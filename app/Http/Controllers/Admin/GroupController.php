<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['section', 'user'])->withCount('members')->get();
        $sections = Section::where('status', true)->get();

        return view('admin.groups.index', compact('groups', 'sections'));
    }

    public function create()
    {
        $sections = Section::where('status', true)->get();
        return view('admin.groups.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:groups,code'],
            'section_id' => ['required', 'exists:sections,id'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'allow_member_registration' => ['nullable', 'boolean'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Create Group User login
            $user = User::create([
                'name' => $validated['name'] . ' Leader',
                'username' => $validated['username'],
                'email' => $validated['username'] . '@fest.com',
                'password' => Hash::make($validated['password']),
                'role' => 'group_user',
            ]);

            // 2. Create Group
            $group = Group::create([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'section_id' => $validated['section_id'],
                'user_id' => $user->id,
                'color' => $validated['color'] ?? '#16b4a6',
                'description' => $validated['description'] ?? null,
                'allow_member_registration' => $validated['allow_member_registration'] ?? true,
                'status' => $validated['status'],
            ]);

            // Link user to group
            $user->update(['group_id' => $group->id]);

            ActivityLog::record('GROUP_CREATED', "Created group {$group->name} ({$group->code}) with login '{$user->username}'.");
        });

        return redirect()->route('admin.groups.index')
            ->with('success', "Group '{$validated['name']}' and dedicated portal login created successfully!");
    }

    public function edit(Group $group)
    {
        $group->load(['section', 'user']);
        $sections = Section::where('status', true)->get();

        return view('admin.groups.edit', compact('group', 'sections'));
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:groups,code,' . $group->id],
            'section_id' => ['required', 'exists:sections,id'],
            'color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'allow_member_registration' => ['nullable', 'boolean'],
            'status' => ['required', 'in:active,inactive'],
            // Optional password reset
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $group->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'section_id' => $validated['section_id'],
            'color' => $validated['color'] ?: $group->color,
            'description' => $validated['description'],
            'allow_member_registration' => $request->boolean('allow_member_registration'),
            'status' => $validated['status'],
        ]);

        // Reset password if provided
        if (! empty($validated['password']) && $group->user) {
            $group->user->update([
                'password' => Hash::make($validated['password']),
            ]);
            ActivityLog::record('GROUP_PASSWORD_RESET', "Reset password for group {$group->name}.");
        }

        ActivityLog::record('GROUP_UPDATED', "Updated details for group {$group->name}.");

        return redirect()->route('admin.groups.index')
            ->with('success', "Group '{$group->name}' updated successfully.");
    }

    public function toggleStatus(Group $group)
    {
        $newStatus = $group->status === 'active' ? 'inactive' : 'active';
        $group->update(['status' => $newStatus]);

        ActivityLog::record('GROUP_STATUS_TOGGLED', "Changed group {$group->name} status to {$newStatus}.");

        return back()->with('success', "Group '{$group->name}' status changed to {$newStatus}.");
    }

    public function destroy(Group $group)
    {
        $name = $group->name;
        if ($group->user) {
            $group->user->delete();
        }
        $group->delete();

        ActivityLog::record('GROUP_DELETED', "Deleted group {$name}.");

        return redirect()->route('admin.groups.index')
            ->with('success', "Group '{$name}' has been deleted.");
    }
}
