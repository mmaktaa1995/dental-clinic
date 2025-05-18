<?php

namespace App\Http\Controllers;

use App\Http\Requests\DebitsSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\DebitsResource;
use App\Models\Patient;
use App\Models\Payment;
use App\Services\Search\DebitSearch;
use Illuminate\Http\JsonResponse;

class DebitsController extends Controller
{
    public function debits(DebitsSearchRequest $request, ?Patient $patient): JsonResponse
    {
        $debitsSearch = new DebitSearch($request->merge(['patient_id' => $patient?->id]));
        $totalDebits = Payment::when($patient->exists, function ($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })->where('user_id', auth()->id())
            ->sum('remaining_amount');

        return response()->json(BaseCollection::make($debitsSearch->getEntries(), DebitsResource::class, 'entries', ['totalDebits' => $totalDebits]));
    }
}
