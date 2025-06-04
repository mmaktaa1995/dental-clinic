<?php

namespace App\Http\OpenApi\Requests;

/**
 * @OA\Schema(
 *     schema="UserRequest",
 *     type="object",
 *     required={"name", "email", "username", "password"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="username", type="string", example="johndoe"),
 *     @OA\Property(property="password", type="string", format="password", example="password"),
 *     @OA\Property(
 *         property="roles",
 *         type="array",
 *         @OA\Items(type="string", example="admin")
 *     )
 * )
 */
class UserRequestSchema
{
    // This class is used for Swagger documentation only
}
