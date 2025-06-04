<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTestPatient extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get or create a test user
        $user = DB::table('users')->first();
        
        if (!$user) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'username' => 'testuser',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $userId = $user->id;
        }

        // Create a test patient
        DB::table('patients')->insert([
            'user_id' => $userId,
            'name' => 'Test Patient',
            'age' => 30,
            'gender' => 1, // 1 for male, 0 for female
            'file_number' => 123, // Ensure this is an integer
            'phone' => '1234567890',
            'mobile' => '0987654321',
            'total_amount' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the test patient
        DB::table('patients')->where('file_number', 'TEST-123')->delete();
        
        // Optionally remove the test user if it was created by this migration
        // DB::table('users')->where('email', 'test@example.com')->delete();
    }
}
