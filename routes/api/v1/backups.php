<?php

use App\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;

// Backup routes with permission middleware
Route::middleware('permission:manage-backups')->group(function () {
    Route::get('backups', [BackupController::class, 'index']);
    Route::post('backups/create', [BackupController::class, 'create']);
    Route::delete('backups/{filename}', [BackupController::class, 'destroy']);
    Route::post('backups/{filename}/restore', [BackupController::class, 'restore']);
    Route::get('backups/{filename}/download', [BackupController::class, 'download']);
});
