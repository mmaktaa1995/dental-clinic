<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientSearchRequest;
use App\Http\Requests\PatientVisitSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\PatientApiResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\VisitResource;
use App\Models\DeletedPatient;
use App\Models\Patient;
use App\Models\Payment;
use App\Services\PatientService;
use App\Services\Search\PatientApiListSearch;
use App\Services\Search\PatientSearch;
use App\Services\Search\VisitSearch;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Js;
use Schema;

class PatientsController extends Controller
{
    public function list(PatientSearchRequest $request)
    {
        $patientSearch = new PatientSearch($request);

        return response()->json(BaseCollection::make($patientSearch->getEntries(), PatientResource::class));
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
        $patient->load(['files', 'symptoms', 'diagnosis', 'affectedTeeth']);
        return response()->json(PatientResource::make($patient));
    }

    public function checkExisting(Request $request): JsonResponse
    {
        $existingPatient = Patient::where('user_id', auth()->id())->where('name', $request->get('query'))->first();
        if ($existingPatient) {
            return response()->json(['message' => trans('app.existPatient', ['file_number' => $existingPatient->file_number]), 'file_number' => $existingPatient->file_number, 'id' => $existingPatient->id]);
        }
        return response()->json([]);
    }

    public function store(PatientRequest $request): JsonResponse
    {
        $existingPatient = Patient::where('user_id', auth()->id())->where('name', $request->get('name'))->first();
        if ($existingPatient) {
            return response()->json(['message' => trans('app.existPatient', ['file_number' => $existingPatient->file_number]), 'file_number' => $existingPatient->file_number, 'id' => $existingPatient->id], 500);
        }
        $patient = null;
        DB::transaction(function () use ($request, &$patient) {
            $patient = Patient::create($request->only(["name", "age", "gender", "file_number", "phone", "mobile", "total_amount"]));
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

            if ($symptoms = $request->get('symptoms', [])) {
                $patient->load('records');
                $symptomsToAdd = collect($symptoms)->filter(fn($symptom) => $symptom['id'] < 0);
                $patient->records()->createMany($symptomsToAdd->toArray());
            }
            if ($diagnosis = $request->get('diagnosis', [])) {
                $patient->load('records');
                $diagnosisToAdd = collect($diagnosis)->filter(fn($diagnose) => $diagnose['id'] < 0);
                $diagnosisToAdd->each(function ($diagnose) use ($patient) {
                    $record = $patient->records()->create($diagnose);
                    if ($diagnose['teeth_ids']) {
                        $record->affectedTeeth()->sync($diagnose['teeth_ids']);
                    }
                });
            }
        });
        return response()->json(['message' => __('app.success'), 'patient' => ['id' => $patient->id]]);
    }

    public function visits(PatientVisitSearchRequest $request, ?Patient $patient): JsonResponse
    {
        $visitSearch = new VisitSearch($request->merge(['patient_id' => $patient?->id]));

        return response()->json(BaseCollection::make($visitSearch->getEntries(), VisitResource::class));
    }

    public function update(PatientRequest $request, Patient $patient): JsonResponse
    {
        $patient->update($request->validated());
        if ($symptoms = $request->get('symptoms', [])) {
            $patient->load('records');
            $symptomsToAdd = collect($symptoms)->filter(fn($symptom) => $symptom['id'] < 0);
            $symptomsToEdit = collect($symptoms)->filter(fn($symptom) => $symptom['id'] > 0);
            $patient->records()->createMany($symptomsToAdd->toArray());
            $symptomsToEdit->each(function ($symptom) use ($patient) {
                $record = $patient->records->where('id', $symptom['id'])->first();
                if ($record) {
                    $record->update(['symptoms' => $symptom['symptoms'], 'record_date' => $symptom['record_date']]);
                }
            });
        }
        if ($diagnosis = $request->get('diagnosis', [])) {
            $patient->loadMissing('records');
            $diagnosisToAdd = collect($diagnosis)->filter(fn($diagnose) => $diagnose['id'] < 0);
            $diagnosisToEdit = collect($diagnosis)->filter(fn($diagnose) => $diagnose['id'] > 0);
            $diagnosisToAdd->each(function ($diagnose) use ($patient) {
                $record = $patient->records()->create($diagnose);
                if ($diagnose['teeth_ids']) {
                    $record->affectedTeeth()->sync($diagnose['teeth_ids']);
                }
            });
//            $diagnosisToEdit->each(function ($diagnose) use ($patient) {
//                $record = $patient->records->where('id', $diagnose['id'])->first();
//                if ($record) {
//                    $record->update(['diagnosis' => $diagnose['diagnosis'], 'record_date' => $diagnose['record_date']]);
//                    if ($diagnose['teeth_ids']) {
//                        $record->affectedTeeth()->sync($diagnose['teeth_ids']);
//                    }
//                }
//            });
        }
        $patient->load(['files', 'symptoms', 'diagnosis']);
        return response()->json(['message' => __('app.success'), 'patient' => PatientResource::make($patient)]);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        DB::transaction(function () use ($patient) {
            $patient->payments()->delete();
            $patient->visits()->delete();
            $patient->records()->delete();
            $patient->delete();
        });
        return response()->json(['message' => __('app.success')]);
    }

    public function restore(DeletedPatient $patient): JsonResponse
    {
        DB::transaction(function () use ($patient) {
            if ($patient->visits()->count()) {
                $patient->visits()->restore();
            }
            if ($patient->payments()->count()) {
                $patient->payments()->restore();
            }
            Patient::insert($patient->withoutRelations()->toArray());
            $patient->delete();
        });

        return response()->json(['message' => __('app.success')]);
    }
}
