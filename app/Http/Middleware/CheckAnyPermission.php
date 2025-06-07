<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAnyPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string                   ...$permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!Auth::user()->hasAnyPermission($permissions)) {
            return response()->json([
                'message' => 'Unauthorized. You do not have any of the required permissions.'
            ], 403);
        }

        return $next($request);
    }
}
