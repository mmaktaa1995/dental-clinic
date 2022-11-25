<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\PatientResource;
use App\Models\DeletedPatient;
use App\Models\Patient;
use App\Models\PatientImage;
use Exception;
use Illuminate\Http\Request;
use Storage;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $params = [
            'order_column' => $request->input('order_column', 'created_at'),
            'order_dir' => $request->input('order_dir', 'desc'),
            'per_page' => $request->input('per_page', 10),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
        ];
        $data = Patient::getAll($params);
        if ($request->filled('deleted')) {
            $data = DeletedPatient::getAll($params);
        }
        return response()->json(BaseCollection::make($data, PatientResource::class), 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownData(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(Patient::all()->pluck('name', 'id'));
    }

    /**
     * @param \App\Models\Patient $patient
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Patient $patient): \Illuminate\Http\JsonResponse
    {
        return response()->json(PatientResource::make($patient), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\PatientRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PatientRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            \DB::beginTransaction();
            $patient = Patient::create($request->validated());
            if ($request->filled('amount')) {
                $visit = $patient->visits()->create($request->validated());
                if ($request->filled('services'))
                    $visit->services()->sync($request->get('services'));
            }
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw new Exception($exception->getMessage());
        }
        return response()->json(['message' => __('app.success')]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\PatientRequest $request
     * @param \App\Models\Patient $patient
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PatientRequest $request, Patient $patient): \Illuminate\Http\JsonResponse
    {
        $patient->update($request->validated());
        return response()->json(['message' => __('app.success')], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Patient $patient
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Patient $patient)
    {
        try {
            $patient->delete();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
        return response()->json(['message' => __('app.success')]);
    }
}