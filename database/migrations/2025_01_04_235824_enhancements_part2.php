<?php

use App\Models\Appointment;
use App\Models\Expense;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
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
        $user = User::where('email', 'mehdi@aktaa-dental.com')->first();
        Service::query()->whereNull('user_id')->update(['user_id' => $user->id]);
        Expense::query()->whereNull('user_id')->update(['user_id' => $user->id]);
        Payment::query()->whereNull('user_id')->update(['user_id' => $user->id]);
        Appointment::query()->whereNull('user_id')->update(['user_id' => $user->id]);
        Patient::query()->whereNull('user_id')->update(['user_id' => $user->id]);
        Visit::query()->whereNull('user_id')->update(['user_id' => $user->id]);
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
