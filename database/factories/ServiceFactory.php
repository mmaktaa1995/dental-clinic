<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'duration' => $this->faker->numberBetween(15, 120), // in minutes
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'user_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
