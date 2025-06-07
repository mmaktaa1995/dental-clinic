<?php

/**
 * Appointments API endpoints.
 *
 * This file contains all routes related to appointment management,
 * including scheduling, rescheduling, and checking availability.
 */

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

/**
 * @OA\Tag(
 *     name="Appointments",
 *     description="API Endpoints for managing appointments"
 * )
 */

// Appointment routes
/**
 * @OA\Get(
 *     path="/api/v1/appointments",
 *     tags={"Appointments"},
 *     summary="Get paginated list of appointments",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="start_date",
 *         in="query",
 *         description="Start date filter (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="end_date",
 *         in="query",
 *         description="End date filter (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Filter by status (scheduled, completed, cancelled, no_show)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="patient_id",
 *         in="query",
 *         description="Filter by patient ID",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of appointments",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AppointmentResource")),
 *             @OA\Property(property="links"),
 *             @OA\Property(property="meta")
 *         )
 *     )
 * )
 */
Route::get('appointments', [AppointmentController::class, 'index']);

/**
 * @OA\Post(
 *     path="/api/v1/appointments",
 *     tags={"Appointments"},
 *     summary="Create a new appointment",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/AppointmentRequest")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Appointment created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/AppointmentResource")
 *     ),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
Route::post('appointments', [AppointmentController::class, 'store']);

/**
 * @OA\Get(
 *     path="/api/v1/appointments/{id}",
 *     tags={"Appointments"},
 *     summary="Get a specific appointment by ID",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Appointment ID"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment details",
 *         @OA\JsonContent(ref="#/components/schemas/AppointmentResource")
 *     ),
 *     @OA\Response(response=404, description="Appointment not found")
 * )
 */
Route::get('appointments/{appointment}', [AppointmentController::class, 'show']);

/**
 * @OA\Patch(
 *     path="/api/v1/appointments/{id}",
 *     tags={"Appointments"},
 *     summary="Update an appointment",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Appointment ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/AppointmentRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/AppointmentResource")
 *     ),
 *     @OA\Response(response=404, description="Appointment not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
Route::patch('appointments/{appointment}', [AppointmentController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/api/v1/appointments/{id}",
 *     tags={"Appointments"},
 *     summary="Delete an appointment",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Appointment ID"
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Appointment deleted successfully"
 *     ),
 *     @OA\Response(response=404, description="Appointment not found")
 * )
 */
Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy']);

/**
 * @OA\Post(
 *     path="/api/v1/appointments/check-availability",
 *     tags={"Appointments"},
 *     summary="Check appointment availability",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"start_time", "end_time"},
 *             @OA\Property(property="start_time", type="string", format="date-time", example="2023-05-20 09:00:00"),
 *             @OA\Property(property="end_time", type="string", format="date-time", example="2023-05-20 10:00:00"),
 *             @OA\Property(property="exclude_appointment_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Availability check result",
 *         @OA\JsonContent(
 *             @OA\Property(property="available", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Time slot is available")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
Route::post('appointments/check-availability', [AppointmentController::class, 'checkAvailability']);
