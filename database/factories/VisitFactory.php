<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeThisYear(),
            'notes' => $this->faker->sentence(),
        ];
    }

    /**
     * Indicate that the visit has a payment.
     *
     * @param float $amount
     * @return static
     */
    public function withPayment(float $amount = 100.00)
    {
        return $this->afterCreating(function (\App\Models\Visit $visit) use ($amount) {
            $visit->payment()->create([
                'amount' => $amount,
                'date' => $visit->date,
                'notes' => 'Visit payment',
                'user_id' => $visit->user_id,
            ]);
        });
    }
}
