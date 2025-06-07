<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class RefreshTokenController extends Controller
{
    /**
     * Refresh the authenticated user's API token.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get the current token from the request
        $currentToken = $request->bearerToken();

        if (!$currentToken) {
            return response()->json([
                'message' => 'No token provided'
            ], 401);
        }

        // Find the token in the database
        $token = PersonalAccessToken::findToken($currentToken);

        if (!$token) {
            return response()->json([
                'message' => 'Invalid token'
            ], 401);
        }

        // Check if token is expired
        if ($token->created_at->diffInMinutes(now()) > config('sanctum.refresh_expiration', 60 * 24 * 7)) {
            // Delete the expired token
            $token->delete();

            return response()->json([
                'message' => 'Refresh token expired. Please log in again.'
            ], 401);
        }

        // Revoke the current token
        $token->delete();

        // Create a new token
        $newToken = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $newToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration') * 60 // in seconds
        ]);
    }
}
