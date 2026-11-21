<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Group;
use App\Models\Member;
use App\Models\Program;
use App\Models\ProgramRegistration;
use App\Models\Result;
use App\Models\Section;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    /**
     * Get the authenticated group
     */
    protected function currentGroup(): Group
    {
        return Auth::user()->group;
    }

    /**
     * Group Portal Dashboard
     */
    public function dashboard()
    {
        $group = $this->currentGroup()->load(['members']);
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");

        // My Group's published awards
        $myResults = Result::with(['program.category', 'program.section'])
            ->where('group_id', $group->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->get();

        $totalMarks = (int) $myResults->sum('marks');
        $firstPrizes = $myResults->where('position', 'first')->count();
        $secondPrizes = $myResults->where('position', 'second')->count();
        $thirdPrizes = $myResults->where('position', 'third')->count();

        // Calculate current group rank in overall and sections
        $overallRank = $this->getRankForGroup($group->id);
        $bakalooriyyaRank = $this->getRankForGroup($group->id, 'Bakalooriyya');
        $sanaviyyaRank = $this->getRankForGroup($group->id, 'Sanaviyya Ulya');

        // Upcoming / ongoing programs across sections
        $sectionPrograms = Program::with(['category', 'section'])
            ->orderBy('name')
            ->take(5)
            ->get();

        return view('group.dashboard', compact(
            'group',
            'festName',
            'myResults',
            'totalMarks',
            'firstPrizes',
            'secondPrizes',
            'thirdPrizes',
            'overallRank',
            'bakalooriyyaRank',
            'sanaviyyaRank',
            'sectionPrograms'
        ));
    }

    /**
     * Group Profile Page
     */
    public function profile()
    {
        $group = $this->currentGroup()->load(['user', 'members.section']);
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");

        return view('group.profile', compact('group', 'festName'));
    }

    /**
     * Group Members Management
     */
    public function members(Request $request)
    {
        $group = $this->currentGroup();
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");
        $sections = Section::where('status', true)->get();

        $globalAllowAdd = Setting::get('allow_group_member_add', '1') === '1';
        $canAddMembers = $globalAllowAdd && $group->allow_member_registration;

        $query = Member::with('section')->where('group_id', $group->id);

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('uid', 'like', "%{$search}%")
                    ->orWhere('class_year', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('group.members.index', compact('group', 'festName', 'members', 'sections', 'canAddMembers'));
    }

    /**
     * Add Member by Group (if enabled)
     */
    public function storeMember(Request $request)
    {
        $group = $this->currentGroup();
        $globalAllowAdd = Setting::get('allow_group_member_add', '1') === '1';

        if (!$globalAllowAdd || !$group->allow_member_registration) {
            return back()->with('error', 'Member registration by groups has been disabled by the administrator.');
        }

        $validated = $request->validate([
            'section_id' => ['required', 'exists:sections,id'],
            'name' => ['required', 'string', 'max:255'],
            'uid' => ['required', 'string', 'max:100', 'unique:members,uid'],
            'class_year' => ['required', 'string', 'in:H1,H2,D1,D2,D3,D4'],
        ]);

        $validated['group_id'] = $group->id;

        $member = Member::create($validated);
        ActivityLog::record('GROUP_ADDED_MEMBER', "Group '{$group->name}' added member {$member->name} ({$member->uid}) in section {$member->section->name}.");

        return back()->with('success', "Member '{$member->name}' added to your group successfully.");
    }

    public function destroyMember(Member $member)
    {
        $group = $this->currentGroup();

        if ($member->group_id !== $group->id) {
            abort(403);
        }

        $globalAllowAdd = Setting::get('allow_group_member_add', '1') === '1';
        if (!$globalAllowAdd || !$group->allow_member_registration) {
            return back()->with('error', 'Member management has been locked by the administrator.');
        }

        $name = $member->name;
        $member->delete();

        ActivityLog::record('GROUP_REMOVED_MEMBER', "Group '{$group->name}' removed member {$name}.");

        return back()->with('success', "Member '{$name}' removed.");
    }

    /**
     * Available Programs & Schedule for Group
     */
    public function programs(Request $request)
    {
        $group = $this->currentGroup();
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");
        $categories = Category::all();
        $sections = Section::where('status', true)->get();

        $query = Program::with(['category', 'section']);

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stage_type')) {
            $query->where('stage_type', $request->stage_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $programs = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('group.programs.index', compact('group', 'festName', 'programs', 'categories', 'sections'));
    }

    /**
     * View Published Results won by this group
     */
    public function results()
    {
        $group = $this->currentGroup();
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");

        $results = Result::with(['program.category', 'program.section'])
            ->where('group_id', $group->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(15);

        $totalMarks = (int) Result::where('group_id', $group->id)->where('is_published', true)->sum('marks');

        return view('group.results.index', compact('group', 'festName', 'results', 'totalMarks'));
    }

    /**
     * View Full Leaderboard & Section Rankings in Portal
     */
    public function rankings(Request $request)
    {
        $group = $this->currentGroup();
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");
        $sections = Section::where('status', true)->get();

        $overallRankings = $this->calculateRankings();
        $bakalooriyyaRankings = $this->calculateRankings('Bakalooriyya');
        $sanaviyyaRankings = $this->calculateRankings('Sanaviyya Ulya');

        return view('group.rankings', compact('group', 'festName', 'sections', 'overallRankings', 'bakalooriyyaRankings', 'sanaviyyaRankings'));
    }

    /**
     * Calculate position rank
     */
    protected function getRankForGroup(int $groupId, ?string $sectionName = null): int
    {
        $rankings = $this->calculateRankings($sectionName);
        foreach ($rankings as $item) {
            if ($item['group']->id === $groupId) {
                return $item['rank'];
            }
        }

        return 1;
    }

    protected function calculateRankings(?string $sectionName = null)
    {
        $query = Group::where('status', 'active')->with(['publishedResults.program.section']);

        $groups = $query->get();

        $ranked = $groups->map(function ($group) use ($sectionName) {
            $results = $group->publishedResults;

            if ($sectionName) {
                // Include results from the specific section AND General section (open to all)
                $results = $results->filter(
                    fn($r) => $r->program && $r->program->section &&
                        ($r->program->section->name === $sectionName || $r->program->section->name === 'General')
                );
            }

            $totalMarks = (int) $results->sum('marks');
            $firstCount = $results->where('position', 'first')->count();
            $secondCount = $results->where('position', 'second')->count();
            $thirdCount = $results->where('position', 'third')->count();

            return [
                'group' => $group,
                'total_marks' => $totalMarks,
                'first_prizes' => $firstCount,
                'second_prizes' => $secondCount,
                'third_prizes' => $thirdCount,
                'total_prizes' => $firstCount + $secondCount + $thirdCount,
            ];
        })->sort(function ($a, $b) {
            if ($a['total_marks'] !== $b['total_marks']) {
                return $b['total_marks'] <=> $a['total_marks'];
            }
            if ($a['first_prizes'] !== $b['first_prizes']) {
                return $b['first_prizes'] <=> $a['first_prizes'];
            }
            if ($a['second_prizes'] !== $b['second_prizes']) {
                return $b['second_prizes'] <=> $a['second_prizes'];
            }
            return $b['third_prizes'] <=> $a['third_prizes'];
        })->values();

        return $ranked->values()->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });
    }

    /**
     * Group Event Registrations list
     */
    public function registrations(Request $request)
    {
        $group = $this->currentGroup();
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");
        $sections = Section::where('status', true)->get();
        $categories = Category::all();
        $isRegistrationOpen = Setting::get('allow_event_registration', '1') === '1';

        $query = ProgramRegistration::with(['program.section', 'program.category', 'member.section'])
            ->where('group_id', $group->id);

        if ($request->filled('section_id')) {
            $query->whereHas('program', fn ($q) => $q->where('section_id', $request->section_id));
        }

        if ($request->filled('category_id')) {
            $query->whereHas('program', fn ($q) => $q->where('category_id', $request->category_id));
        }

        if ($request->filled('stage_type')) {
            $query->whereHas('program', fn ($q) => $q->where('stage_type', $request->stage_type));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', function ($mq) use ($search) {
                    $mq->where('name', 'like', "%{$search}%")
                       ->orWhere('uid', 'like', "%{$search}%");
                })->orWhereHas('program', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        $registrations = $query->latest()->paginate(15)->withQueryString();

        // Available programs for registration
        $availablePrograms = Program::with(['section', 'category'])
            ->where('status', '!=', 'cancelled')
            ->orderBy('name')
            ->get();

        $registeredProgramIds = ProgramRegistration::where('group_id', $group->id)
            ->pluck('program_id')
            ->unique()
            ->toArray();

        $totalRegisteredEvents = count($registeredProgramIds);
        $totalRegisteredParticipants = ProgramRegistration::where('group_id', $group->id)->count();

        return view('group.registrations.index', compact(
            'group',
            'festName',
            'sections',
            'categories',
            'registrations',
            'availablePrograms',
            'registeredProgramIds',
            'isRegistrationOpen',
            'totalRegisteredEvents',
            'totalRegisteredParticipants'
        ));
    }

    /**
     * Store new Event Registration from Group
     */
    public function storeRegistration(Request $request)
    {
        $group = $this->currentGroup();
        $isRegistrationOpen = Setting::get('allow_event_registration', '1') === '1';

        if (!$isRegistrationOpen) {
            return back()->with('error', 'Event registration is currently closed by festival administrators.');
        }

        $validated = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'member_ids' => ['required', 'array', 'min:1'],
            'member_ids.*' => ['exists:members,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $program = Program::with('section')->findOrFail($validated['program_id']);

        if (in_array($program->status, ['completed', 'cancelled'])) {
            return back()->with('error', "Cannot register for a program that is {$program->status}.");
        }

        $newMemberIds = array_unique($validated['member_ids']);
        $newCount = count($newMemberIds);

        // Check already registered members in this program
        $alreadyRegistered = ProgramRegistration::where('program_id', $program->id)
            ->whereIn('member_id', $newMemberIds)
            ->pluck('member_id')
            ->toArray();

        if (!empty($alreadyRegistered)) {
            $memberNames = Member::whereIn('id', $alreadyRegistered)->pluck('name')->implode(', ');
            return back()->with('error', "The following member(s) are already registered for '{$program->name}': {$memberNames}");
        }

        // Check group registration limit for this program
        $existingCount = ProgramRegistration::where('program_id', $program->id)
            ->where('group_id', $group->id)
            ->count();

        if (($existingCount + $newCount) > $program->max_participants) {
            return back()->with('error', "Cannot register {$newCount} member(s). Maximum allowed participants per group for '{$program->name}' is {$program->max_participants} (Already registered: {$existingCount}).");
        }

        $isGeneralProgram = strtolower($program->section->name) === 'general' || strtoupper($program->section->code) === 'GEN';

        $addedCount = 0;
        foreach ($newMemberIds as $memberId) {
            $member = Member::where('id', $memberId)->where('group_id', $group->id)->first();
            if (!$member) {
                continue;
            }

            // Verify section compatibility unless General section
            if (!$isGeneralProgram && $member->section_id !== $program->section_id) {
                return back()->with('error', "Member '{$member->name}' cannot compete in '{$program->name}' because the event is restricted to {$program->section->name}.");
            }

            ProgramRegistration::create([
                'program_id' => $program->id,
                'group_id' => $group->id,
                'member_id' => $member->id,
                'registered_by' => 'group',
                'notes' => $validated['notes'] ?? null,
            ]);
            $addedCount++;
        }

        ActivityLog::record('EVENT_REGISTRATION_GROUP', "Group '{$group->name}' registered {$addedCount} member(s) for '{$program->name}'.");

        return back()->with('success', "Successfully registered {$addedCount} participant(s) for '{$program->name}'!");
    }

    /**
     * Cancel / Delete an Event Registration by Group
     */
    public function destroyRegistration(ProgramRegistration $registration)
    {
        $group = $this->currentGroup();
        $isRegistrationOpen = Setting::get('allow_event_registration', '1') === '1';

        if (!$isRegistrationOpen) {
            return back()->with('error', 'Cannot modify registrations while event registration is closed.');
        }

        if ($registration->group_id !== $group->id) {
            abort(403, 'Unauthorized action.');
        }

        $programName = $registration->program->name ?? 'Program';
        $memberName = $registration->member->name ?? 'Member';

        $registration->delete();

        ActivityLog::record('EVENT_REGISTRATION_CANCELLED', "Group '{$group->name}' removed {$memberName} from {$programName}.");

        return back()->with('success', "Removed registration of {$memberName} for '{$programName}'.");
    }

    /**
     * AJAX helper to get group's eligible members for a selected program
     */
    public function getEligibleMembersForProgram(Program $program)
    {
        $group = $this->currentGroup();
        $isGeneral = strtolower($program->section->name) === 'general' || strtoupper($program->section->code) === 'GEN';

        $membersQuery = Member::with('section')->where('group_id', $group->id);
        if (!$isGeneral) {
            $membersQuery->where('section_id', $program->section_id);
        }
        $members = $membersQuery->orderBy('name')->get();

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
