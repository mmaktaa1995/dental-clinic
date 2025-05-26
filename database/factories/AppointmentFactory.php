<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'notes' => $this->faker->optional(0.7)->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
