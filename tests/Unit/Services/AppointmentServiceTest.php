<?php

namespace Tests\Unit\Services;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentServiceTest extends TestCase
{
    use RefreshDatabase;

    private AppointmentService $service;
    private User $user;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new AppointmentService();
        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
    }

    /** @test */
    public function it_gets_upcoming_appointments()
    {
        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'date' => now()->addDay(),
        ]);

        // Test with Carbon instance
        $appointments = $this->service->getUpcomingAppointments($this->user->id, now());
        $this->assertCount(1, $appointments);
        $this->assertEquals($appointment->id, $appointments->first()->id);

        // Test with string date
        $appointments = $this->service->getUpcomingAppointments($this->user->id, now()->format('Y-m-d'));
        $this->assertCount(1, $appointments);
        $this->assertEquals($appointment->id, $appointments->first()->id);

        // Test with end date
        $appointments = $this->service->getUpcomingAppointments(
            $this->user->id, 
            now()->subDay(), 
            now()->addDays(2)
        );
        $this->assertCount(1, $appointments);
    }

    /** @test */
    public function it_creates_an_appointment()
    {
        $data = [
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'date' => now()->addDay(),
            'notes' => 'Test appointment',
        ];

        $appointment = $this->service->createAppointment($data);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'notes' => 'Test appointment',
        ]);
    }

    /** @test */
    public function it_throws_exception_when_creating_conflicting_appointment()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(__('app.appointments_conflict'));

        $dateTime = now()->addDay();
        
        // Create initial appointment
        Appointment::factory()->create([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'date' => $dateTime,
        ]);

        // Try to create conflicting appointment with Carbon instance
        $this->service->createAppointment([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'date' => $dateTime,
        ]);
    }
    
    /** @test */
    public function it_throws_exception_when_creating_conflicting_appointment_with_string_date()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(__('app.appointments_conflict'));

        $dateTime = now()->addDay()->format('Y-m-d H:i:s');
        
        // Create initial appointment with string date
        Appointment::factory()->create([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'date' => $dateTime,
        ]);

        // Try to create conflicting appointment with string date
        $this->service->createAppointment([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'date' => $dateTime,
        ]);
    }

    /** @test */
    public function it_updates_an_appointment()
    {
        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'date' => now()->addDay(),
            'notes' => 'Old notes',
        ]);

        $newDate = now()->addDays(2);
        $this->service->updateAppointment($appointment, [
            'date' => $newDate->format('Y-m-d H:i:s'),
            'notes' => 'Updated notes',
        ]);

        $appointment->refresh();
        
        // Ensure the date was updated by checking the database directly
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'notes' => 'Updated notes',
        ]);
        
        // Convert the stored date to a Carbon instance and compare
        $storedDate = $appointment->fresh()->date;
        $this->assertTrue(
            $newDate->diffInMinutes($storedDate) < 1, // Allow for small time differences
            'The appointment date was not updated correctly'
        );
        $this->assertEquals('Updated notes', $appointment->notes);
    }

    /** @test */
    public function it_deletes_an_appointment()
    {
        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
        ]);

        $this->service->deleteAppointment($appointment);

        $this->assertDatabaseMissing('appointments', ['id' => $appointment->id]);
    }
}
