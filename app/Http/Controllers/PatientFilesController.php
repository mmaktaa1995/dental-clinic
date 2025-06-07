<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\FileResource;
use App\Models\Patient;
use App\Models\PatientFile;
use App\Services\Search\FileSearch;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientFilesController extends Controller
{
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

    public function deleteFile(Patient $patient, PatientFile $file): JsonResponse
    {
        DB::transaction(function () use ($file, $patient) {
            $file->delete();
        });

        return response()->json(['message' => __('app.success')]);
    }
}
