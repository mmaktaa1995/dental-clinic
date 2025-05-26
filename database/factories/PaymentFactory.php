<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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
            'visit_id' => Visit::factory(),
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeThisYear(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'remaining_amount' => 0,
            'status' => 'paid',
        ];
    }

    /**
     * Indicate that the payment has remaining amount.
     *
     * @param float $remainingAmount
     * @return static
     */
    public function withRemainingAmount(float $remainingAmount)
    {
        return $this->state(function (array $attributes) use ($remainingAmount) {
            return [
                'remaining_amount' => $remainingAmount,
                'status' => 'partial'
            ];
        });
    }
}
