<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if user is not authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        $user = $request->user();
        $now = Carbon::now();
        $lastActivity = Session::get('last_activity');
        $idleTimeout = (int) Config::get('session.idle_timeout', 30) * 60; // Convert to seconds
        $sessionLifetime = (int) Config::get('session.lifetime', 120) * 60; // Convert to seconds

        // Initialize session data if not set
        if (!$lastActivity) {
            Session::put('last_activity', $now->timestamp);
            Session::put('session_started_at', $now->timestamp);
            return $next($request);
        }

        $lastActivityTime = Carbon::createFromTimestamp($lastActivity);
        $sessionStartTime = Carbon::createFromTimestamp(Session::get('session_started_at', $now->timestamp));

        // Check for idle timeout (30 minutes of inactivity)
        if ($now->diffInSeconds($lastActivityTime) > $idleTimeout) {
            $this->logoutAndClearSession($request);

            return $request->expectsJson()
                ? response()->json(['message' => 'Your session has expired due to inactivity.'], 401)
                : redirect()->route('login')->with('error', 'Your session has expired due to inactivity.');
        }

        // Check for absolute session lifetime (2 hours from login)
        if ($now->diffInSeconds($sessionStartTime) > $sessionLifetime) {
            $this->logoutAndClearSession($request);

            return $request->expectsJson()
                ? response()->json(['message' => 'Your session has expired. Please log in again.'], 401)
                : redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
        }

        // Update last activity time
        Session::put('last_activity', $now->timestamp);

        return $next($request);
    }

    /**
     * Logout the user and clear the session.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function logoutAndClearSession($request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
