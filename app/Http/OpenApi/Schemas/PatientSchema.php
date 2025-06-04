<?php

namespace App\Http\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="PatientResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="file_number", type="string", example="PAT-1001"),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *     @OA\Property(property="address", type="string", nullable=true, example="123 Main St"),
 *     @OA\Property(property="city", type="string", nullable=true, example="New York"),
 *     @OA\Property(property="postal_code", type="string", nullable=true, example="10001"),
 *     @OA\Property(property="country", type="string", nullable=true, example="USA"),
 *     @OA\Property(property="blood_type", type="string", nullable=true, example="A+"),
 *     @OA\Property(property="allergies", type="string", nullable=true, example="Penicillin"),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Patient has a history of dental anxiety"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="files",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/PatientFileResource")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="PatientRequest",
 *     type="object",
 *     required={"first_name", "last_name", "date_of_birth"},
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="middle_name", type="string", nullable=true, example="Michael"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *     @OA\Property(property="address", type="string", nullable=true, example="123 Main St"),
 *     @OA\Property(property="city", type="string", nullable=true, example="New York"),
 *     @OA\Property(property="postal_code", type="string", nullable=true, example="10001"),
 *     @OA\Property(property="country", type="string", nullable=true, example="USA"),
 *     @OA\Property(property="blood_type", type="string", nullable=true, example="A+"),
 *     @OA\Property(property="allergies", type="string", nullable=true, example="Penicillin"),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Patient has a history of dental anxiety")
 * )
 */
class PatientSchema
{
    // This class is used for Swagger documentation only
}
