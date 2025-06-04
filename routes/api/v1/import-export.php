<?php

use App\Http\Controllers\ImportExportController;
use Illuminate\Support\Facades\Route;

// Import/Export routes with permission middleware
Route::middleware('permission:import-data')->group(function () {
    Route::post('import/patients', [ImportExportController::class, 'importPatients']);
    Route::post('import/appointments', [ImportExportController::class, 'importAppointments']);
});

Route::middleware('permission:export-data')->group(function () {
    Route::get('export/patients', [ImportExportController::class, 'exportPatients']);
    Route::get('export/appointments', [ImportExportController::class, 'exportAppointments']);
    Route::get('export/templates/{type}', [ImportExportController::class, 'downloadTemplate']);
});
