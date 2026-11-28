<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\Member;
use App\Models\Program;
use App\Models\ProgramRegistration;
use App\Models\Result;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::where('status', true)->get();
        $groups = Group::where('status', 'active')->get();

        $query = Result::with(['program.category', 'program.section', 'group']);

        if ($request->filled('section_id')) {
            $query->whereHas('program', fn($q) => $q->where('section_id', $request->section_id));
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('status')) {
            $isPublished = $request->status === 'published';
            $query->where('is_published', $isPublished);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('participant_name', 'like', "%{$search}%")
                    ->orWhereHas('program', fn($pq) => $pq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"));
            });
        }

        $results = $query->latest()->paginate(20)->withQueryString();

        // Published vs Draft count
        $publishedCount = Result::where('is_published', true)->count();
        $draftCount = Result::where('is_published', false)->count();

        return view('admin.results.index', compact('results', 'sections', 'groups', 'publishedCount', 'draftCount'));
    }

    public function create(Request $request)
    {
        $sections = Section::where('status', true)->get();
        $programs = Program::with(['category', 'section'])->orderBy('name')->get();
        $groups = Group::where('status', 'active')->orderBy('name')->get();

        $selectedProgramId = $request->query('program_id');
        $selectedProgram = $selectedProgramId ? Program::with(['category', 'section', 'results'])->find($selectedProgramId) : null;

        return view('admin.results.create', compact('programs', 'groups', 'sections', 'selectedProgram'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'winners' => ['required', 'array'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $program = Program::with('category')->findOrFail($validated['program_id']);
        $isPublished = $request->boolean('is_published', false);

        $entriesToCreate = [];
        $positions = ['first', 'second', 'third'];

        foreach ($positions as $pos) {
            $rows = $request->input("winners.{$pos}", []);
            if (!is_array($rows)) {
                continue;
            }

            foreach ($rows as $row) {
                $groupId = $row['group_id'] ?? null;
                $participantName = trim($row['participant_name'] ?? '');
                $memberId = !empty($row['member_id']) ? (int) $row['member_id'] : null;

                // Skip blank/unselected rows
                if (empty($groupId) || $participantName === '') {
                    continue;
                }

                // If member_id is provided, ensure group_id matches member's group
                if ($memberId) {
                    $member = Member::find($memberId);
                    if ($member) {
                        $groupId = $member->group_id;
                    }
                }

                // Prevent entering the exact same participant twice for the same position in this program
                $duplicateExists = Result::where('program_id', $program->id)
                    ->where('position', $pos)
                    ->where(function ($q) use ($memberId, $participantName, $groupId) {
                        if ($memberId) {
                            $q->where('member_id', $memberId);
                        } else {
                            $q->where('group_id', $groupId)
                                ->where('participant_name', $participantName);
                        }
                    })
                    ->exists();

                if ($duplicateExists) {
                    $posLabel = ucfirst($pos) . ' Prize';
                    return back()->withErrors([
                        'winners' => "Participant '{$participantName}' already has a {$posLabel} result for '{$program->name}'.",
                    ])->withInput();
                }

                $marks = (isset($row['marks']) && $row['marks'] !== '' && $row['marks'] !== null)
                    ? (int) $row['marks']
                    : $program->category->getMarksForPosition($pos);

                $entriesToCreate[] = [
                    'program_id' => $program->id,
                    'group_id' => (int) $groupId,
                    'member_id' => $memberId,
                    'participant_name' => $participantName,
                    'position' => $pos,
                    'marks' => max(0, $marks),
                    'remarks' => isset($row['remarks']) ? trim($row['remarks']) : null,
                    'is_published' => $isPublished,
                    'published_at' => $isPublished ? now() : null,
                ];
            }
        }

        if (empty($entriesToCreate)) {
            return back()->withErrors([
                'winners' => 'Please select at least one winner (1st, 2nd, or 3rd) before saving.',
            ])->withInput();
        }

        DB::transaction(function () use ($entriesToCreate, $program, $isPublished) {
            foreach ($entriesToCreate as $entry) {
                $result = Result::create($entry);
                $statusText = $isPublished ? 'published immediately' : 'saved as draft';
                ActivityLog::record(
                    'RESULT_ENTERED',
                    "Entered {$result->position} prize for {$program->name} awarded to {$result->participant_name} ({$result->group->name}, {$result->marks} pts) - {$statusText}."
                );
            }

            // Auto update program status to completed
            $program->update(['status' => 'completed']);
        });

        $count = count($entriesToCreate);

        return redirect()->route('admin.results.index')
            ->with('success', "Successfully saved {$count} result(s) for '{$program->name}'!");
    }

    public function edit(Result $result)
    {
        $result->load(['program.category', 'program.section', 'group']);
        $groups = Group::where('status', 'active')->orderBy('name')->get();

        return view('admin.results.edit', compact('result', 'groups'));
    }

    public function update(Request $request, Result $result)
    {
        $validated = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'participant_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'in:first,second,third'],
            'marks' => ['required', 'integer', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $wasPublished = $result->is_published;
        $nowPublished = $request->boolean('is_published');

        $result->update([
            'group_id' => $validated['group_id'],
            'participant_name' => $validated['participant_name'],
            'position' => $validated['position'],
            'marks' => $validated['marks'],
            'remarks' => $validated['remarks'],
            'is_published' => $nowPublished,
            'published_at' => ($nowPublished && !$wasPublished) ? now() : $result->published_at,
        ]);

        ActivityLog::record('RESULT_UPDATED', "Updated result for {$result->program->name} ({$result->getPositionLabel()}).");

        return redirect()->route('admin.results.index')
            ->with('success', "Result for '{$result->program->name}' updated successfully.");
    }

    public function togglePublish(Result $result)
    {
        $newStatus = !$result->is_published;
        $result->update([
            'is_published' => $newStatus,
            'published_at' => $newStatus ? now() : null,
        ]);

        $statusStr = $newStatus ? 'published and is now visible on group portals & public leaderboard' : 'unpublished and reverted to draft';
        ActivityLog::record('RESULT_PUBLISH_TOGGLED', "Result for {$result->program->name} was {$statusStr}.");

        return back()->with('success', "Result has been {$statusStr}.");
    }

    public function publishAllDrafts()
    {
        $count = Result::where('is_published', false)->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        ActivityLog::record('BULK_PUBLISHED_RESULTS', "Bulk published {$count} draft results.");

        return back()->with('success', "Successfully published {$count} draft results!");
    }

    public function destroy(Result $result)
    {
        $programName = $result->program->name;
        $pos = $result->getPositionLabel();
        $result->delete();

        ActivityLog::record('RESULT_DELETED', "Deleted {$pos} for {$programName}.");

        return redirect()->route('admin.results.index')
            ->with('success', "Result deleted successfully.");
    }

    /**
     * AJAX endpoint to return category marks when selecting program & position
     */
    public function getCategoryMarks(Program $program, string $position)
    {
        $program->load('category');
        $marks = $program->category->getMarksForPosition($position);

        return response()->json([
            'success' => true,
            'category_name' => $program->category->name,
            'position' => $position,
            'marks' => $marks,
        ]);
    }

    /**
     * AJAX endpoint to return only registered participants for a program
     */
    public function getProgramParticipants(Program $program)
    {
        $program->load(['section', 'category', 'results.group']);

        $registrations = ProgramRegistration::with(['member.section', 'group'])
            ->where('program_id', $program->id)
            ->get()
            ->sortBy(fn($r) => $r->chest_number ?? $r->member->chest_number ?? $r->member->name ?? '')
            ->values();

        $registeredList = $registrations->map(function ($reg) {
            $m = $reg->member;
            $g = $reg->group;
            $chest = $reg->chest_number ?: ($m->chest_number ?? null);
            return [
                'member_id' => $m->id ?? null,
                'group_id' => $g->id ?? ($m->group_id ?? null),
                'group_name' => $g->name ?? '',
                'group_code' => $g->code ?? '',
                'name' => $m->name ?? 'Unknown',
                'uid' => $m->uid ?? '',
                'chest_number' => $chest,
                'class_year' => $m->class_year ?? '',
                'participant_name' => $m ? "{$m->name} ({$m->uid})" : 'Unknown',
                'is_registered' => true,
            ];
        });

        $existingResults = $program->results->map(function ($r) {
            return [
                'position' => $r->position,
                'position_label' => $r->getPositionLabel(),
                'badge_class' => $r->getPositionBadgeClass(),
                'group_name' => $r->group->name ?? '',
                'participant_name' => $r->participant_name,
                'marks' => $r->marks,
            ];
        });

        return response()->json([
            'success' => true,
            'program' => [
                'id' => $program->id,
                'name' => $program->name,
                'code' => $program->code,
                'type' => $program->type,
                'section_name' => $program->section->name ?? '',
                'category_name' => $program->category->name ?? '',
                'first_marks' => $program->category->first_marks ?? 0,
                'second_marks' => $program->category->second_marks ?? 0,
                'third_marks' => $program->category->third_marks ?? 0,
            ],
            'registered' => $registeredList,
            'existing_results' => $existingResults,
        ]);
    }
}
