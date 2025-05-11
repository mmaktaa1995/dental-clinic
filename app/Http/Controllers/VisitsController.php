<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\VisitResource;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Patient|null $patient
     * @return JsonResponse
     */
    public function index(Request $request, ?Patient $patient)
    {
        $params = [
            'order_column' => $request->input('order_column', 'date'),
            'order_dir' => $request->input('order_dir', 'desc'),
            'per_page' => $request->input('per_page', 10),
            'date' => $request->input('date', null),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
        ];
        $totalIncome = 0;

        if ($patient->exists) {
            $params['extra_filters'] = [
                'patient_id' => [
                    'operation' => '=',
                    'value' => $patient->id
                ]
            ];
        }

        if ($request->filled('patient_id')) {
            $params['extra_filters'] = [
                'patient_id' => [
                    'operation' => '=',
                    'value' => $request->get('patient_id')
                ]
            ];

            $totalIncome = Payment::query()
                ->when($params['fromDate'] && !$params['toDate'], function ($query) use ($params) {
                    $query->whereDate('date', '>=', $params['fromDate']);
                })
                ->when($params['toDate'] && !$params['fromDate'], function ($query) use ($params) {
                    $query->whereDate('date', '<=', $params['toDate']);
                })
                ->when($params['toDate'] && $params['fromDate'], function ($query) use ($params) {
                    $query->whereBetween('date', [$params['fromDate'], $params['toDate']]);
                })
                ->when($params['date'] ?? false, function ($query) use ($params) {
                    $query->whereDate('date', $params['date']);
                })
                ->select([DB::raw("SUM(amount) as value")])
                ->value('value');
        }
        $data = Visit::getAll($params);

        return response()->json(BaseCollection::make($data, VisitResource::class, "entries", ['item' => $patient->exists ? $patient : null, 'totalValues' => $totalIncome]));
    }

    /**
     * @param Visit $visit
     *
     * @return JsonResponse
     */
    public function show(Visit $visit): JsonResponse
    {
        return response()->json(VisitResource::make($visit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param VisitRequest $request
     *
     * @return JsonResponse
     */
    public function store(VisitRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $visit = Visit::create($request->validated());
            if ($request->filled('services'))
                $visit->services()->sync($request->get('services'));
            $visit->payment()->create($request->validated());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
        return response()->json(['message' => __('app.success')]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param VisitRequest $request
     * @param Visit $visit
     *
     * @return JsonResponse
     */
    public function update(VisitRequest $request, Visit $visit): JsonResponse
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
     * @param Visit $visit
     *
     * @return JsonResponse
     */
    public function destroy(Visit $visit): JsonResponse
    {
        try {
            $visit->delete();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
        return response()->json(['message' => __('app.success')]);
    }

}
