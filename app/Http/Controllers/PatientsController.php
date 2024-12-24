<?php

namespace App\Http\Controllers;

use App\Http\Requests\DebitsSearchRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\DebitsResource;
use App\Http\Resources\PatientApiResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PaymentResource;
use App\Models\DeletedPatient;
use App\Models\Patient;
use App\Models\Payment;
use App\Services\PatientService;
use App\Services\Search\DebitsSearch;
use App\Services\Search\PatientApiListSearch;
use App\Services\Search\PatientSearch;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DB;
use Schema;

class PatientsController extends Controller
{
    public function list(PatientSearchRequest $request)
    {
        $patientSearch = new PatientSearch($request);

        return response()->json(BaseCollection::make($patientSearch->getEntries(), PatientResource::class));
    }

    public function dropdownData(Request $request): JsonResponse
    {
        return response()->json(Patient::all()->pluck('name', 'id'));
    }

    public function apiList(PatientSearchRequest $request): JsonResponse
    {
        $patientSearch = new PatientApiListSearch($request);

        return response()->json(BaseCollection::make($patientSearch->getEntries(), PatientApiResource::class));

    }

    public function lastFileNumber(PatientService $patientService): JsonResponse
    {
        $lastFileNumber = $patientService->getLastFileNumber();

        return response()->json(['last_file_number' => $lastFileNumber]);
    }

    public function show(Patient $patient): JsonResponse
    {
        $patient->load('images');
        return response()->json(PatientResource::make($patient));
    }

    public function store(PatientRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
        return response()->json(['message' => __('app.success'), 'id' => $patient->id]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param PatientRequest $request
     * @param Patient $patient
     *
     * @return JsonResponse
     */
    public function update(PatientRequest $request, Patient $patient): JsonResponse
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

    public function destroy(Patient $patient): JsonResponse
    {
        try {
           DB::transaction(function () use ($patient) {
               Schema::disableForeignKeyConstraints();
               $patient->delete();
               Schema::enableForeignKeyConstraints();
           });
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
     * @return JsonResponse
     */
    public function debits(DebitsSearchRequest $request, ?Patient $patient): JsonResponse
    {
        $debitsSearch = new DebitsSearch($request->merge(['patient_id' => $patient?->id]));

        return response()->json(BaseCollection::make($debitsSearch->getEntries(), DebitsResource::class));
    }

    /**
     * restore the specified resource.
     *
     * @psalm-param DeletedPatient $patient
     *
     * @psalm-return  JsonResponse
     */
    public function restore(DeletedPatient $patient): JsonResponse
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
