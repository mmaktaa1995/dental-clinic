<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\AppointmentSearchRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;
use App\Services\Search\AppointmentSearch;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentService $appointmentService)
    {
    }
    public function index(AppointmentSearchRequest $request): JsonResponse
    {
        $appointmentSearch = new AppointmentSearch($request);
        $appointments = $appointmentSearch->getEntries();

        return response()->json(AppointmentResource::collection($appointments));
    }

    public function store(AppointmentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        
        $appointment = $this->appointmentService->createAppointment($data);

        return response()->json([
            'message' => __('app.appointment_created_successfully'),
            'data' => AppointmentResource::make($appointment)
        ], 201);
    }

    public function update(AppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $this->appointmentService->updateAppointment($appointment, $request->validated());

        return response()->json([
            'message' => __('app.appointment_updated_successfully'),
            'data' => AppointmentResource::make($appointment->fresh())
        ], 200);
    }


    public function destroy(Appointment $appointment)
    {
        try {
            $this->appointmentService->deleteAppointment($appointment);
            return response()->json(['message' => __('app.success')]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], $exception->getCode() ?: 500);
        }
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('patient');
        return response()->json(AppointmentResource::make($appointment));
    }
}
