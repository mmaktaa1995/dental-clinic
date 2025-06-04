<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// User routes with permission middleware
Route::middleware('permission:view-users')->group(function () {
    Route::post('users', [UsersController::class, 'list']);
    Route::get('users/{user}', [UsersController::class, 'show']);
});

Route::post('users/create', [UsersController::class, 'store'])->middleware('permission:create-users');
Route::patch('users/{user}', [UsersController::class, 'update'])->middleware('permission:edit-users');
Route::delete('users/{user}', [UsersController::class, 'destroy'])->middleware('permission:delete-users');

// Profile routes (no specific permission required as users can view their own profile)
Route::get('profile', [UsersController::class, 'profile']);
Route::patch('profile', [UsersController::class, 'updateProfile']);
Route::post('change-password', [UsersController::class, 'changePassword']);
