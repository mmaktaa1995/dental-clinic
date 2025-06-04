<?php

namespace Tests\Unit\Models;

use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        /** @var Authenticatable $user */
        $user = $this->user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test */
    public function it_belongs_to_a_patient()
    {
        $patient = Patient::factory()->create(['user_id' => $this->user->id]);
        $visit = Visit::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        $visit->load('patient');
        
        $this->assertInstanceOf(Patient::class, $visit->patient);
        $this->assertEquals($patient->id, $visit->patient->id);
    }

    /** @test */
    public function it_has_one_payment()
    {
        $patient = Patient::factory()->create(['user_id' => $this->user->id]);
        $visit = Visit::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);
        
        $payment = Payment::create([
            'patient_id' => $patient->id,
            'visit_id' => $visit->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 100,
            'remaining_amount' => 0,
            'status' => 'paid'
        ]);

        $this->assertInstanceOf(Payment::class, $visit->payment);
        $this->assertEquals($payment->id, $visit->payment->id);
    }

    /** @test */
    public function it_returns_formatted_date()
    {
        $visit = Visit::factory()->create([
            'user_id' => $this->user->id,
            'date' => '2023-06-15 14:30:00'
        ]);

        $this->assertEquals('2023-06-15', $visit->date);
    }

    /** @test */
    public function it_returns_amount_from_payment_relation()
    {
        $patient = Patient::factory()->create(['user_id' => $this->user->id]);
        $visit = Visit::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);
        
        Payment::create([
            'patient_id' => $patient->id,
            'visit_id' => $visit->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 150,
            'remaining_amount' => 0,
            'status' => 'paid'
        ]);

        // Reload the visit to ensure the payment relation is loaded
        $visit->load('payment');
        
        $this->assertEquals(150, $visit->amount);
    }

    /** @test */
    public function it_returns_zero_amount_when_payment_relation_not_loaded()
    {
        $visit = Visit::factory()->create([
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        // Don't load the payment relation
        $this->assertEquals(0, $visit->amount);
    }
}
