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
        Schema::table('patient_records', function (Blueprint $table) {
            // Add new columns
            $table->string('title')->nullable()->after('patient_id');
            $table->text('description')->nullable()->after('title');
            $table->text('treatment')->nullable()->after('description');
            $table->text('notes')->nullable()->after('treatment');
            $table->timestamp('next_visit_date')->nullable()->after('notes');

            // Make symptoms and diagnosis nullable if they're not already
            $table->text('symptoms')->nullable()->change();
            $table->text('diagnosis')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_records', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'description',
                'treatment',
                'notes',
                'next_visit_date'
            ]);
        });
    }
};
