<?php

/**
 * Patient related API endpoints.
 *
 * Handles all patient-related operations including CRUD, file management,
 * and record keeping.
 */

use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PatientRecordsController;
use App\Http\Controllers\PatientFilesController;
use Illuminate\Support\Facades\Route;

/**
 * @OA\Tag(
 *     name="Patients",
 *     description="API Endpoints for managing patients"
 * )
 * @OA\Tag(
 *     name="Patient Records",
 *     description="API Endpoints for managing patient medical records"
 * )
 * @OA\Tag(
 *     name="Patient Files",
 *     description="API Endpoints for managing patient files"
 * )
 */

// Patient routes
/**
 * @OA\Get(
 *     path="/api/v1/patients/lastFileNumber",
 *     tags={"Patients"},
 *     summary="Get the last used patient file number",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Last file number retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="last_file_number", type="string", example="PAT-1000")
 *         )
 *     )
 * )
 */
Route::get('patients/lastFileNumber', [PatientsController::class, 'lastFileNumber'])->name('patients.last_file_number');

/**
 * @OA\Get(
 *     path="/api/v1/patients/list",
 *     tags={"Patients"},
 *     summary="Get a list of patients for API consumption",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of patients",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/PatientResource")
 *         )
 *     )
 * )
 */
Route::get('patients/list', [PatientsController::class, 'apiList'])->name('patients.api-list');

/**
 * @OA\Post(
 *     path="/api/v1/patients",
 *     tags={"Patients"},
 *     summary="Search and filter patients",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="search", type="string", example="John"),
 *             @OA\Property(property="per_page", type="integer", example=15)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Paginated list of patients",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PatientResource")),
 *             @OA\Property(property="links"),
 *             @OA\Property(property="meta")
 *         )
 *     )
 * )
 */
Route::post('patients', [PatientsController::class, 'list']);

/**
 * @OA\Post(
 *     path="/api/v1/patients/exist",
 *     tags={"Patients"},
 *     summary="Check if a patient with given details exists",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name", "last_name", "date_of_birth"},
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Patient existence check result",
 *         @OA\JsonContent(
 *             @OA\Property(property="exists", type="boolean", example=true),
 *             @OA\Property(
 *                 property="patient",
 *                 type="object",
 *                 nullable=true,
 *                 ref="#/components/schemas/PatientResource"
 *             )
 *         )
 *     )
 * )
 */
Route::post(
    'patients/exist',
    [PatientsController::class, 'checkExisting']
);

/**
 * @OA\Post(
 *     path="/api/v1/patients/create",
 *     tags={"Patients"},
 *     summary="Create a new patient",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Patient created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
 *     ),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
Route::post('patients/create', [PatientsController::class, 'store']);

/**
 * @OA\Get(
 *     path="/api/v1/patients/{id}",
 *     tags={"Patients"},
 *     summary="Get a specific patient by ID",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Patient details",
 *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
 *     ),
 *     @OA\Response(response=404, description="Patient not found")
 * )
 */
Route::get('patients/{patient}', [PatientsController::class, 'show']);

/**
 * @OA\Patch(
 *     path="/api/v1/patients/{id}",
 *     tags={"Patients"},
 *     summary="Update a patient",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Patient updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
 *     ),
 *     @OA\Response(response=404, description="Patient not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
Route::patch('patients/{patient}', [PatientsController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/api/v1/patients/{id}",
 *     tags={"Patients"},
 *     summary="Delete a patient",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Patient deleted successfully"
 *     ),
 *     @OA\Response(response=404, description="Patient not found")
 * )
 */
Route::delete('patients/{patient}', [PatientsController::class, 'destroy']);

// Patient Files
/**
 * @OA\Patch(
 *     path="/api/v1/patients/{id}/files",
 *     tags={"Patient Files"},
 *     summary="Synchronize patient files",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"files"},
 *             @OA\Property(
 *                 property="files",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="name", type="string"),
 *                     @OA\Property(property="path", type="string")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Files synchronized successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Files synchronized successfully")
 *         )
 *     ),
 *     @OA\Response(response=404, description="Patient not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
Route::patch('patients/{patient}/files', [PatientFilesController::class, 'syncFiles'])->name('patients.sync-files');

/**
 * @OA\Post(
 *     path="/api/v1/patients/{id}/files",
 *     tags={"Patient Files"},
 *     summary="Upload files for a patient",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"files"},
 *                 @OA\Property(
 *                     property="files[]",
 *                     type="array",
 *                     @OA\Items(type="string", format="binary")
 *                 ),
 *                 @OA\Property(
 *                     property="type",
 *                     type="string",
 *                     description="Type/category of the files"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Files uploaded successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Files uploaded successfully"),
 *             @OA\Property(property="files", type="array", @OA\Items(type="object"))
 *         )
 *     ),
 *     @OA\Response(response=404, description="Patient not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
Route::post('patients/{patient}/files', [PatientFilesController::class, 'files'])->name('patients.files');

/**
 * @OA\Delete(
 *     path="/api/v1/patients/{patientId}/files/{fileId}",
 *     tags={"Patient Files"},
 *     summary="Delete a patient file",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="patientId",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\Parameter(
 *         name="fileId",
 *         in="path",
 *         required=true,
 *         description="File ID"
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="File deleted successfully"
 *     ),
 *     @OA\Response(response=404, description="File or patient not found")
 * )
 */
Route::delete(
    'patients/{patient}/files/{file}',
    [PatientFilesController::class, 'deleteFile']
)->name('patients.delete-file');

// Patient Records
/**
 * @OA\Post(
 *     path="/api/v1/patients/{id}/records",
 *     tags={"Patient Records"},
 *     summary="Get paginated list of patient records",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="per_page", type="integer", example=15),
 *             @OA\Property(property="page", type="integer", example=1),
 *             @OA\Property(property="sort_by", type="string", example="created_at"),
 *             @OA\Property(property="sort_order", type="string", example="desc")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Paginated list of patient records",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PatientRecordResource")),
 *             @OA\Property(property="links"),
 *             @OA\Property(property="meta")
 *         )
 *     ),
 *     @OA\Response(response=404, description="Patient not found")
 * )
 */
Route::post('patients/{patient}/records', [PatientRecordsController::class, 'list']);

/**
 * @OA\Post(
 *     path="/api/v1/patients/{id}/records/create",
 *     tags={"Patient Records"},
 *     summary="Create a new patient record",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/PatientRecordRequest")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Record created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/PatientRecordResource")
 *     ),
 *     @OA\Response(response=404, description="Patient not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
Route::post('patients/{patient}/records/create', [PatientRecordsController::class, 'store']);

/**
 * @OA\Patch(
 *     path="/api/v1/patients/{patientId}/records/{recordId}",
 *     tags={"Patient Records"},
 *     summary="Update a patient record",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="patientId",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\Parameter(
 *         name="recordId",
 *         in="path",
 *         required=true,
 *         description="Record ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/PatientRecordRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Record updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/PatientRecordResource")
 *     ),
 *     @OA\Response(response=404, description="Record or patient not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
Route::patch('patients/{patient}/records/{patientRecord}', [PatientRecordsController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/api/v1/patients/{patientId}/records/{recordId}",
 *     tags={"Patient Records"},
 *     summary="Delete a patient record",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="patientId",
 *         in="path",
 *         required=true,
 *         description="Patient ID"
 *     ),
 *     @OA\Parameter(
 *         name="recordId",
 *         in="path",
 *         required=true,
 *         description="Record ID"
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Record deleted successfully"
 *     ),
 *     @OA\Response(response=404, description="Record or patient not found")
 * )
 */
Route::delete('patients/{patient}/records/{patientRecord}', [PatientRecordsController::class, 'destroy']);
