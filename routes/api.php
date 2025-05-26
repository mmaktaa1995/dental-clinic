<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DebitsController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientFilesController;
use App\Http\Controllers\PatientRecordsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\UploadFilesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\RefreshTokenController;
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
    // Public routes with rate limiting
    Route::middleware('throttle:60,1')->group(function () {
        Route::post('login', [LoginController::class, 'login'])->name('login');
        Route::post('register', [RegisterController::class, 'register'])->name('register');
        Route::post('upload', [UploadFilesController::class, 'store'])->name('upload.save');
        Route::get('upload/{folder}/{name}/{type}', [UploadFilesController::class, 'show'])->name('upload.show');

        // Token refresh route (public but requires valid refresh token)
        Route::post('refresh-token', [RefreshTokenController::class, 'refresh'])
            ->middleware('auth:sanctum')
            ->name('token.refresh');
    });

    // Email verification routes
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/resend', [VerificationController::class, 'resend'])
        ->middleware(['auth:sanctum', 'throttle:6,1'])
        ->name('verification.resend');

    // More strict rate limiting for file deletion
    Route::middleware('throttle:10,1')->group(function () {
        Route::delete('upload/{folder}/{type}', [UploadFilesController::class, 'destroy'])->name('upload.delete');
    });

    // Authenticated routes with rate limiting
    Route::middleware(['auth:sanctum', 'throttle:120,1'])->group(function () {
        // Routes that don't require email verification
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('user', [LoginController::class, 'user']);

        // Routes that require email verification
        Route::middleware(['api.verified'])->group(function () {
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

            // Users management routes
            Route::post('users', [UsersController::class, 'list'])->middleware('permission:view-users');
            Route::post('users/create', [UsersController::class, 'store'])->middleware('permission:create-users');
            Route::get('users/{user}', [UsersController::class, 'show'])->middleware('permission:view-users');
            Route::patch('users/{user}', [UsersController::class, 'update'])->middleware('permission:edit-users');
            Route::delete('users/{user}', [UsersController::class, 'destroy'])->middleware('permission:delete-users');

            // Roles management routes
            // For dropdown apis
            Route::get('roles/list', [RolesController::class, 'listRoles'])->middleware('permission:view-roles');
            Route::post('roles', [RolesController::class, 'list'])->middleware('permission:view-roles');
            Route::post('roles/create', [RolesController::class, 'store'])->middleware('permission:create-roles');
            Route::get('roles/{role}', [RolesController::class, 'show'])->middleware('permission:view-roles');
            Route::patch('roles/{role}', [RolesController::class, 'update'])->middleware('permission:edit-roles');
            Route::delete('roles/{role}', [RolesController::class, 'destroy'])->middleware('permission:delete-roles');
            Route::get('permissions', [RolesController::class, 'permissions'])->middleware('permission:view-roles');

            // Import/Export routes
            Route::get('export/patients', [ImportExportController::class, 'exportPatients'])->middleware('permission:export-data');
            Route::post('import/patients', [ImportExportController::class, 'importPatients'])->middleware('permission:import-data');

            Route::get('export/services', [ImportExportController::class, 'exportServices'])->middleware('permission:export-data');
            Route::post('import/services', [ImportExportController::class, 'importServices'])->middleware('permission:import-data');

            Route::get('export/expenses', [ImportExportController::class, 'exportExpenses'])->middleware('permission:export-data');
            Route::post('import/expenses', [ImportExportController::class, 'importExpenses'])->middleware('permission:import-data');

            Route::get('export/users', [ImportExportController::class, 'exportUsers'])->middleware('permission:export-data');
            Route::post('import/users', [ImportExportController::class, 'importUsers'])->middleware('permission:import-data');

            Route::get('export/appointments', [ImportExportController::class, 'exportAppointments'])->middleware('permission:export-data');
        }); // End of api.verified middleware group
    });
});
