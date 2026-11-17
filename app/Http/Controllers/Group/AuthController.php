<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isGroupUser()) {
            return redirect()->route('group.dashboard');
        }

        return view('group.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (! $user->isGroupUser() || ! $user->group_id) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Access denied. Valid group account required.',
                ])->onlyInput('username');
            }

            if ($user->group && $user->group->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Your group account is currently inactive. Please contact the administrator.',
                ])->onlyInput('username');
            }

            $request->session()->regenerate();
            ActivityLog::record('GROUP_LOGIN', "Group '{$user->group->name}' logged into portal.", $user->id);

            return redirect()->intended(route('group.dashboard'))
                ->with('success', "Welcome to the Group Portal, {$user->group->name}!");
        }

        return back()->withErrors([
            'username' => 'The provided group credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        if (Auth::check() && Auth::user()->group) {
            ActivityLog::record('GROUP_LOGOUT', "Group '" . Auth::user()->group->name . "' logged out.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('group.login')
            ->with('info', 'You have been logged out of the Group Portal.');
    }
}
