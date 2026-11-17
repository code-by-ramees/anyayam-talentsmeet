<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('programs')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:categories,code'],
            'description' => ['nullable', 'string'],
            'first_marks' => ['required', 'integer', 'min:1'],
            'second_marks' => ['required', 'integer', 'min:0'],
            'third_marks' => ['required', 'integer', 'min:0'],
        ]);

        $category = Category::create($validated);
        ActivityLog::record('CATEGORY_CREATED', "Created category {$category->name} with marks 1st: {$category->first_marks}, 2nd: {$category->second_marks}, 3rd: {$category->third_marks}.");

        return back()->with('success', "Category '{$category->name}' created successfully.");
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:categories,code,' . $category->id],
            'description' => ['nullable', 'string'],
            'first_marks' => ['required', 'integer', 'min:1'],
            'second_marks' => ['required', 'integer', 'min:0'],
            'third_marks' => ['required', 'integer', 'min:0'],
        ]);

        $category->update($validated);
        ActivityLog::record('CATEGORY_UPDATED', "Updated marks for category {$category->name}.");

        return back()->with('success', "Category '{$category->name}' updated successfully.");
    }

    public function destroy(Category $category)
    {
        if ($category->programs()->exists()) {
            return back()->with('error', "Cannot delete category '{$category->name}' because it contains associated programs.");
        }

        $name = $category->name;
        $category->delete();

        ActivityLog::record('CATEGORY_DELETED', "Deleted category {$name}.");

        return back()->with('success', "Category '{$name}' deleted successfully.");
    }
}
