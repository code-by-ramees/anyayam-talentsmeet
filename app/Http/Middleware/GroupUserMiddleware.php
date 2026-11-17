<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GroupUserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('group.login')->with('error', 'Please log in to access your Group Portal.');
        }

        $user = Auth::user();

        if ($user->role !== 'group_user' || ! $user->group_id) {
            return redirect()->route('group.login')->with('error', 'Access denied. Valid group account required.');
        }

        // Verify group is active
        if ($user->group && $user->group->status !== 'active') {
            Auth::logout();
            return redirect()->route('group.login')->with('error', 'Your group account is currently inactive. Please contact the administrator.');
        }

        return $next($request);
    }
}
