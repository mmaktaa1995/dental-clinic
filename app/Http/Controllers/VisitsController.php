<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\VisitResource;
use App\Models\Patient;
use App\Models\Visit;
use Exception;
use Illuminate\Http\Request;

class VisitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ?Patient $patient)
    {
        $params = [
            'order_column' => $request->input('order_column', 'date'),
            'order_dir' => $request->input('order_dir', 'desc'),
            'per_page' => $request->input('per_page', 10),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
        ];

        if ($patient->exists) {
            $params['extra_filters'] = [
                'patient_id' => [
                    'operation' => '=',
                    'value' => $patient->id
                ]
            ];
        }
        $data = Visit::getAll($params);
        $data = collect(BaseCollection::make($data, VisitResource::class))->merge(['item' => $patient->exists ? $patient : null]);
        return response()->json($data);
    }

    /**
     * @param \App\Models\Visit $visit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Visit $visit): \Illuminate\Http\JsonResponse
    {
        return response()->json(VisitResource::make($visit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\VisitRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(VisitRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            \DB::beginTransaction();
            $visit = Visit::create($request->validated());
            if ($request->filled('services'))
                $visit->services()->sync($request->get('services'));
            $visit->payment()->create($request->validated());
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
     * @param \App\Http\Requests\VisitRequest $request
     * @param \App\Models\Visit $visit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(VisitRequest $request, Visit $visit): \Illuminate\Http\JsonResponse
    {
        $visit->update($request->validated());
        if ($request->filled('services'))
            $visit->services()->sync($request->get('services'));
        $visit->payment()->update(['amount' => $request->get('amount'), 'patient_id' => $request->get('patient_id')]);
        return response()->json(['message' => __('app.success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Visit $visit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Visit $visit): \Illuminate\Http\JsonResponse
    {
        try {
            $visit->delete();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
        return response()->json(['message' => __('app.success')]);
    }

}
