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
        $totalPayments = $patientPaymentsSearch->getQuery()->sum('amount');
        $totalRemainingPayments = $patientPaymentsSearch->getQuery()->sum('remaining_amount');

        return response()->json(BaseCollection::make($patientPaymentsSearch->getEntries(), PaymentResource::class, 'entries', [
            'total_payments' => $totalPayments,
            'total_remaining_payments' => $totalRemainingPayments,
        ]));
    }

    public function show(Payment $payment): JsonResponse
    {
        $payment->load(['patient', 'visit']);

        return response()->json(PaymentResource::make($payment));
    }

    public function store(PaymentRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $remainingAmountPayment = Payment::query()
                ->where('patient_id', $request->get('patient_id'))
                ->where('remaining_amount', '>', 0)
                ->first();
            $visit = Visit::create($request->validated());
            $visit->payment()->create($request->validated());
            $amount = $request->get('amount');

            if ($remainingAmountPayment) {
                $remainingAmountPayment->decrement('remaining_amount', $amount);
                if ($remainingAmountPayment->remaining_amount < 0) {
                    $remainingAmountPayment->remaining_amount = 0;
                }
                $remainingAmountPayment->save();
            }
        });
        return response()->json(['message' => __('app.success')]);
    }

    public function update(PaymentRequest $request, Payment $payment): JsonResponse
    {
        DB::transaction(function () use ($request, $payment) {
            if (!$request->filled('is_pay_debt')) {
                $payment->update($request->validated());
                $payment->visit()->update(['date' => $request->get('date'), 'notes' => $request->get('notes')]);
            } else {
                $remainingAmount = $request->get('remaining_amount');
                $amount = $request->get('amount');
                $oldAmount = $request->get('old_amount');
                $newRemainingAmount = $remainingAmount - $amount;
                if ($newRemainingAmount < 0) {
                    throw new Exception("المبلغ المدفوع لا يجب ان يكون اكبر من المبلغ المتبقي!");
                }
                $visit = Visit::create(array_merge($request->validated(), ['notes' => 'دفعة']));
//            if ($newRemainingAmount == 0 && $payment->amount) {
//                $payment->delete();
//            } else {
                $payment->update(array_merge($request->validated(), ['remaining_amount' => $newRemainingAmount, 'amount' => $oldAmount]));
//            }
                Payment::create(array_merge($request->validated(), ['remaining_amount' => 0, 'visit_id' => $visit->id, 'date' => now()]));
            }
        });
        return response()->json(['message' => __('app.success')]);
    }

    public function destroy(Payment $payment)
    {
        try {
            $payment->visit()->delete();
            $payment->delete();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
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
            'url' => action([UploadFilesController::class, 'show'],
                ['folder' => 'patients', 'name' => $fileName, 'type' => 'pdf'])]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Payment $payment
     *
     * @return JsonResponse
     */
    public function restore(Payment $payment): JsonResponse
    {
        try {
            $payment->visit()->withTrashed()->restore();
            $payment->restore();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
        return response()->json(['message' => __('app.success')]);
    }
}
