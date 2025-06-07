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
        Schema::rename('patient_images', 'patient_files');
        Schema::table('patient_files', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->string('file_name')->nullable();
            $table->renameColumn('image', 'file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('patient_files', 'patient_images');
    }
};
