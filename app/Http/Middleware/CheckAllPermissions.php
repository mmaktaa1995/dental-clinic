<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAllPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!Auth::user()->hasAllPermissions($permissions)) {
            return response()->json([
                'message' => 'Unauthorized. You do not have all of the required permissions.'
            ], 403);
        }

        return $next($request);
    }
}
