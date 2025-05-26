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
        // First, clean up any duplicate names by appending a random string to make them unique
        $duplicates = \DB::table('patients')
            ->select('name', \DB::raw('COUNT(*) as count'))
            ->groupBy('name')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $patients = \DB::table('patients')
                ->where('name', $duplicate->name)
                ->orderBy('id')
                ->skip(1) // Skip the first one
                ->get();

            foreach ($patients as $index => $patient) {
                $newName = $patient->name . '_' . uniqid();
                \DB::table('patients')
                    ->where('id', $patient->id)
                    ->update(['name' => $newName]);
            }
        }

        // Now add the unique constraint
        Schema::table('patients', function (Blueprint $table) {
            $table->string('name')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
