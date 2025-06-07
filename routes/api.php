<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API Version 1 Routes
Route::prefix('v1')->group(function () {
    // Include all version 1 routes
    require __DIR__ . '/api/v1/base.php';
});

// Default API version (v1) - This allows for backward compatibility
Route::prefix('v1')->group(function () {
    require __DIR__ . '/api/v1/base.php';
})->withoutMiddleware('api');
