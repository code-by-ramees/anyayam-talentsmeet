<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\Member;
use App\Models\Program;
use App\Models\ProgramRegistration;
use App\Models\Section;
use App\Models\Setting;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::where('status', true)->get();
        $groups = Group::where('status', 'active')->orderBy('name')->get();
        $programs = Program::with('section')->orderBy('name')->get();
        $isRegistrationOpen = Setting::get('allow_event_registration', '1') === '1';

        $query = ProgramRegistration::with(['program.section', 'program.category', 'group', 'member.section']);

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->filled('section_id')) {
            $query->whereHas('program', fn ($q) => $q->where('section_id', $request->section_id));
        }

        if ($request->filled('stage_type')) {
            $query->whereHas('program', fn ($q) => $q->where('stage_type', $request->stage_type));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', function ($mq) use ($search) {
                    $mq->where('name', 'like', "%{$search}%")
                       ->orWhere('uid', 'like', "%{$search}%")
                       ->orWhere('chest_number', 'like', "%{$search}%");
                })->orWhere('chest_number', 'like', "%{$search}%")
                ->orWhereHas('program', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        $registrations = $query->latest()->paginate(20)->withQueryString();

        $totalRegistrations = ProgramRegistration::count();
        $registeredProgramsCount = ProgramRegistration::distinct('program_id')->count('program_id');
        $participatingGroupsCount = ProgramRegistration::distinct('group_id')->count('group_id');

        return view('admin.registrations.index', compact(
            'registrations',
            'sections',
            'groups',
            'programs',
            'isRegistrationOpen',
            'totalRegistrations',
            'registeredProgramsCount',
            'participatingGroupsCount'
        ));
    }

    public function create(Request $request)
    {
        $groups = Group::where('status', 'active')->orderBy('name')->get();
        $programs = Program::with(['section', 'category'])->where('status', '!=', 'cancelled')->orderBy('name')->get();
        $sections = Section::where('status', true)->get();

        $selectedGroupId = $request->query('group_id');
        $selectedProgramId = $request->query('program_id');

        return view('admin.registrations.create', compact('groups', 'programs', 'sections', 'selectedGroupId', 'selectedProgramId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'member_ids' => ['required', 'array', 'min:1'],
            'member_ids.*' => ['exists:members,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $group = Group::findOrFail($validated['group_id']);
        $program = Program::with('section')->findOrFail($validated['program_id']);

        // Check if program is already completed or cancelled
        if (in_array($program->status, ['completed', 'cancelled'])) {
            return back()->with('error', "Cannot register for a program that is {$program->status}.")->withInput();
        }

        $existingCount = ProgramRegistration::where('program_id', $program->id)
            ->where('group_id', $group->id)
            ->count();

        $newMemberIds = array_unique($validated['member_ids']);
        $newCount = count($newMemberIds);

        // Check already registered members in this program
        $alreadyRegistered = ProgramRegistration::where('program_id', $program->id)
            ->whereIn('member_id', $newMemberIds)
            ->pluck('member_id')
            ->toArray();

        if (!empty($alreadyRegistered)) {
            $memberNames = Member::whereIn('id', $alreadyRegistered)->pluck('name')->implode(', ');
            return back()->with('error', "The following member(s) are already registered for '{$program->name}': {$memberNames}")->withInput();
        }

        // Check max participants limit for this group in this program
        if (($existingCount + $newCount) > $program->max_participants) {
            return back()->with('error', "Cannot register {$newCount} member(s). Maximum allowed participants per group for '{$program->name}' is {$program->max_participants} (Already registered: {$existingCount}).")->withInput();
        }

        $isGeneralProgram = strtolower($program->section->name) === 'general' || strtoupper($program->section->code) === 'GEN';

        $addedCount = 0;
        foreach ($newMemberIds as $memberId) {
            $member = Member::where('id', $memberId)->where('group_id', $group->id)->first();
            if (!$member) {
                continue;
            }

            // Chest number is necessary for event registration
            if (empty(trim($member->chest_number ?? ''))) {
                return back()->with('error', "Member '{$member->name}' ({$member->uid}) cannot be registered because they do not have a Chest Number. A Chest Number is required for all event participants. Please edit the student to assign a Chest Number first.")->withInput();
            }

            // Verify section compatibility unless General section
            if (!$isGeneralProgram && $member->section_id !== $program->section_id) {
                return back()->with('error', "Member '{$member->name}' belongs to a different section and cannot compete in '{$program->name}' (Requires {$program->section->name}).")->withInput();
            }

            ProgramRegistration::create([
                'program_id' => $program->id,
                'group_id' => $group->id,
                'member_id' => $member->id,
                'chest_number' => $member->chest_number,
                'registered_by' => 'admin',
                'notes' => $validated['notes'] ?? null,
            ]);
            $addedCount++;
        }

        ActivityLog::record('EVENT_REGISTRATION_ADMIN', "Admin registered {$addedCount} member(s) from '{$group->name}' for program '{$program->name}'.");

        return redirect()->route('admin.registrations.index')
            ->with('success', "Successfully registered {$addedCount} participant(s) for '{$program->name}' under '{$group->name}'.");
    }

    public function destroy(ProgramRegistration $registration)
    {
        $programName = $registration->program->name ?? 'Program';
        $groupName = $registration->group->name ?? 'Group';
        $memberName = $registration->member->name ?? 'Member';

        $registration->delete();

        ActivityLog::record('EVENT_REGISTRATION_DELETED', "Admin removed registration of {$memberName} ({$groupName}) from {$programName}.");

        return back()->with('success', "Registration of {$memberName} for '{$programName}' has been removed.");
    }

    public function toggleStatus()
    {
        $current = Setting::get('allow_event_registration', '1') === '1';
        $new = !$current;
        Setting::set('allow_event_registration', $new ? '1' : '0');

        $statusStr = $new ? 'OPENED' : 'CLOSED';
        ActivityLog::record('REGISTRATION_TOGGLED', "Event registration has been {$statusStr} by admin.");

        return back()->with('success', "Event registration has been {$statusStr} for group portals.");
    }

    /**
     * AJAX helper to return eligible members of a group for a selected program
     */
    public function getEligibleMembers(Request $request)
    {
        $groupId = $request->query('group_id');
        $programId = $request->query('program_id');

        if (!$groupId || !$programId) {
            return response()->json(['success' => false, 'members' => []]);
        }

        $program = Program::with('section')->find($programId);
        $group = Group::find($groupId);

        if (!$program || !$group) {
            return response()->json(['success' => false, 'members' => []]);
        }

        $isGeneral = strtolower($program->section->name) === 'general' || strtoupper($program->section->code) === 'GEN';

        $membersQuery = Member::with('section')->where('group_id', $group->id);
        if (!$isGeneral) {
            $membersQuery->where('section_id', $program->section_id);
        }
        $members = $membersQuery->orderBy('name')->get();

        // Get members already registered in this program
        $alreadyRegisteredMemberIds = ProgramRegistration::where('program_id', $program->id)
            ->pluck('member_id')
            ->toArray();

        $groupRegisteredCount = ProgramRegistration::where('program_id', $program->id)
            ->where('group_id', $group->id)
            ->count();

        $remainingSlots = max(0, $program->max_participants - $groupRegisteredCount);

        $memberData = $members->map(function ($m) use ($alreadyRegisteredMemberIds) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'uid' => $m->uid,
                'chest_number' => $m->chest_number,
                'has_chest_number' => !empty(trim($m->chest_number ?? '')),
                'class_year' => $m->class_year,
                'section_name' => $m->section->name ?? '',
                'is_registered' => in_array($m->id, $alreadyRegisteredMemberIds),
            ];
        });

        return response()->json([
            'success' => true,
            'program' => [
                'name' => $program->name,
                'code' => $program->code,
                'type' => $program->type,
                'max_participants' => $program->max_participants,
                'section_name' => $program->section->name,
                'is_general' => $isGeneral,
            ],
            'group_registered_count' => $groupRegisteredCount,
            'remaining_slots' => $remainingSlots,
            'members' => $memberData,
        ]);
    }
}
