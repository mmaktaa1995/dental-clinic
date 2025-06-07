<?php

namespace Tests\Unit\Services;

use App\Http\Requests\PaymentRequest;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use App\Models\Visit;
use App\Models\Tooth;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private PaymentService $paymentService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paymentService = new PaymentService();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function createsAPaymentWithVisit()
    {
        // Create a patient for testing with unique name
        $patient = Patient::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Payment Patient ' . uniqid(),
            'phone' => '1234567890',
        ]);

        // Create a mock request with validated data
        /** @var PaymentRequest|MockObject $request */
        $request = $this->getMockBuilder(PaymentRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $validatedData = [
            'patient_id' => $patient->id,
            'amount' => 100.00,
            'remaining_amount' => 100.00,
            'date' => now(),
            'notes' => 'Test payment',
            'user_id' => $this->user->id,
        ];

        // Set up the validated method
        $request->expects($this->any())
            ->method('validated')
            ->willReturn($validatedData);

        // Set up the get method
        $request->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($key) use ($validatedData) {
                return $validatedData[$key] ?? null;
            });

        // Set up the only method
        $request->expects($this->any())
            ->method('only')
            ->willReturnCallback(function ($keys) use ($validatedData) {
                return array_intersect_key(
                    $validatedData,
                    array_flip((array)$keys)
                );
            });

        // Call the service method
        $this->paymentService->createPayment($request);

        // Get the created visit
        $visit = Visit::where('patient_id', $patient->id)
            ->where('user_id', $this->user->id)
            ->where('notes', 'Test payment')
            ->first();

        $this->assertNotNull($visit, 'Visit was not created');

        // Verify the payment was created with the correct data
        $payment = Payment::where('visit_id', $visit->id)->first();
        $this->assertNotNull($payment, 'Payment was not created for the visit');

        $this->assertEquals(100.00, $payment->amount, 'Payment amount does not match');
        $this->assertEquals(100.00, $payment->remaining_amount, 'Remaining amount does not match');
        $this->assertEquals($patient->id, $payment->patient_id, 'Patient ID does not match');
        $this->assertEquals($this->user->id, $payment->user_id, 'User ID does not match');
    }

    /** @test */
    public function updatesRemainingAmountWhenCreatingPayment()
    {
        // Create a patient for testing with unique name
        $patient = Patient::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Payment Patient ' . uniqid(),
            'phone' => '1234567890',
        ]);

        // Create a visit for the initial payment
        $visit = Visit::create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'notes' => 'Initial payment',
        ]);

        // Create an initial payment with remaining amount
        $initialPayment = $visit->payment()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'amount' => 200.00,
            'remaining_amount' => 200.00,
            'date' => now(),
        ]);

        // Create a mock request with validated data
        /** @var PaymentRequest|MockObject $request */
        $request = $this->getMockBuilder(PaymentRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $validatedData = [
            'patient_id' => $patient->id,
            'payment_id' => $initialPayment->id,
            'amount' => 100.00,
            'remaining_amount' => 100.00,
            'date' => now(),
            'notes' => 'Partial payment',
            'user_id' => $this->user->id,
        ];

        // Set up the validated method
        $request->expects($this->any())
            ->method('validated')
            ->willReturn($validatedData);

        // Set up the get method
        $request->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($key) use ($validatedData) {
                return $validatedData[$key] ?? null;
            });

        // Set up the only method
        $request->expects($this->any())
            ->method('only')
            ->willReturnCallback(function ($keys) use ($validatedData) {
                return array_intersect_key(
                    $validatedData,
                    array_flip((array)$keys)
                );
            });

        // Call the service method
        $this->paymentService->createPayment($request);

        // Refresh the initial payment to get the latest data
        $initialPayment->refresh();

        // Check that the initial payment's remaining amount was reduced
        $this->assertEquals(100.00, $initialPayment->remaining_amount);

        // Check that the new payment was created with the visit
        $this->assertDatabaseHas('payments', [
            'patient_id' => $patient->id,
            'amount' => 100.00,
            'remaining_amount' => 100.00,
        ]);

        // Verify that there are now two payments
        $payments = Payment::where('patient_id', $patient->id)->get();
        $this->assertCount(2, $payments);

        // Verify the total remaining amount is correct
        $totalRemaining = $payments->sum('remaining_amount');
        $this->assertEquals(200.00, $totalRemaining);
    }

    /** @test */
    public function updatesAPayment()
    {
        // Create a patient for testing
        $patient = Patient::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Update Payment Patient ' . uniqid(),
            'phone' => '1234567890',
        ]);

        // Create a visit and payment
        $visit = Visit::create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'notes' => 'Initial visit',
        ]);

        $payment = $visit->payment()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'amount' => 100.00,
            'remaining_amount' => 100.00,
            'date' => now(),
        ]);

        // Create a mock request with updated data
        /** @var PaymentRequest|MockObject $request */
        $request = $this->getMockBuilder(PaymentRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $updateDate = now()->addDay();
        $updatedData = [
            'amount' => 150.00,
            'remaining_amount' => 50.00,
            'date' => $updateDate->format('Y-m-d H:i:s'),
            'notes' => 'Updated payment',
            'patient_id' => $patient->id,
        ];

        // Set up the get method
        $request->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($key) use ($updatedData, $patient) {
                if ($key === 'patient_id') {
                    return $patient->id;
                }
                if ($key === 'date') {
                    return $updatedData['date'];
                }
                if ($key === 'notes') {
                    return $updatedData['notes'];
                }
                if ($key === 'teeth_ids') {
                    return [];
                }
                return null;
            });

        // Set up the only method
        $request->expects($this->any())
            ->method('only')
            ->willReturnCallback(function ($keys) use ($updatedData) {
                return array_intersect_key(
                    $updatedData,
                    array_flip((array)$keys)
                );
            });

        // Call the service method
        $this->paymentService->updatePayment($request, $payment);

        // Refresh the payment and visit from the database
        $payment->refresh();
        $visit->refresh();

        // Assert the payment was updated
        $this->assertEquals(150.00, $payment->amount);
        $this->assertEquals(50.00, $payment->remaining_amount);

        // Assert the visit was updated
        $expectedDate = $updateDate->format('Y-m-d');
        $actualDate = $visit->date instanceof \Carbon\Carbon
            ? $visit->date->format('Y-m-d')
            : (new \Carbon\Carbon($visit->date))->format('Y-m-d');

        $this->assertEquals($expectedDate, $actualDate, 'The visit date does not match the expected date');
        $this->assertEquals('Updated payment', $visit->notes);
    }

    /** @test */
    public function deletesAPaymentAndItsVisit()
    {
        // Create a patient for testing
        $patient = Patient::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Delete Payment Patient ' . uniqid(),
            'phone' => '1234567890',
        ]);

        // Create a visit and payment
        $visit = Visit::create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'notes' => 'Visit to be deleted',
        ]);

        $payment = $visit->payment()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'amount' => 100.00,
            'remaining_amount' => 100.00,
            'date' => now(),
        ]);

        // Call the service method
        $this->paymentService->deletePayment($payment);

        // Assert the payment was soft-deleted
        $this->assertSoftDeleted('payments', ['id' => $payment->id]);

        // Assert the visit was soft-deleted
        $this->assertSoftDeleted('visits', ['id' => $visit->id]);
    }

    /** @test */
    public function restoresADeletedPaymentAndItsVisit()
    {
        // Create a patient for testing
        $patient = Patient::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Restore Payment Patient ' . uniqid(),
            'phone' => '1234567890',
        ]);

        // Create a visit and payment and soft delete them
        $visit = Visit::create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'notes' => 'Visit to be restored',
        ]);

        $payment = $visit->payment()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'amount' => 100.00,
            'remaining_amount' => 100.00,
            'date' => now(),
        ]);

        // Verify the payment and visit exist
        $this->assertDatabaseHas('payments', ['id' => $payment->id]);
        $this->assertDatabaseHas('visits', ['id' => $visit->id]);

        // Soft delete the payment and visit
        $payment->delete();
        $visit->delete();

        // Verify they were soft deleted
        $this->assertSoftDeleted('payments', ['id' => $payment->id]);
        $this->assertSoftDeleted('visits', ['id' => $visit->id]);

        // Call the service method
        $this->paymentService->restorePayment($payment);

        // Refresh the payment and visit from the database
        $payment->refresh();
        $visit->refresh();

        // Assert the payment was restored
        $this->assertFalse($payment->trashed());
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'deleted_at' => null
        ]);

        // Assert the visit was restored
        $this->assertFalse($visit->trashed());
        $this->assertDatabaseHas('visits', [
            'id' => $visit->id,
            'deleted_at' => null
        ]);

        // Verify the payment and visit are still linked
        $this->assertEquals($payment->visit_id, $visit->id);
        $this->assertEquals($payment->patient_id, $patient->id);
        $this->assertEquals($visit->patient_id, $patient->id);
    }

    /** @test */
    public function updatesTeethTreatmentStatusWhenUpdatingPayment()
    {
        // Create a patient for testing with unique name
        $patient = Patient::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Update Teeth Treatment Patient ' . uniqid(),
            'phone' => '1234567890',
        ]);

        // Create a patient record
        $patientRecord = $patient->records()->create([
            'symptoms' => 'Test symptoms',
            'diagnosis' => 'Test diagnosis',
            'record_date' => now(),
        ]);

        // Create some teeth in the database
        $teethIds = [21, 22, 23];
        $teeth = [];
        foreach ($teethIds as $toothId) {
            $teeth[] = Tooth::create([
                'name' => 'Tooth ' . $toothId,
                'number' => $toothId,
                'extra' => null
            ]);
        }

        // Attach teeth to the patient record with is_treated = false
        $teethData = [];
        foreach ($teeth as $tooth) {
            $teethData[$tooth->id] = [
                'is_treated' => false,
                'description' => 'Test description for tooth ' . $tooth->number
            ];
        }
        $patientRecord->affectedTeeth()->attach($teethData);

        // Create a visit and payment
        $visit = Visit::create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'notes' => 'Initial visit for teeth treatment',
        ]);

        $payment = $visit->payment()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'amount' => 100.00,
            'remaining_amount' => 100.00,
            'date' => now(),
        ]);

        // Create a mock request with updated data including teeth_ids
        /** @var PaymentRequest|MockObject $request */
        $request = $this->getMockBuilder(PaymentRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $updateDate = now()->addDay();
        $updatedData = [
            'amount' => 150.00,
            'remaining_amount' => 50.00,
            'date' => $updateDate->format('Y-m-d H:i:s'),
            'notes' => 'Updated payment with teeth treatment',
            'patient_id' => $patient->id,
            'teeth_ids' => [21, 23], // Only mark these two teeth as treated
        ];

        // Set up the get method
        $request->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($key) use ($updatedData, $patient) {
                if ($key === 'patient_id') {
                    return $patient->id;
                }
                if ($key === 'date') {
                    return $updatedData['date'];
                }
                if ($key === 'notes') {
                    return $updatedData['notes'];
                }
                if ($key === 'teeth_ids') {
                    return $updatedData['teeth_ids'];
                }
                return null;
            });

        // Set up the only method
        $request->expects($this->any())
            ->method('only')
            ->willReturnCallback(function ($keys) use ($updatedData) {
                return array_intersect_key(
                    $updatedData,
                    array_flip((array)$keys)
                );
            });

        // Call the service method
        $this->paymentService->updatePayment($request, $payment);

        // Refresh the payment and visit
        $payment->refresh();
        $visit->refresh();
        $patientRecord->refresh();

        // Get the updated teeth status
        $affectedTeeth = $patientRecord->affectedTeeth()->get();

        // Verify the correct teeth were marked as treated
        foreach ($affectedTeeth as $tooth) {
            $shouldBeTreated = in_array($tooth->number, [21, 23]);
            $this->assertEquals(
                $shouldBeTreated,
                (bool)$tooth->pivot->is_treated,
                "Tooth {$tooth->number} should " . ($shouldBeTreated ? 'be treated' : 'not be treated')
            );
        }

        // Also verify the payment was updated
        $this->assertEquals(150.00, $payment->amount);
        $this->assertEquals(50.00, $payment->remaining_amount);
    }

    /** @test */
    public function updatesTeethTreatmentStatusWhenCreatingPayment()
    {
        // Create a patient for testing with unique name
        $patient = Patient::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Teeth Treatment Patient ' . uniqid(),
            'phone' => '1234567890',
        ]);

        // Create a patient record
        $patientRecord = $patient->records()->create([
            'symptoms' => 'Test symptoms',
            'diagnosis' => 'Test diagnosis',
            'record_date' => now(),
        ]);

        // Create some teeth in the database
        $teethIds = [11, 12, 13];
        $teeth = [];
        foreach ($teethIds as $toothId) {
            $teeth[] = Tooth::create([
                'name' => 'Tooth ' . $toothId,
                'number' => $toothId,
                'extra' => null
            ]);
        }

        // Attach teeth to the patient record with is_treated = false
        $teethData = [];
        foreach ($teeth as $tooth) {
            $teethData[$tooth->id] = [
                'is_treated' => false,
                'description' => 'Test description for tooth ' . $tooth->number
            ];
        }
        $patientRecord->affectedTeeth()->attach($teethData);

        // Store the patient record ID to be used in the request
        $patientRecordId = $patientRecord->id;

        // Create a mock request with validated data including teeth_ids
        /** @var PaymentRequest|MockObject $request */
        $request = $this->getMockBuilder(PaymentRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $validatedData = [
            'patient_id' => $patient->id,
            'amount' => 100.00,
            'remaining_amount' => 100.00,
            'date' => now(),
            'notes' => 'Payment with teeth treatment',
            'user_id' => $this->user->id,
            'teeth_ids' => [11, 13], // Only mark these two teeth as treated
            'patient_record_id' => $patientRecordId, // Add patient record ID to the request
        ];

        // Set up the validated method
        $request->expects($this->any())
            ->method('validated')
            ->willReturn($validatedData);

        // Set up the get method
        $request->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($key) use ($validatedData) {
                if ($key === 'teeth_ids') {
                    return $validatedData['teeth_ids'];
                }
                if ($key === 'patient_id') {
                    return $validatedData['patient_id'];
                }
                return $validatedData[$key] ?? null;
            });

        // Set up the only method
        $request->expects($this->any())
            ->method('only')
            ->willReturnCallback(function ($keys) use ($validatedData) {
                return array_intersect_key(
                    $validatedData,
                    array_flip((array)$keys)
                );
            });

        // Debug: Check the state of teeth before the payment
        $beforeTeeth = $patientRecord->affectedTeeth()->get();
        echo "\n\n=== TEETH STATE BEFORE PAYMENT ===\n";
        foreach ($beforeTeeth as $tooth) {
            echo "Tooth {$tooth->number}: is_treated = " . ($tooth->pivot->is_treated ? 'true' : 'false') . "\n";
        }

        // Call the service method
        $this->paymentService->createPayment($request);

        // Debug: Check the state of teeth after the payment
        $patientRecord->refresh();
        $affectedTeeth = $patientRecord->affectedTeeth()->get();

        echo "\n=== TEETH STATE AFTER PAYMENT ===\n";
        foreach ($affectedTeeth as $tooth) {
            echo "Tooth {$tooth->number}: is_treated = " . ($tooth->pivot->is_treated ? 'true' : 'false') . "\n";
        }

        // Get the tooth numbers that should be treated
        $treatedTeethNumbers = [11, 13];

        // Verify the correct teeth were marked as treated
        foreach ($affectedTeeth as $tooth) {
            $shouldBeTreated = in_array($tooth->number, $treatedTeethNumbers);
            $this->assertEquals(
                $shouldBeTreated,
                (bool)$tooth->pivot->is_treated,
                "Tooth {$tooth->number} should " . ($shouldBeTreated ? 'be treated' : 'not be treated')
            );
        }
    }
}
