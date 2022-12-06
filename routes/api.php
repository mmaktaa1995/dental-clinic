<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PatientsFilesController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UploadFilesController;
use App\Http\Controllers\VisitsController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::get('patients/dropdown', [PatientsController::class, 'dropdownData'])->name('patients.dropdown');
    Route::get('patients/debits', [PatientsController::class, 'debits'])->name('patients.debits');
    Route::resource('patients', PatientsController::class)->except(['create', 'edit']);
    Route::resource('patients-files', PatientsFilesController::class)->parameters(['patients-files' => 'payment'])->except(['create', 'edit']);
    Route::get('patients-files/{patient_id}/print', [PatientsFilesController::class, 'print'])->name('patients-files.print');
    Route::resource('visits', VisitsController::class)->except(['create', 'edit']);
    Route::resource('expenses', ExpensesController::class)->except(['create', 'edit']);
    Route::resource('patients.visits', VisitsController::class)->except(['create', 'edit']);
    Route::resource('appointments', AppointmentController::class)->except(['create', 'edit']);
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('statistics', StatisticsController::class);
});

Route::post('upload', [UploadFilesController::class, 'store'])->name('upload.save');
Route::delete('upload/{folder}/{name}/{type}', [UploadFilesController::class, 'destroy'])->name('upload.delete');
Route::get('upload/{folder}/{name}/{type}', [UploadFilesController::class, 'show'])->name('upload.show');

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
