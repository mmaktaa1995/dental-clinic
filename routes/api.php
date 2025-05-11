<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DebitsController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientFilesController;
use App\Http\Controllers\PatientRecordsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UploadFilesController;
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

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('upload', [UploadFilesController::class, 'store'])->name('upload.save');
    Route::delete('upload/{folder}/{type}', [UploadFilesController::class, 'destroy'])->name('upload.delete');
    Route::get('upload/{folder}/{name}/{type}', [UploadFilesController::class, 'show'])->name('upload.show');


    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('user', [LoginController::class, 'user']);
        Route::get('currencies/exchange-rate', [HomeController::class, 'getUsdExchangeRate']);
        Route::get('teeth', [HomeController::class, 'teeth']);

        // New Routes
        Route::get('patients/lastFileNumber', [PatientsController::class, 'lastFileNumber'])->name('patients.last_file_number');
        Route::get('patients/list', [PatientsController::class, 'apiList'])->name('patients.api-list');

        Route::post('payments', [PaymentsController::class, 'list']);
        Route::post('payments/create', [PaymentsController::class, 'store']);
        Route::patch('payments/{payment}', [PaymentsController::class, 'update']);
        Route::delete('payments/{payment}', [PaymentsController::class, 'destroy']);
        Route::post('payments/{patient?}/patients', [PaymentsController::class, 'list']);
        Route::get('payments/{patient?}/patients/print', [PaymentsController::class, 'print'])->name('patients-payments.print');


        Route::post('patients', [PatientsController::class, 'list']);
        Route::post('patients/exist', [PatientsController::class, 'checkExisting']);
        Route::post('patients/create', [PatientsController::class, 'store']);
        Route::get('patients/{patient}', [PatientsController::class, 'show']);
        Route::patch('patients/{patient}', [PatientsController::class, 'update']);
        Route::delete('patients/{patient}', [PatientsController::class, 'destroy']);

        Route::post('patients/{patient}/records', [PatientRecordsController::class, 'list']);
        Route::post('patients/{patient}/records/create', [PatientRecordsController::class, 'store']);
        Route::patch('patients/{patient}/records/{patientRecord}', [PatientRecordsController::class, 'update']);
        Route::delete('patients/{patient}/records/{patientRecord}', [PatientRecordsController::class, 'destroy']);


        Route::patch('patients/{patient}/files', [PatientFilesController::class, 'syncFiles'])->name('patients.sync-files');
        Route::post('patients/{patient}/files', [PatientFilesController::class, 'files'])->name('patients.files');
        Route::delete('patients/{patient}/files/{file}', [PatientFilesController::class, 'deleteFile'])->name('patients.delete-file');

        Route::post('patients/{patient}/visits', [PatientsController::class, 'visits'])->name('patients.visits');

        Route::post('debits/', [DebitsController::class, 'debits'])->name('debits');
        Route::post('debits/{patient?}/patients', [DebitsController::class, 'debits'])->name('patients.debits');


        Route::post('services', [ServicesController::class, 'list']);
        Route::get('services/{service}', [ServicesController::class, 'show']);
        Route::post('services/create', [ServicesController::class, 'store']);
        Route::patch('services/{service}', [ServicesController::class, 'update']);
        Route::delete('services/{service}', [ServicesController::class, 'destroy']);

        Route::post('expenses', [ExpensesController::class, 'list']);
        Route::get('expenses/{expense}', [ExpensesController::class, 'show']);
        Route::post('expenses/create', [ExpensesController::class, 'store']);
        Route::patch('expenses/{expense}', [ExpensesController::class, 'update']);
        Route::delete('expenses/{expense}', [ExpensesController::class, 'destroy']);

        Route::get('statistics', StatisticsController::class)->name('statistics');

        Route::resource('appointments', AppointmentController::class)->except(['create', 'edit']);
    });
});
