<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Group;
use App\Models\Member;
use App\Models\Program;
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
        $group = $this->currentGroup()->load(['section', 'members']);
        $festName = Setting::get('fest_name', "SNEC Talents' Meet 2026-27");

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

        // Calculate current group rank in overall and section
        $overallRank = $this->getRankForGroup($group->id);
        $sectionRank = $this->getRankForGroup($group->id, $group->section_id);

        // Upcoming / ongoing programs in this group's section
        $sectionPrograms = Program::with('category')
            ->where('section_id', $group->section_id)
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
            'sectionRank',
            'sectionPrograms'
        ));
    }

    /**
     * Group Profile Page
     */
    public function profile()
    {
        $group = $this->currentGroup()->load(['section', 'user', 'members']);
        $festName = Setting::get('fest_name', "SNEC Talents' Meet 2026-27");

        return view('group.profile', compact('group', 'festName'));
    }

    /**
     * Group Members Management
     */
    public function members(Request $request)
    {
        $group = $this->currentGroup();
        $festName = Setting::get('fest_name', "SNEC Talents' Meet 2026-27");
        
        $globalAllowAdd = Setting::get('allow_group_member_add', '1') === '1';
        $canAddMembers = $globalAllowAdd && $group->allow_member_registration;

        $query = Member::where('group_id', $group->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_no', 'like', "%{$search}%")
                  ->orWhere('class_year', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('group.members.index', compact('group', 'festName', 'members', 'canAddMembers'));
    }

    /**
     * Add Member by Group (if enabled)
     */
    public function storeMember(Request $request)
    {
        $group = $this->currentGroup();
        $globalAllowAdd = Setting::get('allow_group_member_add', '1') === '1';

        if (! $globalAllowAdd || ! $group->allow_member_registration) {
            return back()->with('error', 'Member registration by groups has been disabled by the administrator.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration_no' => ['required', 'string', 'max:100', 'unique:members,registration_no'],
            'class_year' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'in:Male,Female,Other'],
        ]);

        $validated['group_id'] = $group->id;

        $member = Member::create($validated);
        ActivityLog::record('GROUP_ADDED_MEMBER', "Group '{$group->name}' added member {$member->name} ({$member->registration_no}).");

        return back()->with('success', "Member '{$member->name}' added to your group successfully.");
    }

    public function destroyMember(Member $member)
    {
        $group = $this->currentGroup();

        if ($member->group_id !== $group->id) {
            abort(403);
        }

        $globalAllowAdd = Setting::get('allow_group_member_add', '1') === '1';
        if (! $globalAllowAdd || ! $group->allow_member_registration) {
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
        $festName = Setting::get('fest_name', "SNEC Talents' Meet 2026-27");
        $categories = Category::all();

        $query = Program::with(['category', 'section'])
            ->where('section_id', $group->section_id);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        $programs = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('group.programs.index', compact('group', 'festName', 'programs', 'categories'));
    }

    /**
     * View Published Results won by this group
     */
    public function results()
    {
        $group = $this->currentGroup();
        $festName = Setting::get('fest_name', "SNEC Talents' Meet 2026-27");

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
        $festName = Setting::get('fest_name', "SNEC Talents' Meet 2026-27");

        $overallRankings = $this->calculateRankings();
        $mySectionRankings = $this->calculateRankings($group->section->name);

        return view('group.rankings', compact('group', 'festName', 'overallRankings', 'mySectionRankings'));
    }

    /**
     * Calculate position rank
     */
    protected function getRankForGroup(int $groupId, ?int $sectionId = null): int
    {
        $query = Group::with('publishedResults');
        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        $groups = $query->get()->map(function ($g) {
            return [
                'id' => $g->id,
                'total_marks' => (int) $g->publishedResults->sum('marks'),
                'first_prizes' => $g->publishedResults->where('position', 'first')->count(),
            ];
        })->sort(function ($a, $b) {
            if ($a['total_marks'] !== $b['total_marks']) {
                return $b['total_marks'] <=> $a['total_marks'];
            }
            return $b['first_prizes'] <=> $a['first_prizes'];
        })->values();

        $rank = 1;
        foreach ($groups as $idx => $item) {
            if ($item['id'] === $groupId) {
                return $idx + 1;
            }
        }

        return 1;
    }

    protected function calculateRankings(?string $sectionName = null)
    {
        $query = Group::with(['section', 'publishedResults']);
        if ($sectionName) {
            $query->whereHas('section', fn ($q) => $q->where('name', $sectionName));
        }

        $groups = $query->get();

        $ranked = $groups->map(function ($group) {
            $results = $group->publishedResults;
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
            return $b['second_prizes'] <=> $a['second_prizes'];
        })->values();

        return $ranked->values()->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });
    }
}
