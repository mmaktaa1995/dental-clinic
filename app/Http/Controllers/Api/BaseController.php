<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

/**
 * Base controller for API endpoints
 */
#[OA\Tag(name: "patients", description: "Patient management")]
#[OA\Tag(name: "appointments", description: "Appointment management")]
#[OA\Tag(name: "services", description: "Dental services management")]
#[OA\Tag(name: "payments", description: "Payment processing")]
#[OA\Tag(name: "statistics", description: "System statistics")]
class BaseController extends Controller
{
    /**
     * Success response method.
     *
     * @param mixed   $data
     * @param string  $message
     * @param integer $code
     * @return JsonResponse
     */
    protected function sendResponse($data, string $message = '', int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $data,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }

    /**
     * Error response method.
     *
     * @param string  $error
     * @param array   $errorMessages
     * @param integer $code
     * @return JsonResponse
     */
    protected function sendError(string $error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
