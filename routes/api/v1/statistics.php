<?php

use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Statistics and home routes
Route::get('statistics', StatisticsController::class);
Route::get('statistics/overview', [StatisticsController::class, 'overview']);
Route::get('statistics/patient-growth', [StatisticsController::class, 'patientGrowth']);
Route::get('statistics/revenue', [StatisticsController::class, 'revenue']);
Route::get('statistics/services', [StatisticsController::class, 'services']);
Route::get('statistics/expenses', [StatisticsController::class, 'expenses']);
Route::get('statistics/appointments', [StatisticsController::class, 'appointments']);
