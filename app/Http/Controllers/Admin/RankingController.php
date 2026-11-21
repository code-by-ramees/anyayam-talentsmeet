<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Group;
use App\Models\Section;
use App\Models\Setting;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $festName = Setting::get('fest_name', "SNEC Talents' Meet 2026-27");
        $sections = Section::where('status', true)->get();
        $categories = Category::all();

        $activeTab = $request->get('tab', 'overall');

        $overallRankings = $this->calculateRankings();
        $bakalooriyyaRankings = $this->calculateRankings('Bakalooriyya');
        $sanaviyyaRankings = $this->calculateRankings('Sanaviyya Ulya');

        // Category breakdown
        $categoryRankings = [];
        foreach ($categories as $cat) {
            $categoryRankings[$cat->id] = [
                'category' => $cat,
                'rankings' => $this->calculateRankings(null, $cat->id),
            ];
        }

        return view('admin.rankings.index', compact(
            'festName',
            'sections',
            'categories',
            'activeTab',
            'overallRankings',
            'bakalooriyyaRankings',
            'sanaviyyaRankings',
            'categoryRankings'
        ));
    }

    /**
     * Printable Scoresheet Report
     */
    public function printView(Request $request)
    {
        $festName = Setting::get('fest_name', "SNEC Talents' Meet 2026-27");
        $collegeName = Setting::get('college_name', "Shamsul Ulama Islamic & Arts College");
        $sectionFilter = $request->get('section');

        $overallRankings = $this->calculateRankings($sectionFilter);

        return view('admin.rankings.print', compact('festName', 'collegeName', 'sectionFilter', 'overallRankings'));
    }

    protected function calculateRankings(?string $sectionName = null, ?int $categoryId = null)
    {
        $query = Group::where('status', 'active')->with(['publishedResults.program.section', 'publishedResults.program.category']);

        $groups = $query->get();

        $ranked = $groups->map(function ($group) use ($sectionName, $categoryId) {
            $results = $group->publishedResults;

            if ($sectionName) {
                // Include results from the specific section AND General section (open to all)
                $results = $results->filter(
                    fn($r) => $r->program && $r->program->section &&
                        ($r->program->section->name === $sectionName || $r->program->section->name === 'General')
                );
            }

            if ($categoryId) {
                $results = $results->filter(fn($r) => $r->program && $r->program->category_id === $categoryId);
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

        // Tie-breaker sort: Total marks DESC -> 1st prizes DESC -> 2nd prizes DESC -> 3rd prizes DESC
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

        return $sorted->values()->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });
    }
}
