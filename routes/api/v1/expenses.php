<?php

use App\Http\Controllers\ExpensesController;
use Illuminate\Support\Facades\Route;

// Expense routes
Route::post('expenses', [ExpensesController::class, 'list'])->middleware("permission:view-expenses");
Route::post('expenses/create', [ExpensesController::class, 'store'])->middleware("permission:create-expenses");
Route::get('expenses/{expense}', [ExpensesController::class, 'show'])->middleware("permission:view-expenses");
Route::patch('expenses/{expense}', [ExpensesController::class, 'update'])->middleware("permission:edit-expenses");
Route::delete('expenses/{expense}', [ExpensesController::class, 'destroy'])->middleware("permission:delete-expenses");

Route::get('expenses-categories', [ExpensesController::class, 'categories']);
