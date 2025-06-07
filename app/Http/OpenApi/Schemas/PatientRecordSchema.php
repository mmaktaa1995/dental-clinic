<?php

namespace App\Http\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="PatientRecordResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="patient_id", type="integer", example=1),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Initial Consultation"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         example="Patient presented with tooth pain in lower left molar"
 *     ),
 *     @OA\Property(
 *         property="treatment",
 *         type="string",
 *         nullable=true,
 *         example="Performed root canal treatment"
 *     ),
 *     @OA\Property(
 *         property="prescription",
 *         type="string",
 *         nullable=true,
 *         example="Amoxicillin 500mg, 1 tablet every 8 hours for 7 days"
 *     ),
 *     @OA\Property(
 *         property="notes",
 *         type="string",
 *         nullable=true,
 *         example="Patient to return in 2 weeks for follow-up"
 *     ),
 *     @OA\Property(
 *         property="record_date",
 *         type="string",
 *         format="date",
 *         example="2023-05-15"
 *     ),
 *     @OA\Property(
 *         property="next_visit",
 *         type="string",
 *         format="date",
 *         nullable=true,
 *         example="2023-05-29"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="patient",
 *         ref="#/components/schemas/PatientResource"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="PatientRecordRequest",
 *     type="object",
 *     required={"title", "record_date"},
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Initial Consultation"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         example="Patient presented with tooth pain in lower left molar"
 *     ),
 *     @OA\Property(
 *         property="treatment",
 *         type="string",
 *         nullable=true,
 *         example="Performed root canal treatment"
 *     ),
 *     @OA\Property(
 *         property="prescription",
 *         type="string",
 *         nullable=true,
 *         example="Amoxicillin 500mg, 1 tablet every 8 hours for 7 days"
 *     ),
 *     @OA\Property(
 *         property="notes",
 *         type="string",
 *         nullable=true,
 *         example="Patient to return in 2 weeks for follow-up"
 *     ),
 *     @OA\Property(
 *         property="record_date",
 *         type="string",
 *         format="date",
 *         example="2023-05-15"
 *     ),
 *     @OA\Property(
 *         property="next_visit",
 *         type="string",
 *         format="date",
 *         nullable=true,
 *         example="2023-05-29"
 *     )
 * )
 */
class PatientRecordSchema
{
    // This class is used for Swagger documentation only
}
