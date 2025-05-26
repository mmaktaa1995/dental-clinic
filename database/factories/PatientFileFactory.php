<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\PatientFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFileFactory extends Factory
{
    protected $model = PatientFile::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'file' => 'patient-files/' . $this->faker->uuid . '.jpg',
            'file_name' => $this->faker->word . '.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
