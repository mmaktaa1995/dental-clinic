<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Patient;
use Database\Factories\PatientFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PatientsControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * The authenticated user instance.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear all database tables
        $this->artisan('migrate:fresh');
        
        // Create roles if they don't exist
        if (!Role::where('slug', 'admin')->exists()) {
            Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrator']);
        }
        if (!Role::where('slug', 'dentist')->exists()) {
            Role::create(['name' => 'Dentist', 'slug' => 'dentist', 'description' => 'Dentist']);
        }
        
        // Create and authenticate a test user with admin role
        $this->user = User::factory()->create([
            'email_verified_at' => now(), // Mark the user as verified
        ]);
        $this->user->assignRole('admin');
        
        // Create a personal access token for the user
        $token = $this->user->createToken('test-token')->plainTextToken;
        
        // Set the authorization header for all requests
        $this->withHeader('Authorization', 'Bearer ' . $token);
        
        // Verify the role was assigned correctly
        $adminRole = Role::where('slug', 'admin')->first();
        $roleUser = DB::table('role_user')->where('user_id', $this->user->id)->where('role_id', $adminRole->id)->first();
        
        if (!$roleUser) {
            throw new \Exception('Failed to assign admin role to test user');
        }
    }

    /** @test */
    public function it_can_create_a_patient()
    {
        $patientData = [
            'name' => 'John Doe',
            'age' => 30,
            'gender' => 1, // 1 for male, 2 for female
            'phone' => '1234567890',
            'mobile' => '0987654321',
            'file_number' => 'TEST123',
            'total_amount' => 1000
        ];

        $response = $this->postJson('/api/v1/patients/create', $patientData);
        
        // Dump the response for debugging
        dump($response->getContent());
        
        $response->assertStatus(200);
        
        // Check if the patient was created in the database
        $this->assertDatabaseHas('patients', [
            'name' => 'John Doe',
            'file_number' => 'TEST123',
            'user_id' => $this->user->id,
            'gender' => 1,
            'age' => 30
        ]);
        
        // Check the response structure
        $response->assertJsonStructure([
            'message',
            'patient' => [
                'id'
            ]
        ]);
        
        // Verify the success message
        $response->assertJson([
            'message' => 'تمت العملية بنجاح'
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/v1/patients/create', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_prevents_duplicate_patient_names()
    {
        // Get initial count of patients in the database
        $initialCount = Patient::count();
        
        // Create a patient with a specific name
        $patient = Patient::create([
            'user_id' => $this->user->id,
            'name' => 'John Doe Unique Test',
            'file_number' => 'TEST123',
            'gender' => 1,
            'age' => 30
        ]);

        // Verify the patient was created
        $this->assertDatabaseHas('patients', ['name' => 'John Doe Unique Test']);
        $this->assertEquals($initialCount + 1, Patient::count());

        // Try to create another patient with the same name
        $response = $this->postJson('/api/v1/patients/create', [
            'name' => 'John Doe Unique Test',
            'age' => 30,
            'gender' => 1,
            'file_number' => 'TEST124',
            'phone' => '1234567890',
            'mobile' => '0987654321',
            'total_amount' => 1000
        ]);
        
        // Expect validation error for duplicate name
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        
        // Verify no additional patient was created
        $this->assertEquals($initialCount + 1, Patient::count());
    }

    /** @test */
    public function it_can_retrieve_a_patient()
    {
        $patient = Patient::create([
            'user_id' => $this->user->id,
            'name' => 'John Doe',
            'file_number' => 'TEST123',
            'gender' => 1,
            'age' => 30
        ]);

        $response = $this->getJson("/api/v1/patients/{$patient->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $patient->id,
            'name' => $patient->name,
            'file_number' => $patient->file_number,
            'gender' => $patient->gender,
            'age' => (string)$patient->age,
        ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_patient()
    {
        $response = $this->getJson('/api/v1/patients/9999');
        $response->assertStatus(404);
    }
    
    /** @test */
    public function it_requires_authentication()
    {
        // Create a fresh test instance without authentication
        $this->refreshApplication();
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson('/api/v1/patients/1');
        
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }
}
