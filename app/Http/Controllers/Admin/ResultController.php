<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\Member;
use App\Models\Program;
use App\Models\Result;
use App\Models\Section;
use Illuminate\Http\Request;

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
            'group_id' => ['required', 'exists:groups,id'],
            'participant_name' => ['required', 'string', 'max:255'],
            'member_id' => ['nullable', 'exists:members,id'],
            'position' => ['required', 'in:first,second,third'],
            'marks' => ['nullable', 'integer', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $program = Program::with('category')->findOrFail($validated['program_id']);

        // Prevent duplicate position entries for the same program
        $exists = Result::where('program_id', $program->id)
            ->where('position', $validated['position'])
            ->exists();

        if ($exists) {
            $posLabel = ucfirst($validated['position']) . ' Prize';
            return back()->withErrors([
                'position' => "A result for {$posLabel} has already been entered for '{$program->name}'. Please edit the existing result instead.",
            ])->withInput();
        }

        // Auto-fetch marks from category if not explicitly provided
        $marks = $request->filled('marks')
            ? (int) $request->input('marks')
            : $program->category->getMarksForPosition($validated['position']);

        $isPublished = $request->boolean('is_published', false);

        $result = Result::create([
            'program_id' => $program->id,
            'group_id' => $validated['group_id'],
            'member_id' => $validated['member_id'] ?? null,
            'participant_name' => $validated['participant_name'],
            'position' => $validated['position'],
            'marks' => $marks,
            'remarks' => $validated['remarks'] ?? null,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        // Auto update program status to completed
        $program->update(['status' => 'completed']);

        $statusText = $isPublished ? 'published immediately' : 'saved as draft';
        ActivityLog::record('RESULT_ENTERED', "Entered {$result->position} prize for {$program->name} awarded to {$result->group->name} ({$marks} pts) - {$statusText}.");

        return redirect()->route('admin.results.index')
            ->with('success', "Result for '{$program->name}' ({$result->getPositionLabel()}) saved successfully!");
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

        // Check unique position if position was changed
        if ($validated['position'] !== $result->position) {
            $exists = Result::where('program_id', $result->program_id)
                ->where('position', $validated['position'])
                ->where('id', '!=', $result->id)
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    'position' => "A result for " . ucfirst($validated['position']) . " Prize already exists for this program.",
                ])->withInput();
            }
        }

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
}
