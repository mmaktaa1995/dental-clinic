<?php

namespace Tests\Unit\Services;

use App\Models\Patient;
use App\Models\User;
use App\Services\PatientService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientServiceTest extends TestCase
{
    use RefreshDatabase;

    private PatientService $patientService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->patientService = new PatientService();
        $this->user = $this->createTestUser();
    }

    /**
     * Create a test user with a unique username and email
     *
     * @return User
     */
    private function createTestUser(): User
    {
        $unique = uniqid();
        return User::create([
            'name' => 'Test User ' . $unique,
            'username' => 'testuser_' . $unique,
            'email' => 'test_' . $unique . '@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Create a test patient with the given file number
     *
     * @param string $fileNumber
     * @return Patient
     */
    private function createPatientWithFileNumber(string $fileNumber, array $attributes = []): Patient
    {
        return Patient::create(array_merge([
            'name' => 'Test Patient ' . uniqid(),
            'age' => 30,
            'gender' => 'male',
            'phone' => '1234567890',
            'mobile' => '0987654321',
            'file_number' => $fileNumber,
            'user_id' => $this->user->id
        ], $attributes));
    }

    /** @test */
    public function returns1WhenNoPatientsExist()
    {
        // Clear all patients
        Patient::query()->delete();

        $fileNumber = $this->patientService->getLastFileNumber();

        $this->assertEquals('1', $fileNumber);
    }

    /** @test */
    public function returnsNextFileNumberWhenPatientsExist()
    {
        // Create a patient with file number 'PAT100' directly in the database
        $this->createPatientWithFileNumber('PAT100');

        $fileNumber = $this->patientService->getLastFileNumber();

        // The method should increment the number after PAT
        $this->assertStringStartsWith('PAT', $fileNumber);
        $this->assertGreaterThan(100, (int)substr($fileNumber, 3));
    }

    /** @test */
    public function handlesMultiplePatientsCorrectly()
    {
        // Create multiple patients with different file numbers
        $this->createPatientWithFileNumber('PAT99');
        $this->createPatientWithFileNumber('PAT100');

        $fileNumber = $this->patientService->getLastFileNumber();

        // The method should use the highest file number and increment it
        $this->assertStringStartsWith('PAT', $fileNumber);
        $this->assertGreaterThan(100, (int)substr($fileNumber, 3));
    }

    /** @test */
    public function handlesFileNumbersWithoutPrefix()
    {
        // Create a patient with a numeric file number (without PAT prefix)
        $this->createPatientWithFileNumber('100');

        $fileNumber = $this->patientService->getLastFileNumber();

        // Should return a new file number with PAT prefix
        $this->assertStringStartsWith('PAT', $fileNumber);
    }

    /** @test */
    public function handlesNonNumericFileNumbers()
    {
        // Create a patient with a non-numeric file number
        $this->createPatientWithFileNumber('ABC123');

        $fileNumber = $this->patientService->getLastFileNumber();

        // Should return a new file number with PAT prefix
        $this->assertStringStartsWith('PAT', $fileNumber);
    }

    /** @test */
    public function handlesMixedPrefixAndNonPrefixFileNumbers()
    {
        // Create patients with different file number formats
        $this->createPatientWithFileNumber('100');
        $this->createPatientWithFileNumber('PAT200');
        $this->createPatientWithFileNumber('300');
        $this->createPatientWithFileNumber('PAT400');

        $fileNumber = $this->patientService->getLastFileNumber();

        // Should return a new file number with PAT prefix
        $this->assertStringStartsWith('PAT', $fileNumber);
        $this->assertGreaterThan(400, (int)substr($fileNumber, 3));
    }

    /** @test */
    public function handlesEmptyFileNumbers()
    {
        // Create a patient with an empty file number
        $this->createPatientWithFileNumber('');

        $fileNumber = $this->patientService->getLastFileNumber();

        // Should return a new file number with PAT prefix
        $this->assertStringStartsWith('PAT', $fileNumber);
    }
}
