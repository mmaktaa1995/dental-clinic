<?php

use Illuminate\Support\Facades\Route;
use App\Models\Patient;
use App\Http\Controllers\PatientsController;

Route::get('/test/patients', function () {
    $patient = Patient::first();
    return response()->json([
        'patient' => $patient,
        'all_columns' => \Schema::getColumnListing('patients')
    ]);
});

Route::get('/test/patients/search', [PatientsController::class, 'list']);
