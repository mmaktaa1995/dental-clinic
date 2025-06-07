<?php

namespace App\Http\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="PatientFileResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="patient_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="xray_front.jpg"),
 *     @OA\Property(property="original_name", type="string", example="xray_front.jpg"),
 *     @OA\Property(property="path", type="string", example="patients/1/xray_front.jpg"),
 *     @OA\Property(property="type", type="string", nullable=true, example="xray"),
 *     @OA\Property(property="size", type="integer", example=1024),
 *     @OA\Property(property="mime_type", type="string", example="image/jpeg"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Front view x-ray"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="PatientFileRequest",
 *     type="object",
 *     required={"files"},
 *     @OA\Property(
 *         property="files",
 *         type="array",
 *         @OA\Items(type="string", format="binary")
 *     ),
 *     @OA\Property(property="type", type="string", nullable=true, example="xray"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Front view x-ray")
 * )
 */
class PatientFileSchema
{
    // This class is used for Swagger documentation only
}
