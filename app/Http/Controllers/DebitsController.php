<?php

namespace App\Http\Controllers;

use App\Http\Requests\DebitsSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\DebitsResource;
use App\Models\Patient;
use App\Services\Search\DebitsSearch;
use Illuminate\Http\JsonResponse;

class DebitsController extends Controller
{
    public function debits(DebitsSearchRequest $request, ?Patient $patient): JsonResponse
    {
        $debitsSearch = new DebitsSearch($request->merge(['patient_id' => $patient?->id]));
        $totalDebits = $debitsSearch->getQuery()->sum('remaining_amount');

        return response()->json(BaseCollection::make($debitsSearch->getEntries(), DebitsResource::class, 'entries', ['totalDebits' => $totalDebits]));
    }
}
