<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
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

            if (! $user->isSuperAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Access denied. You do not have Super Admin permissions.',
                ])->onlyInput('username');
            }

            $request->session()->regenerate();
            ActivityLog::record('ADMIN_LOGIN', 'Super Administrator logged into admin panel.');

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, Super Administrator!');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our administrative records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        ActivityLog::record('ADMIN_LOGOUT', 'Super Administrator logged out.');
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('info', 'You have been logged out of the Super Admin Panel.');
    }

    /**
     * Show Change Password View
     */
    public function showChangePassword()
    {
        return view('admin.change-password');
    }

    /**
     * Update Super Admin Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided current password does not match our records.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::record('ADMIN_PASSWORD_CHANGED', 'Super Administrator changed their account password.');

        return back()->with('success', 'Your password has been changed successfully.');
    }

    /**
     * Update Super Admin Username
     */
    public function updateUsername(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'username_current_password' => ['required', 'string'],
        ], [
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes, and underscores.',
            'username_current_password.required' => 'Please enter your current password to confirm the username change.',
        ]);

        if (! Hash::check($validated['username_current_password'], $user->password)) {
            return back()->withErrors([
                'username_current_password' => 'The provided current password does not match our records.',
            ])->withInput(['username' => $validated['username']]);
        }

        $oldUsername = $user->username;
        if ($oldUsername === $validated['username']) {
            return back()->with('info', 'The new username is the same as your current username.');
        }

        $user->update([
            'username' => $validated['username'],
        ]);

        ActivityLog::record(
            'ADMIN_USERNAME_CHANGED',
            "Super Administrator changed username from '{$oldUsername}' to '{$validated['username']}'."
        );

        return back()->with('success', "Admin username updated to '{$validated['username']}' successfully.");
    }
}
