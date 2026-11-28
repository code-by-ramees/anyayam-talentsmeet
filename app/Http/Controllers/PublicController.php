<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use App\Models\Program;
use App\Models\Result;
use App\Models\Section;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Public Festival Homepage & Live Leaderboard
     */
    public function index()
    {
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");
        $collegeName = Setting::get('college_name', "Shamsul Ulama Islamic & Arts College");
        $edition = Setting::get('edition', "Meet '26");
        $tagline = Setting::get('tagline', "“കല കലഹിക്കട്ടെ; സത്യം സംസാരിക്കട്ടെ”");
        $themeTitle = Setting::get('theme_title', "അന്യായം");

        $sections = Section::where('status', true)->get();
        $categories = Category::all();

        // Calculate Overall Rankings from published results
        $overallRankings = $this->calculateGroupRankings();

        // Section-wise rankings
        $bakalooriyyaRankings = $this->calculateGroupRankings('Bakalooriyya');
        $sanaviyyaRankings = $this->calculateGroupRankings('Sanaviyya Ulya');

        // Individual Score Leaderboard
        $individualRankings = Result::getIndividualLeaderboard();

        // Recent Published Results
        $latestResults = Result::with(['program.category', 'program.section', 'group'])
            ->where('is_published', true)
            ->latest('published_at')
            ->take(8)
            ->get();

        // Counts
        $totalPrograms = Program::count();
        $completedPrograms = Program::where('status', 'completed')->count();
        $totalGroups = Group::where('status', 'active')->count();

        return view('public.index', compact(
            'festName',
            'collegeName',
            'edition',
            'tagline',
            'themeTitle',
            'sections',
            'categories',
            'overallRankings',
            'bakalooriyyaRankings',
            'sanaviyyaRankings',
            'individualRankings',
            'latestResults',
            'totalPrograms',
            'completedPrograms',
            'totalGroups'
        ));
    }

    /**
     * All Published Results with Search & Filter
     */
    public function results(Request $request)
    {
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");
        $sections = Section::where('status', true)->get();
        $categories = Category::all();
        $groups = Group::where('status', 'active')->get();

        $query = Result::with(['program.category', 'program.section', 'group'])
            ->where('is_published', true);

        if ($request->filled('section_id')) {
            $query->whereHas('program', fn($q) => $q->where('section_id', $request->section_id));
        }

        if ($request->filled('category_id')) {
            $query->whereHas('program', fn($q) => $q->where('category_id', $request->category_id));
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('participant_name', 'like', "%{$search}%")
                    ->orWhereHas('program', fn($pq) => $pq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"));
            });
        }

        $results = $query->latest('published_at')->paginate(15)->withQueryString();

        return view('public.results', compact('festName', 'sections', 'categories', 'groups', 'results'));
    }

    /**
     * Detailed Leaderboard / Rankings View
     */
    public function rankings(Request $request)
    {
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");
        $sections = Section::where('status', true)->get();

        $activeTab = $request->get('tab', 'overall');

        $overallRankings = $this->calculateGroupRankings();
        $bakalooriyyaRankings = $this->calculateGroupRankings('Bakalooriyya');
        $sanaviyyaRankings = $this->calculateGroupRankings('Sanaviyya Ulya');
        $individualRankings = Result::getIndividualLeaderboard();

        return view('public.rankings', compact(
            'festName',
            'sections',
            'activeTab',
            'overallRankings',
            'bakalooriyyaRankings',
            'sanaviyyaRankings',
            'individualRankings'
        ));
    }

    /**
     * Public Program Schedule / List
     */
    public function programs(Request $request)
    {
        $festName = Setting::get('fest_name', "Masa Talents' Meet 26");
        $sections = Section::where('status', true)->get();
        $categories = Category::all();

        $query = Program::with(['section', 'category']);

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stage_type')) {
            $query->where('stage_type', $request->stage_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('public.programs', compact('festName', 'sections', 'categories', 'programs'));
    }

    /**
     * Helper to calculate rankings with tie-breaking policy
     */
    protected function calculateGroupRankings(?string $sectionName = null)
    {
        $query = Group::where('status', 'active')->with(['publishedResults.program.section']);

        $groups = $query->get();

        // Calculate marks, first, second, third prizes
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
        });

        // Predefined tie-breaking policy: Total Marks DESC, then Most First Prizes DESC, then Second DESC, then Third DESC
        $sorted = $ranked->sort(function ($a, $b) {
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

        // Assign Rank numbers (with ties handled gracefully)
        return $sorted->values()->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });
    }
}
