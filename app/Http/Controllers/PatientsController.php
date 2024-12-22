<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PaymentResource;
use App\Models\DeletedPatient;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use App\Services\PatientService;
use App\Services\Search\Base\SearchRequest;
use App\Services\Search\PatientSearch;
use Exception;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    public function list(PatientSearchRequest $request)
    {
        $patientSearch = new PatientSearch($request);

        return response()->json(BaseCollection::make($patientSearch->getEntries(), PatientResource::class));
    }

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
        return response()->json(BaseCollection::make($data, PatientResource::class));
    }

    public function dropdownData(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(Patient::all()->pluck('name', 'id'));
    }

    public function lastFileNumber(PatientService $patientService): \Illuminate\Http\JsonResponse
    {
        return response()->json(['last_file_number' => $patientService->getLastFileNumber()]);
    }

    public function show(Patient $patient): \Illuminate\Http\JsonResponse
    {
        $patient->load('images');
        return response()->json(PatientResource::make($patient));
    }

    public function store(PatientRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            \DB::beginTransaction();
            $patient = Patient::create($request->validated());
            if ($request->filled('amount') && $request->filled('date')) {
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
            }
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw new Exception($exception->getMessage());
        }
        return response()->json(['message' => __('app.success'), 'id' => $patient->id]);
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
        return response()->json(['message' => __('app.success')]);
    }

    public function updateImages(Request $request, Patient $patient)
    {
        $images = $request->get('images', []);
        $patient->images()->delete();
        $patient->images()->createMany($images);
        return response()->json(['message' => __('app.success')]);
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
            \Schema::disableForeignKeyConstraints();
            $patient->delete();
            \Schema::enableForeignKeyConstraints();
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

    /**
     * restore the specified resource.
     *
     * @psalm-param \App\Models\DeletedPatient $patient
     *
     * @psalm-return  \Illuminate\Http\JsonResponse
     */
    public function restore(DeletedPatient $patient): \Illuminate\Http\JsonResponse
    {
        try {
            if ($patient->visits()->count()) {
                $patient->visits()->restore();
            }
            if ($patient->payments()->count()) {
                $patient->payments()->restore();
            }
            Patient::insert($patient->withoutRelations()->toArray());
            $patient->delete();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
        return response()->json(['message' => __('app.success')]);
    }
}
