<?php

use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;

// Role and permission routes with permission middleware
Route::middleware('permission:view-roles')->group(function () {
    Route::post('roles', [RolesController::class, 'list']);
    Route::get('roles/list', [RolesController::class, 'listRoles']);
    Route::get('roles/{role}', [RolesController::class, 'show']);
    Route::get('permissions', [RolesController::class, 'permissions']);
});

Route::post('roles/create', [RolesController::class, 'store'])->middleware('permission:create-roles');
Route::patch('roles/{role}', [RolesController::class, 'update'])->middleware('permission:edit-roles');
Route::delete('roles/{role}', [RolesController::class, 'destroy'])->middleware('permission:delete-roles');
