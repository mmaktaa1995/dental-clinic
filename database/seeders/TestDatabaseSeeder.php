<?php

namespace Database\Seeders;

use App\Models\AppConfig;
use App\Models\Appointment;
use App\Models\Expense;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\PatientFile;
use App\Models\PatientRecord;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Tooth;
use App\Models\User;
use App\Models\Visit;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's test database.
     *
     * @return void
     */
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    public function run()
    {
        // Create test users
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'admin' => true,
            ]
        );

        $dentist = User::query()->firstOrCreate(
            ['email' => 'dentist@example.com'],
            [
                'name' => 'Dr. Smith',
                'username' => 'dentist',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'admin' => false,
            ]
        );

        // Create test patients
        $patients = Patient::factory()
            ->count(10)
            ->create(['user_id' => $admin->id]);

        // Create services
        $services = [
            ['name' => 'Dental Checkup', 'price' => 50, 'duration' => 30],
            ['name' => 'Teeth Cleaning', 'price' => 80, 'duration' => 45],
            ['name' => 'Filling', 'price' => 120, 'duration' => 60],
            ['name' => 'Root Canal', 'price' => 300, 'duration' => 90],
            ['name' => 'Tooth Extraction', 'price' => 150, 'duration' => 45],
        ];

        foreach ($services as $service) {
            Service::query()->firstOrCreate(
                ['name' => $service['name']],
                array_merge($service, ['user_id' => $admin->id])
            );
        }

        // Create appointments, visits, and payments for each patient
        $patients->each(function ($patient) use ($admin, $dentist) {
            // Create 1-3 visits per patient
            $visits = Visit::factory()
                ->count(rand(1, 3))
                ->create([
                    'patient_id' => $patient->id,
                    'user_id' => $dentist->id,
                ]);

            // Create payments for each visit
            $visits->each(function ($visit) use ($patient, $admin) {
                Payment::factory()
                    ->count(rand(1, 2))
                    ->create([
                        'patient_id' => $patient->id,
                        'visit_id' => $visit->id,
                        'user_id' => $admin->id,
                    ]);
            });

            // Create patient records
            PatientRecord::factory()
                ->count(rand(1, 3))
                ->create([
                    'patient_id' => $patient->id,
                    'user_id' => $dentist->id,
                    'record_date' => now()->subDays(rand(1, 30)),
                ]);

            // Create medications (many-to-many relationship)
            if (rand(0, 1)) { // 50% chance to have medications
                Medication::factory()
                    ->count(rand(1, 3))
                    ->forPatient($patient)
                    ->create(['user_id' => $dentist->id]);
            }

            // Create patient files
            if (rand(0, 1)) { // 50% chance to have files
                PatientFile::factory()
                    ->count(rand(1, 3))
                    ->create([
                        'patient_id' => $patient->id,
                        'user_id' => $admin->id,
                    ]);
            }

            // Create patient records with teeth (many-to-many through patient_records)
            // Only if there are teeth in the database
            if (Tooth::query()->count() > 0) {
                $patientRecord = PatientRecord::factory()
                    ->create([
                        'patient_id' => $patient->id,
                        'user_id' => $dentist->id,
                        'record_date' => now()->subDays(rand(1, 30)),
                    ]);

                // Attach some teeth to the patient record
                $teeth = Tooth::query()->inRandomOrder()->take(rand(5, min(10, Tooth::query()->count())))->get();
                foreach ($teeth as $tooth) {
                    $patientRecord->affectedTeeth()->attach($tooth->id, [
                        'is_treated' => (bool)rand(0, 1),
                        'description' => rand(0, 1) ? $this->faker->sentence : null
                    ]);
                }
            }
        });

        // Create some expenses
        Expense::factory()
            ->count(15)
            ->create(['user_id' => $admin->id]);

        // Create some appointments
        foreach (range(1, 20) as $i) {
            $patient = $patients->random();
            $appointmentDate = now()->addDays(rand(1, 30))->setHour(rand(9, 16))->setMinute(0);

            Appointment::factory()
                ->create([
                    'patient_id' => $patient->id,
                    'user_id' => $dentist->id,
                    'date' => $appointmentDate,
                    'notes' => rand(0, 1) ? $this->faker->sentence : null,
                ]);
        }

        // Ensure the app_configs table exists
        if (!Schema::hasTable('app_configs')) {
            Schema::create('app_configs', function (Blueprint $table) {
                $table->id();
                $table->string('key');
                $table->text('value');
                $table->timestamps();
            });
        }

        // Set up default app config
        if (!AppConfig::query()->where('key', 'usd_exchange')->exists()) {
            AppConfig::query()->create([
                'key' => 'usd_exchange',
                'value' => '6000',
            ]);
        }
    }
}
