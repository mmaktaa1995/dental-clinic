<?php

namespace App\Http\Controllers;

use App\Http\Requests\DebitsSearchRequest;
use App\Http\Requests\FileSearchRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\DebitsResource;
use App\Http\Resources\FileResource;
use App\Http\Resources\PatientApiResource;
use App\Http\Resources\PatientResource;
use App\Models\DeletedPatient;
use App\Models\Patient;
use App\Models\PatientFile;
use App\Models\Payment;
use App\Services\PatientService;
use App\Services\Search\DebitsSearch;
use App\Services\Search\FileSearch;
use App\Services\Search\PatientApiListSearch;
use App\Services\Search\PatientSearch;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $patient->load('files');
        return response()->json(PatientResource::make($patient));
    }

    public function store(PatientRequest $request): JsonResponse
    {
        $patient = null;
        DB::transaction(function () use ($request, &$patient) {
            $patient = Patient::create($request->only(["name", "age", "gender", "file_number", "phone", "mobile"]));
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
        });
        return response()->json(['message' => __('app.success'), 'id' => $patient->id]);
    }

    public function update(PatientRequest $request, Patient $patient): JsonResponse
    {
        $patient->update($request->validated());
        return response()->json(['message' => __('app.success')]);
    }

    public function syncFiles(Request $request, Patient $patient): JsonResponse
    {
        DB::transaction(function () use ($request, $patient) {
            $files = $request->get('files', []);
            $patient->files()->createMany($files);
        });
        return response()->json(['message' => __('app.success')]);
    }

    public function files(FileSearchRequest $request, Patient $patient): JsonResponse
    {
        $filesSearch = new FileSearch($request->merge(['patient_id' => $patient->id]));

        return response()->json(BaseCollection::make($filesSearch->getEntries(), FileResource::class));
    }

    public function deleteFile(Patient $patient, PatientFile $patientFile): JsonResponse
    {
        DB::transaction(function () use ($patientFile, $patient) {
            $patientFile->delete();
        });

        return response()->json(['message' => __('app.success')]);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        DB::transaction(function () use ($patient) {
            Schema::disableForeignKeyConstraints();
            $patient->delete();
            Schema::enableForeignKeyConstraints();
        });
        return response()->json(['message' => __('app.success')]);
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
