<?php

namespace Tests\Unit\Models;

use App\Models\Patient;
use App\Models\PatientRecord;
use App\Models\Tooth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientRecordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_patient_record()
    {
        $patient = Patient::factory()->create();
        
        $record = PatientRecord::create([
            'patient_id' => $patient->id,
            'symptoms' => 'Tooth pain and sensitivity',
            'diagnosis' => 'Cavity in tooth #36',
            'record_date' => now()
        ]);

        $this->assertDatabaseHas('patient_records', [
            'patient_id' => $patient->id,
            'symptoms' => 'Tooth pain and sensitivity',
            'diagnosis' => 'Cavity in tooth #36',
        ]);
    }

    /** @test */
    public function it_belongs_to_a_patient()
    {
        $patient = Patient::factory()->create();
        $record = PatientRecord::factory()->create(['patient_id' => $patient->id]);

        $this->assertInstanceOf(Patient::class, $record->patient);
        $this->assertEquals($patient->id, $record->patient->id);
    }

    /** @test */
    public function it_can_have_affected_teeth()
    {
        $record = PatientRecord::factory()->create();
        $tooth1 = Tooth::factory()->create(['number' => 16]);
        $tooth2 = Tooth::factory()->create(['number' => 36]);

        $record->affectedTeeth()->attach([
            $tooth1->id => ['description' => 'Cavity', 'is_treated' => false],
            $tooth2->id => ['description' => 'Filling needed', 'is_treated' => true],
        ]);

        $this->assertCount(2, $record->affectedTeeth);
        $this->assertTrue($record->affectedTeeth->contains($tooth1));
        $this->assertTrue($record->affectedTeeth->contains($tooth2));
        
        // Verify pivot data
        $this->assertEquals('Cavity', $record->affectedTeeth->find($tooth1->id)->pivot->description);
        $this->assertFalse((bool)$record->affectedTeeth->find($tooth1->id)->pivot->is_treated);
        $this->assertEquals('Filling needed', $record->affectedTeeth->find($tooth2->id)->pivot->description);
        $this->assertTrue((bool)$record->affectedTeeth->find($tooth2->id)->pivot->is_treated);
    }

    /** @test */
    public function it_casts_record_date_to_timestamp()
    {
        $record = PatientRecord::factory()->create([
            'record_date' => '2023-01-01 10:00:00'
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $record->record_date);
        $this->assertEquals('2023-01-01 10:00:00', $record->record_date->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function it_can_scope_records_for_specific_patient()
    {
        $patient1 = Patient::factory()->create();
        $patient2 = Patient::factory()->create();
        
        $record1 = PatientRecord::factory()->create(['patient_id' => $patient1->id]);
        $record2 = PatientRecord::factory()->create(['patient_id' => $patient1->id]);
        $record3 = PatientRecord::factory()->create(['patient_id' => $patient2->id]);

        $patient1Records = PatientRecord::where('patient_id', $patient1->id)->get();
        
        $this->assertCount(2, $patient1Records);
        $this->assertTrue($patient1Records->contains($record1));
        $this->assertTrue($patient1Records->contains($record2));
        $this->assertFalse($patient1Records->contains($record3));
    }
}
