<?php

namespace App\Services;

use App\Http\Requests\PaymentRequest;
use App\Models\Patient;
use App\Models\PatientRecord;
use App\Models\Payment;
use App\Models\Tooth;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Create a new payment with related visit and update remaining amounts
     *
     * @param PaymentRequest $request
     * @return void
     */
    public function createPayment(PaymentRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $patientId = $request->get('patient_id');
            $amount = $request->get('amount');
            $originalPaymentId = $request->get('payment_id');

            // Create the visit first
            $visit = Visit::create($data);

            // If this is a payment against an existing payment (partial payment)
            if ($originalPaymentId) {
                $originalPayment = Payment::findOrFail($originalPaymentId);

                // Update the original payment's remaining amount
                $remainingAmount = max(0, $originalPayment->remaining_amount - $amount);
                $originalPayment->update(['remaining_amount' => $remainingAmount]);

                // Create a new payment for the current transaction
                $paymentData = array_merge($data, [
                    'visit_id' => $visit->id,
                    'remaining_amount' => $amount, // New payment's remaining is the full amount
                ]);

                $visit->payment()->create($paymentData);
            } else {
                // This is a new payment with no parent
                $paymentData = array_merge($data, [
                    'visit_id' => $visit->id,
                    'remaining_amount' => $data['remaining_amount'] ?? $amount,
                ]);

                $visit->payment()->create($paymentData);
            }

            // Handle teeth treatment if needed
            if ($teethIds = $request->get('teeth_ids')) {
                // Debug: Log the teeth IDs we're trying to update
                \Log::debug('Updating teeth treatment status', [
                    'teeth_ids' => $teethIds,
                    'patient_id' => $patientId,
                    'patient_record_id' => $request->get('patient_record_id')
                ]);

                // Get the patient record if provided, otherwise get the latest one
                $patientRecord = $request->has('patient_record_id')
                    ? PatientRecord::find($request->get('patient_record_id'))
                    : PatientRecord::where('patient_id', $patientId)
                        ->latest()
                        ->first();

                if ($patientRecord) {
                    // Debug: Log the patient record we found
                    \Log::debug('Found patient record', [
                        'patient_record_id' => $patientRecord->id,
                        'patient_id' => $patientRecord->patient_id
                    ]);

                    // Get the tooth models for the given IDs
                    $teeth = Tooth::whereIn('number', $teethIds)->get();

                    // Debug: Log the teeth we found
                    \Log::debug('Found teeth', [
                        'teeth' => $teeth->pluck('id', 'number')->toArray()
                    ]);

                    // Update each tooth's pivot record individually
                    foreach ($teeth as $tooth) {
                        $updated = $patientRecord->affectedTeeth()->updateExistingPivot(
                            $tooth->id,
                            ['is_treated' => true]
                        );

                        // Debug: Log the update result
                        \Log::debug('Updated tooth pivot', [
                            'tooth_id' => $tooth->id,
                            'tooth_number' => $tooth->number,
                            'updated' => $updated
                        ]);
                    }
                } else {
                    \Log::debug('No patient record found');
                }
            }
        });
    }

    /**
     * Update an existing payment
     *
     * @param PaymentRequest $request
     * @param Payment        $payment
     * @return void
     */
    public function updatePayment(PaymentRequest $request, Payment $payment): void
    {
        DB::transaction(function () use ($request, $payment) {
            $data = $request->only(['amount', 'date', 'remaining_amount']);
            $payment->update($data);
            $payment->visit()->update([
                'date' => $request->get('date'),
                'notes' => $request->get('notes')
            ]);

            if ($teethIds = $request->get('teeth_ids')) {
                $patientId = $request->get('patient_id');
                $patientRecordId = $request->get('patient_record_id');

                // Get the patient record if provided, otherwise get the latest one
                $patientRecord = $patientRecordId
                    ? PatientRecord::find($patientRecordId)
                    : PatientRecord::where('patient_id', $patientId)
                        ->latest()
                        ->first();

                if ($patientRecord) {
                    // Get the tooth models for the given tooth numbers
                    $teeth = Tooth::whereIn('number', $teethIds)->get();

                    // Update each tooth's pivot record individually
                    foreach ($teeth as $tooth) {
                        $patientRecord->affectedTeeth()->updateExistingPivot(
                            $tooth->id,
                            ['is_treated' => true]
                        );
                    }
                }
            }
        });
    }

    /**
     * Delete a payment and its related visit
     *
     * @param Payment $payment
     * @return void
     */
    public function deletePayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->visit()->delete();
            $payment->delete();
        });
    }

    /**
     * Restore a soft-deleted payment and its visit
     *
     * @param Payment $payment
     * @return void
     */
    public function restorePayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->visit()->withTrashed()->restore();
            $payment->restore();
        });
    }
}
