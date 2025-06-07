<?php

namespace Tests\Unit\Models;

use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServicePayment;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
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
    public function belongsToaVisit()
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

        $payment->load('visit');

        $this->assertInstanceOf(Visit::class, $payment->visit);
        $this->assertEquals($visit->id, $payment->visit->id);
    }

    /** @test */
    public function belongsToaPatient()
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

        $payment->load('patient');

        $this->assertInstanceOf(Patient::class, $payment->patient);
        $this->assertEquals($patient->id, $payment->patient->id);
    }

    /** @test */
    public function belongsTomanyServices()
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
            'remaining_amount' => 100,
            'status' => 'pending'
        ]);

        $service1 = Service::create([
            'name' => 'Cleaning',
            'price' => 50,
            'user_id' => $this->user->id
        ]);

        $service2 = Service::create([
            'name' => 'Checkup',
            'price' => 75,
            'user_id' => $this->user->id
        ]);

        // Attach services to payment through the pivot table
        $payment->services()->attach([
            $service1->id => ['price' => 50],
            $service2->id => ['price' => 75],
        ]);

        // Refresh the payment to load the services
        $payment->refresh();

        // Get the services through the relationship
        $services = $payment->services()->get();

        // Assertions
        $this->assertCount(2, $services);
        $this->assertInstanceOf(Service::class, $services->first());
        $this->assertEqualsCanonicalizing(
            ['Checkup', 'Cleaning'],
            $services->pluck('name')->toArray()
        );
    }

    /** @test */
    public function hasManyServicePayments()
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
            'remaining_amount' => 100,
            'status' => 'pending'
        ]);

        // Create services
        $service1 = Service::factory()->create(['user_id' => $this->user->id]);
        $service2 = Service::factory()->create(['user_id' => $this->user->id]);

        // Create service payments through the pivot table
        $payment->services()->attach([
            $service1->id => [
                'price' => 50,
                'notes' => 'Test service 1'
            ],
            $service2->id => [
                'price' => 75,
                'notes' => 'Test service 2'
            ]
        ]);

        // Refresh the payment to load the service payments
        $payment->refresh();

        // Get the service payments through the relationship
        $servicePayments = $payment->services()->withPivot('price', 'notes')->get();

        // Debug output
        echo "\nPayment ID: " . $payment->id . "\n";
        echo "Attached Services: " . $servicePayments->count() . "\n";
        foreach ($servicePayments as $service) {
            echo "- Service ID: " . $service->id . ", Price: " . $service->pivot->price . "\n";
        }

        // Assertions
        $this->assertCount(2, $servicePayments, 'Expected 2 services but found ' . $servicePayments->count());
        $this->assertInstanceOf(Service::class, $servicePayments->first());

        // Check the pivot data
        $prices = $servicePayments->pluck('pivot.price')->sort()->values()->toArray();
        $this->assertEqualsCanonicalizing(
            [50, 75],
            $prices,
            'Service payment amounts do not match expected values. Got: ' . implode(', ', $prices)
        );
    }

    /** @test */
    public function hasChildren()
    {
        $patient = Patient::factory()->create(['user_id' => $this->user->id]);

        // Create parent payment with its own visit
        $parentVisit = Visit::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        $parentPayment = Payment::create([
            'patient_id' => $patient->id,
            'visit_id' => $parentVisit->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 100,
            'remaining_amount' => 100,
            'status' => 'pending'
        ]);

        // Create first child payment with its own visit
        $childVisit1 = Visit::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        $childPayment1 = new Payment([
            'patient_id' => $patient->id,
            'visit_id' => $childVisit1->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 50,
            'remaining_amount' => 50,
            'status' => 'pending'
        ]);

        // Associate with parent using the relationship
        $parentPayment->children()->save($childPayment1);

        // Create second child payment with its own visit
        $childVisit2 = Visit::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        $childPayment2 = new Payment([
            'patient_id' => $patient->id,
            'visit_id' => $childVisit2->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 25,
            'remaining_amount' => 25,
            'status' => 'pending'
        ]);

        // Associate with parent using the relationship
        $parentPayment->children()->save($childPayment2);

        // Refresh the parent payment to ensure we have the latest data
        $parentPayment->refresh();
        $childPayment1->refresh();
        $childPayment2->refresh();

        // Get the children using the relationship
        $children = $parentPayment->children()->get();

        // Debug: Log the parent and children data
        echo "\nParent Payment ID: " . $parentPayment->id . "\n";
        echo "Child Payments in DB: \n";
        $allPayments = \App\Models\Payment::all();
        foreach ($allPayments as $p) {
            echo "- ID: " . $p->id . ", Parent ID: " . $p->parent_id . ", Amount: " . $p->amount . "\n";
        }

        // Assertions
        $this->assertCount(2, $children, 'Expected 2 child payments but found ' . $children->count());
        $this->assertInstanceOf(Payment::class, $children->first());
        $this->assertEqualsCanonicalizing(
            [25, 50],
            $children->pluck('amount')->sort()->values()->toArray(),
            'Child payment amounts do not match expected values'
        );
    }

    /** @test */
    public function belongsToparent()
    {
        $patient = Patient::factory()->create(['user_id' => $this->user->id]);

        // Create parent payment with its own visit
        $parentVisit = Visit::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        $parentPayment = Payment::create([
            'patient_id' => $patient->id,
            'visit_id' => $parentVisit->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 100,
            'remaining_amount' => 100,
            'status' => 'pending'
        ]);

        // Create child payment with its own visit
        $childVisit = Visit::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'date' => now()
        ]);

        // Create child payment using the parent's children relationship
        $childPayment = new Payment([
            'patient_id' => $patient->id,
            'visit_id' => $childVisit->id,
            'user_id' => $this->user->id,
            'date' => now(),
            'amount' => 50,
            'remaining_amount' => 50,
            'status' => 'pending'
        ]);

        // Save the child payment through the parent's children relationship
        $parentPayment->children()->save($childPayment);

        // Refresh both payments to ensure we have the latest data
        $parentPayment->refresh();
        $childPayment->refresh();

        // Get the parent using the relationship
        $parent = $childPayment->parent;

        // Debug output
        echo "\nChild Payment ID: " . $childPayment->id . "\n";
        echo "Child Payment Parent ID: " . $childPayment->parent_id . "\n";
        echo "Parent Payment ID: " . $parentPayment->id . "\n";

        // Assertions
        $this->assertInstanceOf(Payment::class, $parent, 'Parent should be an instance of Payment');
        $this->assertNotNull($parent, 'Parent should not be null');
        $this->assertEquals($parentPayment->id, $parent->id, 'Parent ID does not match');
        $this->assertEquals(100, $parent->amount, 'Parent amount does not match');
    }

    /** @test */
    public function formatsDateAttribute()
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
            'date' => '2023-06-15 14:30:00',
            'amount' => 100,
            'remaining_amount' => 0,
            'status' => 'paid'
        ]);

        $this->assertEquals('2023-06-15', $payment->date);
    }
}
