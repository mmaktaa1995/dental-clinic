<?php

use App\Http\Controllers\DebitsController;
use Illuminate\Support\Facades\Route;

// Debit routes
Route::post('debits', [DebitsController::class, 'debits'])->name('debits');
Route::post('debits/{patient?}/patients', [DebitsController::class, 'debits'])->name('patients.debits');
