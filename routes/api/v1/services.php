<?php

use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;

// Service routes
Route::post('services', [ServicesController::class, 'list'])->middleware("permission:view-services");
Route::post('services/create', [ServicesController::class, 'store'])->middleware("permission:create-services");
Route::get('services/{service}', [ServicesController::class, 'show'])->middleware("permission:view-services");
Route::patch('services/{service}', [ServicesController::class, 'update'])->middleware("permission:edit-services");
Route::delete('services/{service}', [ServicesController::class, 'destroy'])->middleware("permission:delete-services");
Route::get('services-list', [ServicesController::class, 'list'])->middleware("permission:view-services");
