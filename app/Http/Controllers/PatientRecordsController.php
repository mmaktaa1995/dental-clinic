<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRecordRequest;
use App\Http\Requests\PatientRecordsSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\PatientRecordResource;
use App\Models\Patient;
use App\Models\PatientRecord;
use App\Services\Search\PatientRecordSearch;
use Illuminate\Http\JsonResponse;

class PatientRecordsController extends Controller
{
    public function list(PatientRecordsSearchRequest $request, Patient $patient): JsonResponse
    {
        $filesSearch = new PatientRecordSearch($request->merge(['patient_id' => $patient->id]));

        return response()->json(BaseCollection::make($filesSearch->getEntries(), PatientRecordResource::class));
    }

    public function store(PatientRecordRequest $request, Patient $patient): JsonResponse
    {
        $data = $request->validated();
        $data['patient_id'] = $patient->id;
        $patientRecord = PatientRecord::create($data);

        return response()->json(['message' => __('app.success'), 'id' => $patientRecord->id]);
    }

    public function update(PatientRecord $patientRecord, Patient $patient, PatientRecordRequest $request): JsonResponse
    {
        $patientRecord->update($request->validated());

        return response()->json(['message' => __('app.success')]);
    }

    public function destroy(PatientRecord $patientRecord)
    {
        \DB::transaction(function () use ($patientRecord) {
            $patientRecord->delete();
        });
        return response()->json(['message' => __('app.success')]);
    }

}
