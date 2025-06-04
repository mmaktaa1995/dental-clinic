<?php

use App\Http\Controllers\PaymentsController;
use Illuminate\Support\Facades\Route;

// Payment routes
Route::post('payments', [PaymentsController::class, 'list']);
Route::post('payments/create', [PaymentsController::class, 'store']);
Route::patch('payments/{payment}', [PaymentsController::class, 'update']);
Route::delete('payments/{payment}', [PaymentsController::class, 'destroy']);
Route::post('payments/{patient?}/patients', [PaymentsController::class, 'list']);
Route::get('payments/{patient?}/patients/print', [PaymentsController::class, 'print'])->name('patients-payments.print');
