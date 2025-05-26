<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence,
            'date' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
