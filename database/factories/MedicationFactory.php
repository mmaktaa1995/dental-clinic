<?php

namespace Database\Factories;

use App\Models\Medication;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicationFactory extends Factory
{
    protected $model = Medication::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->optional(0.7)->sentence,
            'manufacturer' => $this->faker->optional(0.8)->company,
            'price' => $this->faker->optional(0.7)->randomFloat(2, 5, 200),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the model factory to automatically attach to a patient
     */
    public function forPatient($patient)
    {
        return $this->afterCreating(function (Medication $medication) use ($patient) {
            $patient->medications()->attach(
                $medication->id,
                [
                    'dosage' => $this->faker->randomElement([
                        '250mg', '500mg', '1g', '5ml', '10ml'
                    ]),
                    'frequency' => $this->faker->randomElement([
                        'once daily', 'twice daily', 'three times daily', 'as needed'
                    ]),
                    'start_date' => now()->subDays(rand(1, 30)),
                    'end_date' => now()->addDays(rand(7, 90)),
                ]
            );
        });
    }
}
