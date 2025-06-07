<?php

namespace App\Http\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="AppointmentResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="patient_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Dental Checkup"),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         example="Regular dental checkup and cleaning"
 *     ),
 *     @OA\Property(
 *         property="start_time",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-20 09:00:00"
 *     ),
 *     @OA\Property(
 *         property="end_time",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-20 10:00:00"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"scheduled", "completed", "cancelled", "no_show"},
 *         example="scheduled"
 *     ),
 *     @OA\Property(property="color", type="string", nullable=true, example="#3b82f6"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="patient",
 *         ref="#/components/schemas/PatientResource"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="AppointmentRequest",
 *     type="object",
 *     required={"patient_id", "title", "start_time", "end_time"},
 *     @OA\Property(property="patient_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Dental Checkup"),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         example="Regular dental checkup and cleaning"
 *     ),
 *     @OA\Property(
 *         property="start_time",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-20 09:00:00"
 *     ),
 *     @OA\Property(
 *         property="end_time",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-20 10:00:00"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"scheduled", "completed", "cancelled", "no_show"},
 *         example="scheduled"
 *     ),
 *     @OA\Property(property="color", type="string", nullable=true, example="#3b82f6")
 * )
 */
class AppointmentSchema
{
    // This class is used for Swagger documentation only
}
