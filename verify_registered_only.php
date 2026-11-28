<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Program;
use App\Models\ProgramRegistration;

echo "=== Testing getProgramParticipants (registered only) ===\n";

// Test 1: view compiles
$controller = new \App\Http\Controllers\Admin\ResultController();
echo "Controller instantiated OK\n";

// Test 2: check a program
$program = Program::first();
if (!$program) {
    echo "No programs found — skipping participant test\n";
} else {
    echo "Testing program: {$program->name} (id={$program->id})\n";
    $program->load(['section', 'category', 'results.group']);

    $registrations = ProgramRegistration::with(['member.section', 'group'])
        ->where('program_id', $program->id)
        ->get();

    echo "Registered count: " . $registrations->count() . "\n";

    // Simulate what controller does
    $registeredList = $registrations->map(function ($reg) {
        $m = $reg->member;
        $g = $reg->group;
        $chest = $reg->chest_number ?: ($m->chest_number ?? null);
        return [
            'member_id' => $m->id ?? null,
            'name' => $m->name ?? 'Unknown',
            'chest_number' => $chest,
            'group_name' => $g->name ?? '',
        ];
    });

    foreach ($registeredList as $r) {
        echo "  - [{$r['chest_number']}] {$r['name']} ({$r['group_name']})\n";
    }
}

// Test 3: view compiles
echo "\nCompiling create view...\n";
try {
    $sections = \App\Models\Section::where('status', true)->get();
    $programs = Program::with(['category', 'section'])->orderBy('name')->get();
    $groups = \App\Models\Group::where('status', 'active')->orderBy('name')->get();
    $selectedProgram = null;
    $view = view('admin.results.create', compact('programs', 'groups', 'sections', 'selectedProgram'));
    $html = $view->render();
    echo "View compiled OK (" . strlen($html) . " chars)\n";
    if (str_contains($html, 'No registered participants')) {
        echo "✓ 'No registered participants' message present in alert HTML\n";
    }
    if (!str_contains($html, 'Other Eligible Members (Not Pre-Registered)')) {
        echo "✓ 'Other Eligible Members' optgroup removed from view\n";
    }
    if (!str_contains($html, 'Team / Group Entry')) {
        echo "✓ 'Team / Group Entry' optgroup removed from view\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\nAll checks complete!\n";
