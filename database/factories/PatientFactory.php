<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'age' => $this->faker->numberBetween(1, 100),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'file_number' => 'PAT' . $this->faker->unique()->numberBetween(1000, 9999),
            'total_amount' => $this->faker->randomFloat(2, 0, 10000),
            'user_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the patient is male.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function male()
    {
        return $this->state(function (array $attributes) {
            return [
                'gender' => 'male',
            ];
        });
    }

    /**
     * Indicate that the patient is female.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function female()
    {
        return $this->state(function (array $attributes) {
            return [
                'gender' => 'female',
            ];
        });
    }

    /**
     * Indicate that the patient has a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forUser($userId)
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'user_id' => $userId,
            ];
        });
    }
}
