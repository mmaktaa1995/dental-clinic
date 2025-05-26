<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the roles for the dental clinic
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Administrator with full access to all system features',
            ],
            [
                'name' => 'Dentist',
                'slug' => 'dentist',
                'description' => 'Dentist with access to patient records, appointments, and treatments',
            ],
            [
                'name' => 'Assistant',
                'slug' => 'assistant',
                'description' => 'Dental assistant with limited access to patient records and appointments',
            ],
            [
                'name' => 'Receptionist',
                'slug' => 'receptionist',
                'description' => 'Receptionist with access to appointments and patient registration',
            ],
            [
                'name' => 'Patient',
                'slug' => 'patient',
                'description' => 'Patient with access to their own records and appointments',
            ],
        ];

        // Create each role
        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
