<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Section;
use App\Models\Group;
use App\Models\Program;
use App\Models\Result;

$checks = [];

// 1. public/index compiles
try {
    $sections = Section::where('status', true)->get();
    $groups = Group::where('status', 'active')->get();
    $overallRankings = collect();
    $bakalooriyyaRankings = collect();
    $sanaviyyaRankings = collect();
    $individualRankings = collect();
    $latestResults = collect();
    $totalGroups = $groups->count();
    $totalPrograms = Program::count();
    $completedPrograms = Program::where('status', 'completed')->count();
    $festName = "Masa Talents' Meet 26";
    $collegeName = "SNEC";
    $edition = "Meet '26";
    $tagline = '"കല കലഹിക്കട്ടെ; സത്യം സംസാരിക്കട്ടെ"';

    $html = view('public.index', compact(
        'sections', 'groups', 'overallRankings', 'bakalooriyyaRankings',
        'sanaviyyaRankings', 'individualRankings', 'latestResults',
        'totalGroups', 'totalPrograms', 'completedPrograms',
        'festName', 'collegeName', 'edition', 'tagline'
    ))->render();
    $checks['public/index'] = strlen($html) > 1000 ? 'OK (' . strlen($html) . ' chars)' : 'SHORT';
    $checks['hero-mobile-class'] = str_contains($html, 'hero-cta-wrap') ? '✓ hero-cta-wrap found' : '✗ missing';
    $checks['tabs-scrollable'] = str_contains($html, 'flex-nowrap') ? '✓ flex-nowrap tabs' : '✗ missing';
    $checks['result-cards-col6'] = str_contains($html, 'col-6') ? '✓ col-6 for mobile cards' : '✗ missing';
} catch (\Throwable $e) {
    $checks['public/index'] = 'ERROR: ' . $e->getMessage();
}

// 2. leaderboard_table partial
try {
    $rankings = [];
    $title = 'Test';
    $html2 = view('public.partials.leaderboard_table', compact('rankings', 'title'))->render();
    $checks['leaderboard_table'] = 'OK';
    $checks['mobile-card-list'] = str_contains($html2, 'd-sm-none') ? '✓ mobile cards present' : '✗ missing';
} catch (\Throwable $e) {
    $checks['leaderboard_table'] = 'ERROR: ' . $e->getMessage();
}

// 3. individual leaderboard
try {
    $individualRankings = collect();
    $title = 'Individual';
    $html3 = view('partials.individual_leaderboard_table', compact('individualRankings', 'title'))->render();
    $checks['individual_lb'] = 'OK';
    $checks['mobile-cards-ind'] = str_contains($html3, 'd-md-none') ? '✓ mobile cards present' : '✗ missing';
} catch (\Throwable $e) {
    $checks['individual_lb'] = 'ERROR: ' . $e->getMessage();
}

// 4. app layout
try {
    $html4 = view('layouts.app')->render();
    $checks['layouts/app'] = str_contains($html4, 'hero-cta-wrap') ? '✓ mobile CSS present' : '✗ CSS missing';
} catch (\Throwable $e) {
    $checks['layouts/app'] = 'ERROR: ' . $e->getMessage();
}

foreach ($checks as $k => $v) {
    echo str_pad($k, 30) . " => $v\n";
}
echo "\nDone!\n";
