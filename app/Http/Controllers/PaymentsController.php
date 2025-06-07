<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Requests\PaymentSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use App\Services\Search\PaymentSearch;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class PaymentsController extends Controller
{
    public function list(?Patient $patient, PaymentSearchRequest $request): JsonResponse
    {
        $patientPaymentsSearch = new PaymentSearch($request->merge(['patient_id' => $patient?->id]));

        $totalPayments = Payment::when($patient->exists, function ($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })
        ->where('user_id', auth()->id())
        ->sum('amount');

        $totalRemainingPayments = Payment::when($patient->exists, function ($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })
        ->where('user_id', auth()->id())
        ->sum('remaining_amount');

        return response()->json(
            BaseCollection::make(
                $patientPaymentsSearch->getEntries(),
                PaymentResource::class,
                'entries',
                [
                    'total_payments' => $totalPayments,
                    'total_remaining_payments' => $totalRemainingPayments,
                ]
            )
        );
    }

    public function store(PaymentRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $originalPaymentId = $request->get('payment_id');
            $remainingAmountPayment = Payment::query()
                ->where('patient_id', $request->get('patient_id'))
                ->where('remaining_amount', '>', 0)
                ->when($originalPaymentId, function ($query) use ($originalPaymentId) {
                    $query->where('id', $originalPaymentId);
                })
                ->first();

            $amount = $request->get('amount');
            $data = $request->validated();
            $visit = Visit::create($data);

            if ($remainingAmountPayment) {
                if ($amount > $remainingAmountPayment->remaining_amount) {
                    $data['remaining_amount'] = 0;
                } else {
                    $data['remaining_amount'] = $remainingAmountPayment->remaining_amount - $amount;
                }
            }
            $visit->payment()->create($data);

            if ($remainingAmountPayment) {
                $remainingAmountPayment->remaining_amount = $remainingAmountPayment->remaining_amount - $amount;
                if ($remainingAmountPayment->remaining_amount < 0) {
                    $remainingAmountPayment->remaining_amount = 0;
                }
                $remainingAmountPayment->save();
            }
            if ($teethIds = $request->get('teeth_ids')) {
                $patient = Patient::query()->findOrFail($request->get('patient_id'));
                $patient->affectedTeeth()->whereIn('tooth_id', $teethIds)->update(['is_treated' => 1]);
            }
        });

        return response()->json(['message' => __('app.success')]);
    }

    public function update(PaymentRequest $request, Payment $payment): JsonResponse
    {
        DB::transaction(function () use ($request, $payment) {
            $data = $request->only(['amount', 'date', 'remaining_amount']);
            $payment->update($data);
            $payment->visit()->update(['date' => $request->get('date'), 'notes' => $request->get('notes')]);

            if ($teethIds = $request->get('teeth_ids')) {
                $patient = Patient::query()->findOrFail($request->get('patient_id'));
                $patient->affectedTeeth()->whereIn('tooth_id', $teethIds)->update(['is_treated' => 1]);
            }
        });

        return response()->json(['message' => __('app.success')]);
    }

    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $payment->visit()->delete();
            $payment->delete();
        });

        return response()->json(['message' => __('app.success')]);
    }

    public function print(Patient $patient): JsonResponse
    {
        try {
            $payments = $patient->payments()->with(['patient', 'visit'])->orderBy('date', 'desc')->get();
            $totalPayments = $payments->sum('amount');
            $totalRemainingPayments = $payments->sum('remaining_amount');
            $fileName = "{$patient->name}-{$patient->file_number}.pdf";
            $pdf1 = LaravelMpdf::loadView('pdf', compact('payments', 'patient', 'totalPayments', 'totalRemainingPayments'));
            $pdf1->save(storage_path("app/public/pdf/patients/$fileName"));
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json([
            'url' => action(
                [UploadFilesController::class, 'show'],
                ['folder' => 'patients', 'name' => $fileName, 'type' => 'pdf']
            )]);
    }

    public function restore(Payment $payment): JsonResponse
    {
        DB::transaction(function () use ($payment) {
            $payment->visit()->withTrashed()->restore();
            $payment->restore();
        });

        return response()->json(['message' => __('app.success')]);
    }
}
