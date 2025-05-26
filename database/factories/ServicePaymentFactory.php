<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Service;
use App\Models\ServicePayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicePaymentFactory extends Factory
{
    protected $model = ServicePayment::class;

    public function definition()
    {
        return [
            'service_id' => Service::factory(),
            'payment_id' => Payment::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
