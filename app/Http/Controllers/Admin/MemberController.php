<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\Member;
use App\Models\Section;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::where('status', 'active')->orderBy('name')->get();
        $sections = Section::where('status', true)->get();

        $query = Member::with(['group', 'section']);

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('uid', 'like', "%{$search}%")
                    ->orWhere('chest_number', 'like', "%{$search}%")
                    ->orWhere('class_year', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(20)->withQueryString();

        return view('admin.members.index', compact('members', 'groups', 'sections'));
    }

    public function create()
    {
        $groups = Group::where('status', 'active')->orderBy('name')->get();
        $sections = Section::where('status', true)->get();

        return view('admin.members.create', compact('groups', 'sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'name' => ['required', 'string', 'max:255'],
            'uid' => ['required', 'string', 'max:100', 'unique:members,uid'],
            'chest_number' => ['required', 'string', 'max:50', 'unique:members,chest_number'],
            'class_year' => ['required', 'string', 'in:H1,H2,D1,D2,D3,D4'],
        ]);

        $member = Member::create($validated);
        ActivityLog::record('MEMBER_ADDED', "Added member {$member->name} (UID: {$member->uid}, Chest #{$member->chest_number}) to group {$member->group->name} [{$member->section->name}].");

        return redirect()->route('admin.members.index')
            ->with('success', "Member '{$member->name}' added successfully.");
    }

    public function edit(Member $member)
    {
        $groups = Group::where('status', 'active')->orderBy('name')->get();
        $sections = Section::where('status', true)->get();

        return view('admin.members.edit', compact('member', 'groups', 'sections'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'name' => ['required', 'string', 'max:255'],
            'uid' => ['required', 'string', 'max:100', 'unique:members,uid,' . $member->id],
            'chest_number' => ['required', 'string', 'max:50', 'unique:members,chest_number,' . $member->id],
            'class_year' => ['required', 'string', 'in:H1,H2,D1,D2,D3,D4'],
        ]);

        $member->update($validated);
        ActivityLog::record('MEMBER_UPDATED', "Updated member {$member->name} (Chest #{$member->chest_number}).");

        return redirect()->route('admin.members.index')
            ->with('success', "Member '{$member->name}' updated successfully.");
    }

    public function destroy(Member $member)
    {
        $name = $member->name;
        $member->delete();

        ActivityLog::record('MEMBER_DELETED', "Deleted member {$name}.");

        return back()->with('success', "Member '{$name}' deleted successfully.");
    }

    /**
     * CSV Export of Member List
     */
    public function export(Request $request)
    {
        $query = Member::with(['group', 'section']);
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $members = $query->orderBy('name')->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=fest_members_' . date('Y-m-d') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($members) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'UID', 'Member Name', 'Group', 'Section', 'Class']);

            foreach ($members as $m) {
                fputcsv($file, [
                    $m->id,
                    $m->uid,
                    $m->name,
                    $m->group->name ?? 'N/A',
                    $m->section->name ?? 'N/A',
                    $m->class_year,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
