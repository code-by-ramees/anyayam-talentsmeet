<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'fest_name' => Setting::get('fest_name', "Masa Talents' Meet 26"),
            'college_name' => Setting::get('college_name', "Shamsul Ulama Islamic & Arts College (SNEC)"),
            'edition' => Setting::get('edition', "Meet '26"),
            'allow_group_member_add' => Setting::get('allow_group_member_add', '1'),
            'allow_event_registration' => Setting::get('allow_event_registration', '1'),
            'tie_breaker' => Setting::get('tie_breaker', 'most_first_prizes'),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'fest_name' => ['required', 'string', 'max:255'],
            'college_name' => ['required', 'string', 'max:255'],
            'edition' => ['nullable', 'string', 'max:100'],
            'allow_group_member_add' => ['nullable', 'boolean'],
            'tie_breaker' => ['required', 'string'],
        ]);

        Setting::set('fest_name', $validated['fest_name']);
        Setting::set('college_name', $validated['college_name']);
        Setting::set('edition', $validated['edition'] ?? '');
        Setting::set('allow_group_member_add', $request->boolean('allow_group_member_add') ? '1' : '0');
        Setting::set('allow_event_registration', $request->boolean('allow_event_registration') ? '1' : '0');
        Setting::set('tie_breaker', $validated['tie_breaker']);

        ActivityLog::record('SETTINGS_UPDATED', 'Updated festival global settings.');

        return back()->with('success', 'Festival settings saved successfully.');
    }
}
