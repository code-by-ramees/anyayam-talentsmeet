<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::withCount(['groups', 'programs'])->get();
        return view('admin.sections.index', compact('sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:sections,code'],
            'description' => ['nullable', 'string'],
        ]);

        $section = Section::create($validated);
        ActivityLog::record('SECTION_CREATED', "Created section {$section->name} ({$section->code}).");

        return back()->with('success', "Section '{$section->name}' created successfully.");
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:sections,code,' . $section->id],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $section->update($validated);
        ActivityLog::record('SECTION_UPDATED', "Updated section {$section->name}.");

        return back()->with('success', "Section '{$section->name}' updated successfully.");
    }
}
