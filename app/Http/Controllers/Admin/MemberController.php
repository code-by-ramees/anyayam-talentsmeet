<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::where('status', 'active')->orderBy('name')->get();

        $query = Member::with('group.section');

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_no', 'like', "%{$search}%")
                  ->orWhere('class_year', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(20)->withQueryString();

        return view('admin.members.index', compact('members', 'groups'));
    }

    public function create()
    {
        $groups = Group::where('status', 'active')->orderBy('name')->get();
        return view('admin.members.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'name' => ['required', 'string', 'max:255'],
            'registration_no' => ['required', 'string', 'max:100', 'unique:members,registration_no'],
            'class_year' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'in:Male,Female,Other'],
        ]);

        $member = Member::create($validated);
        ActivityLog::record('MEMBER_ADDED', "Added member {$member->name} ({$member->registration_no}) to group {$member->group->name}.");

        return redirect()->route('admin.members.index')
            ->with('success', "Member '{$member->name}' added successfully.");
    }

    public function edit(Member $member)
    {
        $groups = Group::where('status', 'active')->orderBy('name')->get();
        return view('admin.members.edit', compact('member', 'groups'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'name' => ['required', 'string', 'max:255'],
            'registration_no' => ['required', 'string', 'max:100', 'unique:members,registration_no,' . $member->id],
            'class_year' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'in:Male,Female,Other'],
        ]);

        $member->update($validated);
        ActivityLog::record('MEMBER_UPDATED', "Updated member {$member->name}.");

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
        $query = Member::with('group.section');
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
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
            fputcsv($file, ['ID', 'Registration / Admission No', 'Member Name', 'Group', 'Section', 'Class / Year', 'Gender', 'Phone']);

            foreach ($members as $m) {
                fputcsv($file, [
                    $m->id,
                    $m->registration_no,
                    $m->name,
                    $m->group->name,
                    $m->group->section->name,
                    $m->class_year,
                    $m->gender,
                    $m->phone,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
