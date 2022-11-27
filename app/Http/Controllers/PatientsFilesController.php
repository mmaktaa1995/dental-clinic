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
                ->orderBy('id', 'desc')
                ->limit('1'),
            'latest_payment' => Payment::from('payments as p')->select('amount')
                ->whereColumn('p.patient_id', 'payments.patient_id')
                ->orderBy('id', 'desc')
                ->limit('1'),
        ];
        $data = Payment::getAll($params);
        return response()->json(BaseCollection::make($data, PaymentResource::class), 200);
    }

    /**
     * @param $patient_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($patient_id): JsonResponse
    {
        $payments = Payment::with(['patient', 'visit'])->where('patient_id', $patient_id)->orderBy('date', 'desc')->get();
        return response()->json(PaymentResource::collection($payments), 200);
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
            if ($request->filled('amount')) {
                $visit->payment()->create($request->validated());
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
     */
    public function update(PaymentRequest $request, Payment $payment): JsonResponse
    {
        $payment->update($request->validated());
        $payment->visit()->update(['date' => $request->get('date'), 'notes' => $request->get('notes')]);
        return response()->json(['message' => __('app.success')], 200);
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
            $fileName = "{$patient->name}-{$patient->file_number}.pdf";
            $pdf1 = LaravelMpdf::loadView('pdf', compact('payments', 'patient', 'totalPayments'));
            $pdf1->save(storage_path("app/public/pdf/patients/$fileName"));
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json([
            'url' => action([UploadFilesController::class, 'show'],
                ['folder' => 'patients', 'name' => $fileName, 'type' => 'pdf'])]);
    }
}
