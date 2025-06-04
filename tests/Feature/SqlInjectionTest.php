<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SqlInjectionTest extends TestCase
{
    use RefreshDatabase;
    
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function test_it_prevents_sql_injection_in_search_queries()
    {
        // Create a test patient for this user
        $patient = Patient::create([
            'user_id' => $this->user->id,
            'name' => 'John Doe',
            'age' => '30',
            'gender' => 1, // 1 for male
            'file_number' => 123,
            'phone' => '1234567890',
            'mobile' => '0987654321',
            'total_amount' => 100,
        ]);

        // Test normal search works - Using the POST /api/v1/patients endpoint with search parameter
        $response = $this->postJson('/api/v1/patients', [
            'search' => 'John',
            'per_page' => 10,
            'page' => 1
        ]);
        
        $response->assertStatus(200);
        
        // Get the response data
        $responseData = $response->json();
        
        // Check if the response has the expected structure
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('entries', $responseData);
        $this->assertArrayHasKey('pagination', $responseData);
        $this->assertIsArray($responseData['entries']);
        
        // If there are patients in the response, check their structure
        if (!empty($responseData['entries'])) {
            $firstPatient = $responseData['entries'][0];
            $this->assertArrayHasKey('id', $firstPatient);
            $this->assertArrayHasKey('name', $firstPatient);
            $this->assertArrayHasKey('file_number', $firstPatient);
            $this->assertArrayHasKey('age', $firstPatient);
            $this->assertArrayHasKey('phone', $firstPatient);
            $this->assertArrayHasKey('mobile', $firstPatient);
            $this->assertArrayHasKey('gender', $firstPatient);
            $this->assertArrayHasKey('created_at', $firstPatient);
        }

        // Test SQL injection attempt in search query
        $response = $this->postJson('/api/v1/patients', [
            'search' => 'test\' OR \'1\'=\'1',
            'per_page' => 10,
            'page' => 1
        ]);
        
        // Should be blocked by our middleware with 422 status
        $response->assertStatus(422);
        
        // Verify the error response structure
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'input'
            ]
        ]);
    }

    /** @test */
    public function test_it_handles_special_characters_in_search_queries()
    {
        // Create test patients with various special characters in their names
        $patients = [
            ['name' => 'John-Doe', 'file_number' => 1, 'gender' => 1],
            ['name' => "O'Connor", 'file_number' => 2, 'gender' => 1],
            ['name' => 'Doe, John', 'file_number' => 3, 'gender' => 1],
            ['name' => 'John.Doe', 'file_number' => 4, 'gender' => 1],
            ['name' => 'John_Doe', 'file_number' => 5, 'gender' => 1],
        ];

        foreach ($patients as $patientData) {
            Patient::create(array_merge($patientData, [
                'user_id' => $this->user->id,
                'age' => '30',
                'phone' => '1234567890',
                'mobile' => '0987654321',
                'total_amount' => 100,
            ]));
        }

        // Test with safe special characters that should be allowed
        $testCases = [
            ['search' => 'John-Doe', 'expectedFileNumber' => 1],  // Hyphen in name
            ['search' => "O'Connor", 'expectedFileNumber' => 2],  // Single quote in name
            ['search' => 'Doe, John', 'expectedFileNumber' => 3], // Comma in name
            ['search' => 'John.Doe', 'expectedFileNumber' => 4],  // Period in name
            ['search' => 'John_Doe', 'expectedFileNumber' => 5],  // Underscore in name
            ['search' => '1', 'expectedFileNumber' => 1],         // Search by file number
        ];
        
        foreach ($testCases as $testCase) {
            $response = $this->postJson('/api/v1/patients', [
                'search' => $testCase['search'],
                'per_page' => 10,
                'page' => 1
            ]);
            
            $response->assertStatus(200);
            
            // Get the response data
            $responseData = $response->json();
            
            // Check if the response has the expected structure
            $this->assertIsArray($responseData);
            $this->assertArrayHasKey('entries', $responseData);
            $this->assertArrayHasKey('pagination', $responseData);
            
            // Verify that the expected patient is in the results
            if (!empty($testCase['expectedFileNumber'])) {
                $found = false;
                foreach ($responseData['entries'] as $entry) {
                    if ($entry['file_number'] == $testCase['expectedFileNumber']) {
                        $found = true;
                        break;
                    }
                }
                $this->assertTrue($found, "Expected patient with file number {$testCase['expectedFileNumber']} not found in search results for: {$testCase['search']}");
            }
        }
        
        // Test with potentially dangerous patterns that should be blocked
        $dangerousPatterns = [
            "' OR '1'='1",  // SQL injection attempt
            '" OR "1"="1\'', // SQL injection with double quotes
            '; DROP TABLE users; --', // SQL injection with semicolon
            '1; SELECT * FROM users', // Multiple statements
            'admin' . chr(0) . '--', // Null byte injection
        ];
        
        foreach ($dangerousPatterns as $pattern) {
            $response = $this->postJson('/api/v1/patients', [
                'search' => $pattern,
                'per_page' => 10,
                'page' => 1
            ]);
            
            // Should be blocked by our middleware with 422 status
            $response->assertStatus(422);
            
            // Verify the error response structure
            $response->assertJsonStructure([
                'message',
                'errors' => [
                    'input'
                ]
            ]);
        }
    }

    /** @test */
    public function it_prevents_union_based_injection()
    {
        // Test UNION-based injection attempt
        $injection = "test' UNION SELECT * FROM users WHERE '1'='1";
        $response = $this->getJson('/api/v1/patients?query=' . urlencode($injection));
        
        // Should be blocked by our middleware with 422 status
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Invalid input detected.',
            'errors' => [
                'input' => ['The provided input contains potentially harmful content.']
            ]
        ]);
    }

    /** @test */
    public function it_prevents_boolean_based_blind_injection()
    {
        // Test boolean-based blind injection
        $injection = "test' AND 1=CONVERT(INT,(SELECT table_name FROM information_schema.tables))--";
        $response = $this->getJson('/api/v1/patients?query=' . urlencode($injection));
        
        // Should be blocked by our middleware with 422 status
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Invalid input detected.',
            'errors' => [
                'input' => ['The provided input contains potentially harmful content.']
            ]
        ]);
    }
}
