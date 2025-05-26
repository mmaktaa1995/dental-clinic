<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\AppointmentSearchRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\Search\AppointmentSearch;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    public function index(AppointmentSearchRequest $request): JsonResponse
    {
        $appointmentSearch = new AppointmentSearch($request);
        $appointments = $appointmentSearch->getEntries();

        return response()->json(AppointmentResource::collection($appointments));
    }

    public function store(AppointmentRequest $request): JsonResponse
    {
        $appointments = Appointment::where('user_id', auth()->id())
            ->whereDate('date', '>=', now())
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d H:i');
            });

        $date = $request->date('date')->format('Y-m-d H:i');
        if (in_array($date, $appointments->keys()->toArray())) {
            return response()->json(['message' => __('app.appointments_conflict')], 422);
        }
        Appointment::create($request->validated());

        return response()->json(['message' => __('app.appointment_created_successfully')], 201);
    }

    public function update(AppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $appointments = Appointment::where('user_id', auth()->id())
            ->whereDate('date', '>=', now())
            ->where('id', '!=', $appointment->id)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d H:i');
            });

        $date = $request->date('date')->format('Y-m-d H:i');
        if (in_array($date, $appointments->keys()->toArray())) {
            return response()->json(['message' => __('app.appointments_conflict')], 422);
        }
        $appointment->update($request->validated());

        return response()->json(['message' => __('app.appointment_updated_successfully')], 201);
    }

    public function destroy(Appointment $appointment)
    {
        try {
            $appointment->delete();
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
        return response()->json(['message' => __('app.success')]);
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('patient');
        return response()->json(AppointmentResource::make($appointment));
    }
}
