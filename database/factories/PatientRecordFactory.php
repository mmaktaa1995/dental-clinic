<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\PatientRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientRecordFactory extends Factory
{
    protected $model = PatientRecord::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'symptoms' => $this->faker->optional(0.8)->text,
            'diagnosis' => $this->faker->optional(0.7)->text,
            'record_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
