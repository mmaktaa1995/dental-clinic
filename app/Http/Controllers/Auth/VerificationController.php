<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        // Check if the verification link is valid
        if ($request->route('id') != $request->user()->getKey() || 
            ! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Invalid verification link or token.',
                ], 403);
            }
            
            return redirect()->to('/login?verified=0&error=invalid_token');
        }

        // Check if the email is already verified
        if ($request->user()->hasVerifiedEmail()) {
            // For API requests, return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Email already verified.',
                ], 200);
            }
            
            // For web requests, redirect to login with a success message
            return redirect()->to('/login?verified=1');
        }

        // Mark the email as verified
        if ($request->user()->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($request->user()));
        }

        // For API requests, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Email verified successfully.',
            ], 200);
        }
        
        // For web requests, redirect to login with a success message
        return redirect()->to('/login?verified=1');
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
            ], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Email verification link sent to your email.',
        ]);
    }
}
