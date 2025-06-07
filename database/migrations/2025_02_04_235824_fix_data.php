<?php

use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip this migration in testing environment or if patients table doesn't exist
        if (app()->environment('testing') || !Schema::hasTable('patients')) {
            return;
        }

        // Skip if the mysql_new connection is not configured
        if (!config('database.connections.mysql_new')) {
            return;
        }

        // Check if the deleted_at column exists before adding the condition
        $hasDeletedAt = Schema::hasColumn('patients', 'deleted_at');

        try {
            $query = Patient::with('payments.visit')
                ->where('file_number', '>=', 2415);

            if ($hasDeletedAt) {
                $query->whereNull('deleted_at');
            }

            $patients = $query->get();

            DB::transaction(function () use ($patients) {
                foreach ($patients as $patient) {
                    $patient->payments->each(function ($payment) use ($patient) {
                        if (!$payment->visit) {
                            return;
                        }

                        $visit = $payment->visit->toArray();
                        unset($visit['id']);
                        $newVisit = Visit::on('mysql_new')
                            ->create($visit);

                        if ($newVisit) {
                            $payment->setAttribute('visit_id', $newVisit->id);
                            $paymentArray = $payment->toArray();
                            unset($paymentArray['id']);
                            Payment::on('mysql_new')
                                ->create($paymentArray);
                        }
                    });
                }
            });
        } catch (\Exception $e) {
            // Log the error and continue
            \Log::error('Error in 2025_02_04_235824_fix_data migration: ' . $e->getMessage());
            return;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_record_teeth');
        Schema::dropIfExists('teeth');
    }
};
