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
        Schema::create('teeth', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->unsignedInteger('number')->nullable();
            $table->text('extra')->nullable();
        });

        Schema::create('patient_record_teeth', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_record_id');
            $table->unsignedBigInteger('tooth_id');
            $table->boolean('is_treated')->default(0);
            $table->string('description')->nullable();

            $table->foreign('patient_record_id')->references('id')->on('patient_records')->onDelete('cascade');
            $table->foreign('tooth_id')->references('id')->on('teeth')->onDelete('cascade');
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
