<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PaymentResource;
use App\Models\DeletedPatient;
use App\Models\Patient;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;

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
        $patient->load('images');
        return response()->json(PatientResource::make($patient));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\PatientRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(PatientRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            \DB::beginTransaction();
            $patient = Patient::create($request->validated());
//            if ($request->filled('amount')) {
                $visit = $patient->visits()->create($request->validated());
                Payment::create([
                    'patient_id' => $patient->id,
                    'visit_id' => $visit->id,
                    'date' => $request->get('date'),
                    'amount' => $request->get('amount'),
                    'remaining_amount' => $request->get('remaining_amount', 0)
                ]);
                if ($request->filled('services'))
                    $visit->services()->sync($request->get('services'));
//            }
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

    public function updateImages(Request $request, Patient $patient)
    {
        $images = $request->get('images', []);
        $patient->images()->delete();
        $patient->images()->createMany($images);
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

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function debits(Request $request)
    {
        $params = [
            'order_column' => $request->input('order_column', 'date'),
            'order_dir' => $request->input('order_dir', 'desc'),
            'per_page' => $request->input('per_page', 10),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
            'extra_filters' => [
                'remaining_amount' => [
                    'operation' => '>',
                    'value' => 0
                ]
            ]
        ];

        Payment::$relationsWithForSearch = ['patient', 'visit'];
        $data = Payment::getAll($params);
        return response()->json(BaseCollection::make($data, PaymentResource::class));
    }
}
