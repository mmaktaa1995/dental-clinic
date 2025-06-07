<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Tooth;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $patient;
    protected $service;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a test user with Sanctum token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;

        // Create a test patient
        $this->patient = Patient::create([
            'user_id' => $this->user->id,
            'name' => 'John Doe',
            'file_number' => 'TEST123',
            'gender' => 1,
            'age' => 30
        ]);

        // Create a test service
        $this->service = Service::create([
            'name' => 'Dental Checkup',
            'price' => 150.00,
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function canListPayments()
    {
        // Create test payments
        $visit = Visit::create([
            'patient_id' => $this->patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        $payment = Payment::create([
            'patient_id' => $this->patient->id,
            'visit_id' => $visit->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 15000, // Stored in cents
            'remaining_amount' => 0,
            'status' => 1 // 1 = paid, 0 = unpaid, 2 = partial
        ]);

        // Need to include required parameters for pagination
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/payments', [
            'patient_id' => $this->patient->id,
            'date' => now()->format('Y-m-d'),
            'per_page' => 10,
            'page' => 1
        ]);

        // Debug the response
        if ($response->status() !== 200) {
            dump('List payments error:', $response->json());
        }

        $response->assertStatus(200);

        // Check if the response has the expected structure
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertArrayHasKey('entries', $responseData);
        $this->assertIsArray($responseData['entries']);

        if (count($responseData['entries']) > 0) {
            $firstPayment = $responseData['entries'][0];
            $this->assertArrayHasKey('id', $firstPayment);
            $this->assertArrayHasKey('patient_id', $firstPayment);
            $this->assertArrayHasKey('amount', $firstPayment);
        }
    }

    /** @test */
    public function canCreateAPayment()
    {
        $paymentData = [
            'patient_id' => $this->patient->id,
            'date' => now()->format('Y-m-d'),
            'amount' => 150.00,
            'notes' => 'Test payment',
            'status' => 1, // 1 = paid
            'user_id' => $this->user->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/payments/create', $paymentData);

        // Debug the response
        if ($response->status() !== 200) {
            dump('Create payment error:', $response->json());
        }

        $response->assertStatus(200);

        // Debug: Show the API response
        $responseData = $response->json();
        dump('API Response:', $responseData);

        // Check if the payment was created in the database with the correct data
        $this->assertDatabaseHas('payments', [
            'patient_id' => $this->patient->id,
            'amount' => 150, // Amount is stored as is
            'status' => 1 // 1 = paid
        ]);

        // Get the created payment from the database
        $payment = \App\Models\Payment::where('patient_id', $this->patient->id)
            ->where('amount', 150)
            ->first();

        $this->assertNotNull($payment, 'Payment was not created in the database');

        // Check if a visit was created and associated with the payment
        $this->assertDatabaseHas('visits', [
            'patient_id' => $this->patient->id,
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function validatesRequiredFieldsWhenCreatingPayment()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/payments/create', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['patient_id', 'date']);
    }

    /** @test */
    public function canUpdateAPayment()
    {
        $visit = Visit::create([
            'patient_id' => $this->patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        $payment = Payment::create([
            'patient_id' => $this->patient->id,
            'visit_id' => $visit->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 15000, // In cents
            'remaining_amount' => 0,
            'status' => 1 // 1 = paid
        ]);

        $updateData = [
            'patient_id' => $this->patient->id,
            'date' => now()->format('Y-m-d'),
            'amount' => 200.00,
            'notes' => 'Updated payment'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->patchJson("/api/v1/payments/{$payment->id}", $updateData);

        // Debug the response
        if ($response->status() !== 200) {
            dump('Update payment error:', $response->json());
        }

        $response->assertStatus(200);

        // Check if the payment was updated in the database
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'amount' => 200,
            'remaining_amount' => 0,
            'status' => 1 // 1 = paid
        ]);
    }

    /** @test */
    public function canDeleteAPayment()
    {
        $visit = Visit::create([
            'patient_id' => $this->patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        $payment = Payment::create([
            'patient_id' => $this->patient->id,
            'visit_id' => $visit->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 15000, // In cents
            'remaining_amount' => 0,
            'status' => 1 // 1 = paid
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->deleteJson("/api/v1/payments/{$payment->id}");

        $response->assertStatus(200);

        // Check if the payment was soft deleted
        $this->assertSoftDeleted('payments', ['id' => $payment->id]);
    }
}
