<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('patient_files', 'file_name')) {
            Schema::table('patient_files', function (Blueprint $table) {
                $table->string('file_name')->nullable()->after('type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('patient_files', 'file_name')) {
            Schema::table('patient_files', function (Blueprint $table) {
                $table->dropColumn('file_name');
            });
        }
    }
};
