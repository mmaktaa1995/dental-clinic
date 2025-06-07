<?php

/**
 * Base API routes file.
 *
 * This file contains the base API routes including authentication,
 * file uploads, and other core API endpoints.
 */

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadFilesController;
use App\Http\Controllers\Auth\RefreshTokenController;
use Illuminate\Support\Facades\Route;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 * @OA\Tag(
 *     name="Files",
 *     description="API Endpoints for file uploads and management"
 * )
 * @OA\Tag(
 *     name="Email",
 *     description="API Endpoints for email verification"
 * )
 */

// Public routes with authentication rate limiting
Route::middleware('throttle:auth')->group(function () {
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"Authentication"},
     *     summary="Authenticate user and create token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|abcdef123456")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    // Authentication endpoints with stricter rate limiting
    Route::post('login', [LoginController::class, 'login'])
        ->middleware('throttle:auth')
        ->name('login');

    Route::post('register', [RegisterController::class, 'register'])
        ->middleware('throttle:auth')
        ->name('register');
});

// File upload routes with upload-specific rate limiting
Route::middleware('throttle:uploads')->group(function () {
    Route::post('upload', [UploadFilesController::class, 'store'])
        ->name('upload.save');

    Route::get('upload/{folder}/{name}/{type}', [UploadFilesController::class, 'show'])
        ->name('upload.show');

    /**
     * @OA\Post(
     *     path="/api/v1/refresh-token",
     *     tags={"Authentication"},
     *     summary="Refresh authentication token",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="new_token_here")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    Route::post('refresh-token', [RefreshTokenController::class, 'refresh'])
        ->middleware('auth:sanctum')
        ->name('token.refresh');
});

/**
 * @OA\Get(
 *     path="/api/v1/email/verify/{id}/{hash}",
 *     tags={"Email"},
 *     summary="Verify email address",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="hash",
 *         in="path",
 *         required=true,
 *         description="Verification hash",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Email verified successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Email verified successfully")
 *         )
 *     ),
 *     @OA\Response(response=403, description="Invalid verification link")
 * )
 */
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:auth'])
    ->name('verification.verify');

/**
 * @OA\Post(
 *     path="/api/v1/email/resend",
 *     tags={"Email"},
 *     summary="Resend email verification link",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Verification link sent",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Verification link sent to your email")
 *         )
 *     ),
 *     @OA\Response(response=429, description="Too many requests")
 * )
 */
Route::post('email/resend', [VerificationController::class, 'resend'])
    ->middleware(['auth:sanctum', 'throttle:auth'])
    ->name('verification.resend');

// File deletion with upload rate limiting
Route::middleware('throttle:uploads')->group(function () {
    /**
     * @OA\Delete(
     *     path="/api/v1/upload/{folder}/{type}",
     *     tags={"Files"},
     *     summary="Delete an uploaded file",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="folder",
     *         in="path",
     *         required=true,
     *         description="Folder name where the file is stored"
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         required=true,
     *         description="File type identifier"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="File not found")
     * )
     */
    Route::delete('upload/{folder}/{type}', [UploadFilesController::class, 'destroy'])->name('upload.delete');
});

/**
 * @OA\Tag(
 *     name="User",
 *     description="API Endpoints for user management"
 * )
 */

// Authenticated routes with API rate limiting
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     tags={"Authentication"},
     *     summary="Logout user (revoke token)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     )
     * )
     */
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     tags={"User"},
     *     summary="Get authenticated user details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    Route::get('user', [LoginController::class, 'user']);

    // Routes that require email verification
    Route::middleware(['api.verified'])->group(function () {
        // Include all versioned route files
        require __DIR__ . '/patients.php';
        require __DIR__ . '/appointments.php';
        require __DIR__ . '/services.php';
        require __DIR__ . '/users.php';
        require __DIR__ . '/roles.php';
        require __DIR__ . '/payments.php';
        require __DIR__ . '/expenses.php';
        require __DIR__ . '/debits.php';
        require __DIR__ . '/statistics.php';
        require __DIR__ . '/backups.php';
        require __DIR__ . '/import-export.php';

        // Home and utility routes
        Route::get('currencies/exchange-rate', [HomeController::class, 'getUsdExchangeRate']);
        Route::get('teeth', [HomeController::class, 'teeth']);
    });
});
