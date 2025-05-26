<?php

namespace Tests\Unit\Models;

use App\Models\Patient;
use App\Models\PatientFile;
use App\Models\PatientRecord;
use App\Models\Payment;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    /**
     * The authenticated user instance.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User
     */
    protected $user;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->signIn($this->user);
    }


    /** @test */
    public function it_has_many_visits()
    {
        $patient = Patient::factory()->create();
        $visits = Visit::factory()->count(3)->create(['patient_id' => $patient->id]);

        $this->assertCount(3, $patient->visits);
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $patient->visits);
        $this->assertInstanceOf('\App\Models\Visit', $patient->visits->first());
    }

    /** @test */
    public function it_has_many_payments()
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
            'remaining_amount' => 50,
            'status' => 'pending'
        ]);
        
        $this->assertCount(1, $patient->payments);
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $patient->payments);
        $this->assertInstanceOf('App\Models\Payment', $patient->payments->first());
        $this->assertEquals($payment->id, $patient->payments->first()->id);
    }

    /** @test */
    public function it_has_many_files()
    {
        $patient = Patient::factory()->create();
        $files = PatientFile::factory()->count(2)->create(['patient_id' => $patient->id]);

        $this->assertCount(2, $patient->files);
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $patient->files);
        $this->assertInstanceOf('\App\Models\PatientFile', $patient->files->first());
    }

    /** @test */
    public function it_has_many_records()
    {
        $patient = Patient::factory()->create();
        $records = PatientRecord::factory()->count(3)->create(['patient_id' => $patient->id]);

        $this->assertCount(3, $patient->records);
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $patient->records);
        $this->assertInstanceOf('\App\Models\PatientRecord', $patient->records->first());
    }

    /** @test */
    public function it_has_many_medications()
    {
        $patient = Patient::factory()->create();
        
        // Attach medications to patient
        $patient->medications()->attach(
            \App\Models\Medication::factory()->create()->id,
            [
                'dosage' => '500mg',
                'frequency' => 'twice daily',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
            ]
        );

        $this->assertCount(1, $patient->medications);
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $patient->medications);
        $this->assertInstanceOf('\App\Models\Medication', $patient->medications->first());
        $this->assertEquals('500mg', $patient->medications->first()->pivot->dosage);
    }

    /** @test */
    public function it_has_many_affected_teeth_through_patient_records()
    {
        $patient = Patient::factory()->create();
        $record = PatientRecord::factory()->create(['patient_id' => $patient->id]);
        
        // Create teeth and attach to the record using the correct relationship method
        $tooth1 = \App\Models\Tooth::factory()->create();
        $tooth2 = \App\Models\Tooth::factory()->create();
        
        $record->affectedTeeth()->attach([
            $tooth1->id => ['description' => 'Cavity'],
            $tooth2->id => ['description' => 'Filling']
        ]);

        // Refresh to ensure relationships are loaded
        $patient->load('affectedTeeth');
        
        $this->assertCount(2, $patient->affectedTeeth);
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $patient->affectedTeeth);
    }

    /** @test */
    public function it_has_symptoms_through_records()
    {
        $patient = Patient::factory()->create();
        $recordWithSymptoms = PatientRecord::factory()->create([
            'patient_id' => $patient->id,
            'symptoms' => 'Tooth pain and sensitivity'
        ]);
        $recordWithoutSymptoms = PatientRecord::factory()->create([
            'patient_id' => $patient->id,
            'symptoms' => null
        ]);

        $this->assertCount(1, $patient->symptoms);
        $this->assertEquals('Tooth pain and sensitivity', $patient->symptoms->first()->symptoms);
    }

    /** @test */
    public function it_has_diagnosis_through_records()
    {
        $patient = Patient::factory()->create();
        $recordWithDiagnosis = PatientRecord::factory()->create([
            'patient_id' => $patient->id,
            'diagnosis' => 'Cavity in tooth #19'
        ]);
        $recordWithoutDiagnosis = PatientRecord::factory()->create([
            'patient_id' => $patient->id,
            'diagnosis' => null
        ]);

        $this->assertCount(1, $patient->diagnosis);
        $this->assertEquals('Cavity in tooth #19', $patient->diagnosis->first()->diagnosis);
    }

    /** @test */
    public function it_has_last_visit()
    {
        $patient = Patient::factory()->create();
        
        // Create visits with different dates
        $olderVisits = Visit::factory()->count(3)->create([
            'patient_id' => $patient->id,
            'date' => now()->subDays(2)
        ]);
        
        $lastVisit = Visit::factory()->create([
            'patient_id' => $patient->id,
            'date' => now()
        ]);
        
        // Refresh to ensure we get the latest data
        $patient->refresh();
        
        // Get the most recent visit directly from the database
        $expectedLastVisit = $patient->visits()->latest('date')->first();
        
        $this->assertNotNull($patient->lastVisit);
        $this->assertEquals($expectedLastVisit->id, $patient->lastVisit->id);
    }

    /** @test */
    public function it_soft_deletes_and_moves_to_deleted_patients()
    {
        // Create a user to associate with the patient
        $user = \App\Models\User::factory()->create();
        
        // Create a patient with related records
        $patient = Patient::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Patient',
            'file_number' => 'TEST123'
        ]);
        
        $visit = \App\Models\Visit::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $user->id
        ]);
        
        $payment = \App\Models\Payment::factory()->create([
            'patient_id' => $patient->id,
            'visit_id' => $visit->id,
            'user_id' => $user->id
        ]);
        
        $file = \App\Models\PatientFile::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $user->id
        ]);
        
        // Ensure the patient has the SoftDeletes trait
        $this->assertTrue(method_exists($patient, 'bootSoftDeletes'));
        
        // Ensure the DeletedPatient model is used
        $this->assertTrue(class_exists(\App\Models\DeletedPatient::class));
        
        // Soft delete the patient
        $patient->delete();
        
        // Refresh the patient to get the soft deleted status
        $patient->refresh();
        
        // Assert the patient is soft deleted
        $this->assertSoftDeleted($patient);
        
        // Check if the patient was moved to deleted_patients table
        $this->assertDatabaseHas('deleted_patients', [
            'id' => $patient->id,
            'name' => $patient->name,
            'file_number' => $patient->file_number
        ]);
        
        // Check related records are soft deleted
        $this->assertSoftDeleted($visit);
        $this->assertSoftDeleted($payment);
        $this->assertSoftDeleted($file);
    }
}
