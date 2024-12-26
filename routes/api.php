<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DebitsController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PatientFilesController;
use App\Http\Controllers\PatientRecordsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PatientsFilesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ServicesController;
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
    Route::prefix('v1')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        // New Routes
        Route::get('patients/lastFileNumber', [PatientsController::class, 'lastFileNumber'])->name('patients.last_file_number');
        Route::get('patients/list', [PatientsController::class, 'apiList'])->name('patients.api-list');

        Route::post('payments', [PaymentsController::class, 'list']);
        Route::post('payments/{payment?}', [PaymentsController::class, 'show']);
        Route::post('payments/{patient?}/patients', [PaymentsController::class, 'list']);
        Route::post('payments/{patient?}/patients/create', [PaymentsController::class, 'store']);
        Route::get('payments/{patient?}/patients/print', [PaymentsController::class, 'print'])->name('patients-payments.print');
        Route::patch('payments/{patient?}/patients/{payment}', [PaymentsController::class, 'update']);


        Route::post('patients', [PatientsController::class, 'list']);
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

    });
    // Old Routes
    Route::get('patients/dropdown', [PatientsController::class, 'dropdownData'])->name('patients.dropdown');
    Route::patch('patients/{patient}/images', [PatientsController::class, 'updateImages'])->name('patients.update_images');
    Route::patch('patients/{patient}/restore', [PatientsController::class, 'restore'])->name('patients.restore');
    Route::resource('patients-files', PatientsFilesController::class)->parameters(['patients-files' => 'payment'])->except(['create', 'edit']);
    Route::post('patients-files/{payment}/restore', [PatientsFilesController::class, 'restore'])->withTrashed()->name('payments.restore');
    Route::get('patients-files/{patient}/print', [PatientsFilesController::class, 'print'])->name('patients-files.print');

    Route::resource('visits', VisitsController::class)->except(['create', 'edit']);
    Route::resource('patients.visits', VisitsController::class)->except(['create', 'edit']);


    Route::resource('appointments', AppointmentController::class)->except(['create', 'edit']);

    Route::get('statistics', StatisticsController::class)->name('statistics');

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::post('upload', [UploadFilesController::class, 'store'])->name('upload.save');
Route::delete('upload/{folder}/{type}', [UploadFilesController::class, 'destroy'])->name('upload.delete');
Route::get('upload/{folder}/{name}/{type}', [UploadFilesController::class, 'show'])->name('upload.show');

//Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login'])->name('login');
