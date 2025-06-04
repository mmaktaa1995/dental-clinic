<?php

namespace Tests\Unit\Models;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_patient()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a patient
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        
        // Create an appointment
        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $user->id,
        ]);
        
        // Reload the appointment to refresh relationships
        $appointment->refresh();
        
        // Assert the patient relationship
        $this->assertInstanceOf(Patient::class, $appointment->patient);
        $this->assertEquals($patient->id, $appointment->patient->id);
    }
    
    /** @test */
    public function it_belongs_to_a_user()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a patient
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        
        // Create an appointment
        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $user->id,
        ]);
        
        // Reload the appointment to refresh relationships
        $appointment->refresh();
        
        // Assert the user relationship
        $this->assertInstanceOf(User::class, $appointment->user);
        $this->assertEquals($user->id, $appointment->user->id);
    }
}
