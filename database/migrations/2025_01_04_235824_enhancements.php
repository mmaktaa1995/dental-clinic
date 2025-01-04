<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('date');
        });
        Schema::table('patients', function (Blueprint $table) {
            $table->index('file_number');
            $table->index('name');
            $table->index('created_at');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->index('deleted_at');
            $table->index('date');
            $table->index('created_at');
        });
        Schema::table('financial_expenses', function (Blueprint $table) {
            $table->index('amount');
            $table->index('created_at');
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
