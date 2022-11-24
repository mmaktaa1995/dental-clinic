<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $appointments = Appointment::with('patient')
            ->whereYear('date', $request->get('year', date('Y')))
            ->whereMonth('date', $request->get('month', date('m')))
            ->get();
        return response()->json(AppointmentResource::collection($appointments));
    }

    /**
     * @param \App\Http\Requests\AppointmentRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AppointmentRequest $request): JsonResponse
    {
        $appointments = Appointment::whereDate('date', '>=', now())->get()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m-d H:i');
        });
        if (in_array(date('Y-m-d H:i', strtotime($request->get('date'))), array_keys($appointments->toArray()))) {
            return response()->json(['message' => 'الرجاء إدخال موعد أخر لا يتضارب مع المواعيد الأخرى وشكراً.'], 422);
        }
        Appointment::create($request->validated());
        session()->flash('msg', "تم إضافة الموعد بنجاح.");

        return response()->json(['message' => "تم إضافة الموعد بنجاح."], 201);
    }
}
