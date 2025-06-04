<?php

namespace Tests\Unit\Models;

use App\Models\Payment;
use App\Models\Service;
use App\Models\ServicePayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_payment_relationship()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a service
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        // Create a payment and attach the service to it
        $payment = Payment::factory()->create(['user_id' => $user->id]);
        $service->payments()->attach($payment->id, [
            'price' => $service->price,
            'notes' => 'Test notes',
        ]);
        
        // Reload the service to refresh relationships
        $service->refresh();
        
        // Assert the payment relationship
        $this->assertInstanceOf(Payment::class, $service->payments->first());
        $this->assertEquals($payment->id, $service->payments->first()->id);
        $this->assertEquals($service->price, $service->payments->first()->pivot->price);
        $this->assertEquals('Test notes', $service->payments->first()->pivot->notes);
    }
    
    /** @test */
    public function it_has_a_service_payment_relationship()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a service
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        // Create a payment
        $payment = Payment::factory()->create(['user_id' => $user->id]);
        
        // Attach the service to the payment with additional pivot data
        $service->payments()->attach($payment->id, [
            'price' => $service->price,
            'notes' => 'Test notes',
        ]);
        
        // Reload the service to refresh relationships
        $service->refresh();
        
        // Get the first service payment through the relationship
        $servicePayment = $service->servicePayments->first();
        
        // Assert the service payment relationship
        $this->assertInstanceOf(ServicePayment::class, $servicePayment);
        $this->assertEquals($service->price, $servicePayment->price);
        $this->assertEquals('Test notes', $servicePayment->notes);
    }
    
    /** @test */
    public function it_belongs_to_a_user()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a service with the user
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        // Reload the service to refresh relationships
        $service->refresh();
        
        // Assert the user relationship
        $this->assertInstanceOf(User::class, $service->user);
        $this->assertEquals($user->id, $service->user->id);
    }
}
