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
        $patients = Patient::with('payments.visit')->where('file_number', '>=', 2415)
            ->get();
        DB::transaction(function () use ($patients) {
            foreach ($patients as $patient) {
                $patient->payments->each(function ($payment) use ($patient) {
                    $visit = $payment->visit->toArray();
                    unset($visit['id']);
                    $newVisit = Visit::on('mysql_new')
                        ->create($visit);
                    $payment->setAttribute('visit_id', $newVisit->id);
                    $payment = $payment->toArray();
                    unset($payment['id']);
                    Payment::on('mysql_new')
                        ->create($payment);
                });
            }
        });
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
