<?php

namespace App\Http\Controllers\Admin;

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

class DashboardController extends Controller
{
    public function index()
    {
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");

        // Key Dashboard Statistics
        $totalGroups = Group::count();
        $totalMembers = Member::count();
        $totalPrograms = Program::count();
        $totalCategories = Category::count();
        $totalResultsEntered = Result::count();
        $totalPublishedResults = Result::where('is_published', true)->count();
        $draftResultsCount = Result::where('is_published', false)->count();

        // Sections breakdown
        $sections = Section::withCount(['members', 'programs'])->get();

        // Top 5 Groups Leaderboard
        $groups = Group::with(['results', 'members'])->get();
        $rankings = $groups->map(function ($group) {
            $results = $group->results;
            $published = $results->where('is_published', true);
            return [
                'group' => $group,
                'total_marks' => (int) $published->sum('marks'),
                'draft_marks' => (int) $results->where('is_published', false)->sum('marks'),
                'first_prizes' => $published->where('position', 'first')->count(),
                'second_prizes' => $published->where('position', 'second')->count(),
                'third_prizes' => $published->where('position', 'third')->count(),
                'members_count' => $group->members()->count(),
            ];
        })->sortByDesc('total_marks')->values();

        // Recent Results entered
        $recentResults = Result::with(['program.category', 'program.section', 'group'])
            ->latest()
            ->take(6)
            ->get();

        // Recent Activity Logs
        $recentActivities = ActivityLog::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'festName',
            'totalGroups',
            'totalMembers',
            'totalPrograms',
            'totalCategories',
            'totalResultsEntered',
            'totalPublishedResults',
            'draftResultsCount',
            'sections',
            'rankings',
            'recentResults',
            'recentActivities'
        ));
    }
}
