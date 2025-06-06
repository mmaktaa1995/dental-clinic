<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Meneses\LaravelMpdf\Facades\LaravelMpdf;

class PatientsFilesController extends Controller
{
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
            'order_column' => $request->input('order_column', 'latest_payment_date'),
            'order_dir' => $request->input('order_dir', 'desc'),
            'per_page' => $request->input('per_page', 10),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
            'groupBy' => 'patient_id',
            'distinct' => 'patient_id',
        ];
        Payment::$columnsToSelect = [
            'patient_id', 'date', 'id',
            'latest_payment_date' => Payment::from('payments as p')->select('date')
                ->whereColumn('p.patient_id', 'payments.patient_id')
                ->whereColumn('p.amount', '>', DB::raw("0"))
                ->orderBy('id', 'desc')
                ->limit('1'),
            'latest_payment' => Payment::from('payments as p')->select('amount')
                ->whereColumn('p.patient_id', 'payments.patient_id')
                ->whereColumn('p.amount', '>', DB::raw("0"))
                ->orderBy('id', 'desc')
                ->limit('1'),
            'total_remaining_amount' => Payment::from('payments as p')->select(DB::raw("SUM(remaining_amount)"))
                ->whereColumn('p.patient_id', 'payments.patient_id')
                ->limit('1'),
        ];
        $data = Payment::getAll($params);
        return response()->json(BaseCollection::make($data, PaymentResource::class));
    }

    /**
     * @param $patient_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($patient_id): JsonResponse
    {
        $payments = Payment::query()
            ->with(['patient', 'visit'])
            ->where('patient_id', $patient_id)
            ->orderBy('remaining_amount', 'desc')
            ->orderBy('date', 'desc')
            ->get();
        return response()->json(PaymentResource::collection($payments));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\PaymentRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(PaymentRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $visit = Visit::create($request->validated());
            $visit->payment()->create($request->validated());
            $amount = $request->get('amount');
            $remainingAmountPayment = Payment::query()
                ->where('patient_id', $request->get('patient_id'))
                ->where('remaining_amount', '>', 0)
                ->first();
            if ($remainingAmountPayment) {
                $remainingAmountPayment->decrement('remaining_amount', $amount);
                if ($remainingAmountPayment->remaining_amount < 0){
                    $remainingAmountPayment->remaining_amount = 0;
                }
                $remainingAmountPayment->save();
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
        return response()->json(['message' => __('app.success')]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\PaymentRequest $request
     * @param \App\Models\Payment $payment
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(PaymentRequest $request, Payment $payment): JsonResponse
    {
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
            DB::beginTransaction();
            $visit = Visit::create(array_merge($request->validated(), ['notes' => 'دفعة']));
            if ($newRemainingAmount == 0) {
                $payment->delete();
            } else {
                $payment->update(array_merge($request->validated(), ['remaining_amount' => $newRemainingAmount, 'amount' => $oldAmount]));
            }
            Payment::create(array_merge($request->validated(), ['remaining_amount' => 0, 'visit_id' => $visit->id, 'date' => now()]));
            DB::commit();
        }
        return response()->json(['message' => __('app.success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Payment $payment
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $patient_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function print($patient_id)
    {
        try {
            $patient = Patient::find($patient_id);
            $payments = $patient->payments()->with(['patient', 'visit'])->orderBy('date', 'desc')->get();
            $totalPayments = $payments->sum->amount;
            $totalRemainingPayments = $payments->sum->remaining_amount;
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
}
