<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
