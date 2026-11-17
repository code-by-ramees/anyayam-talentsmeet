<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Program;
use App\Models\Section;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::where('status', true)->get();
        $categories = Category::all();

        $query = Program::with(['section', 'category', 'results']);

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        return view('admin.programs.index', compact('programs', 'sections', 'categories'));
    }

    public function create()
    {
        $sections = Section::where('status', true)->get();
        $categories = Category::all();
        return view('admin.programs.create', compact('sections', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:programs,code'],
            'section_id' => ['required', 'exists:sections,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:individual,group'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:scheduled,ongoing,completed,cancelled'],
            'venue' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $validated['max_participants'] = $validated['max_participants'] ?: 1;

        $program = Program::create($validated);
        ActivityLog::record('PROGRAM_CREATED', "Created program {$program->name} ({$program->code}) in section {$program->section->name}.");

        return redirect()->route('admin.programs.index')
            ->with('success', "Program '{$program->name}' created successfully.");
    }

    public function edit(Program $program)
    {
        $sections = Section::where('status', true)->get();
        $categories = Category::all();
        return view('admin.programs.edit', compact('program', 'sections', 'categories'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:programs,code,' . $program->id],
            'section_id' => ['required', 'exists:sections,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:individual,group'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:scheduled,ongoing,completed,cancelled'],
            'venue' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $validated['max_participants'] = $validated['max_participants'] ?: 1;

        $program->update($validated);
        ActivityLog::record('PROGRAM_UPDATED', "Updated program {$program->name}.");

        return redirect()->route('admin.programs.index')
            ->with('success', "Program '{$program->name}' updated successfully.");
    }

    public function destroy(Program $program)
    {
        $name = $program->name;
        $program->delete();

        ActivityLog::record('PROGRAM_DELETED', "Deleted program {$name}.");

        return redirect()->route('admin.programs.index')
            ->with('success', "Program '{$name}' deleted successfully.");
    }
}
