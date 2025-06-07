<?php

namespace Tests\Unit\Services;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Payment;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReportService $service;
    private Carbon $startDate;
    private Carbon $endDate;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear all data before each test
        Appointment::query()->delete();
        Payment::query()->delete();
        Patient::query()->delete();
        
        $this->service = new ReportService();
        $this->startDate = now()->startOfMonth();
        $this->endDate = now()->endOfMonth();
    }

    /** @test */
    public function it_gets_appointment_statistics()
    {
        // Create test data
        $appointmentCount = 5;
        Appointment::factory()->count($appointmentCount)->create([
            'date' => now()
        ]);

        // Get statistics
        $stats = $this->service->getAppointmentStatistics($this->startDate, $this->endDate);

        // Assertions
        $this->assertEquals($appointmentCount, $stats['total']);
        $this->assertEquals($appointmentCount, $stats['completed']); // All appointments are considered completed
        $this->assertEquals(0, $stats['cancelled']); // No cancellation tracking in current schema
        $this->assertEquals(100, $stats['completion_rate']); // 100% completion rate since all are completed
    }

    /** @test */
    public function it_gets_revenue_statistics()
    {
        // Create test patient
        $patient = Patient::factory()->create();
        
        // Create test payments for the same patient
        $payment1 = 100.50;
        $payment2 = 199.50;
        $totalPayment = $payment1 + $payment2;
        
        Payment::factory()->create([
            'patient_id' => $patient->id,
            'amount' => $payment1,
            'date' => now()->toDateString()
        ]);
        
        Payment::factory()->create([
            'patient_id' => $patient->id,
            'amount' => $payment2,
            'date' => now()->toDateString()
        ]);

        // Get statistics
        $stats = $this->service->getRevenueStatistics($this->startDate, $this->endDate);

        // Assertions with delta for floating point comparison
        $this->assertEqualsWithDelta($totalPayment, $stats['total_revenue'], 0.01);
        $this->assertEqualsWithDelta($totalPayment, $stats['cash_revenue'], 0.01); // All payments are considered cash
        $this->assertEquals(0, $stats['card_revenue']); // No card payments in current schema
        $this->assertEquals(1, $stats['total_patients']); // Only one unique patient
        $this->assertEqualsWithDelta($totalPayment, $stats['average_revenue_per_patient'], 0.01);
    }

    /** @test */
    public function it_gets_new_patients_statistics()
    {
        // Create test patients for current period
        $currentCount = 10;
        Patient::factory()->count($currentCount)->create([
            'created_at' => now()
        ]);

        // Create test patients for previous period
        $previousCount = 5;
        $previousStart = now()->subMonths(1)->startOfMonth();
        $previousEnd = now()->subMonths(1)->endOfMonth();
        
        Patient::factory()->count($previousCount)->create([
            'created_at' => $previousStart->copy()->addDays(5)
        ]);

        // Get statistics
        $stats = $this->service->getNewPatientsStatistics($this->startDate, $this->endDate);

        // Calculate expected growth rate
        $expectedGrowthRate = $previousCount > 0 
            ? (($currentCount - $previousCount) / $previousCount) * 100 
            : ($currentCount > 0 ? 100 : 0);

        // Assertions
        $this->assertEquals($currentCount, $stats['new_patients']);
        $this->assertEquals($previousCount, $stats['previous_period_new_patients']);
        $this->assertEqualsWithDelta($expectedGrowthRate, $stats['growth_rate'], 0.01);
    }

    /** @test */
    public function it_gets_appointments_by_status()
    {
        // Create test appointments
        $appointmentCount = 5;
        Appointment::factory()->count($appointmentCount)->create([
            'date' => now()
        ]);

        // Get statistics
        $result = $this->service->getAppointmentsByStatus($this->startDate, $this->endDate);

        // Assertions - all appointments are considered 'scheduled' in the current implementation
        $this->assertArrayHasKey('scheduled', $result);
        $this->assertEquals($appointmentCount, $result['scheduled']);
    }

    /** @test */
    public function it_gets_revenue_by_month()
    {
        // Create test payments for different months
        $currentMonth = now();
        $lastMonth = now()->subMonth();
        
        $currentMonthAmount = 100.75;
        $lastMonthAmount = 200.25;
        
        Payment::factory()->create([
            'amount' => $currentMonthAmount,
            'date' => $currentMonth->toDateString()
        ]);
        
        Payment::factory()->create([
            'amount' => $lastMonthAmount,
            'date' => $lastMonth->toDateString()
        ]);

        // Get statistics
        $result = $this->service->getRevenueByMonth(
            $lastMonth->copy()->startOfMonth(),
            $currentMonth->copy()->endOfMonth()
        );

        // Assertions
        $this->assertCount(2, $result);
        
        // Convert to associative array for easier assertions
        $monthlyData = [];
        foreach ($result as $item) {
            $monthlyData[$item['month']] = $item['revenue'];
        }
        
        // Check last month's data
        $this->assertArrayHasKey($lastMonth->format('M Y'), $monthlyData);
        $this->assertEqualsWithDelta($lastMonthAmount, $monthlyData[$lastMonth->format('M Y')], 0.01);
        
        // Check current month's data
        $this->assertArrayHasKey($currentMonth->format('M Y'), $monthlyData);
        $this->assertEqualsWithDelta($currentMonthAmount, $monthlyData[$currentMonth->format('M Y')], 0.01);
    }

    /** @test */
    public function it_handles_empty_data_correctly()
    {
        // Test with a date range that has no data
        $futureDate = now()->addYear();
        $futureEndDate = $futureDate->copy()->addMonth();
        
        // Test appointment statistics with no data
        $appointmentStats = $this->service->getAppointmentStatistics($futureDate, $futureEndDate);
        $this->assertEquals(0, $appointmentStats['total']);
        $this->assertEquals(0, $appointmentStats['completion_rate']);

        // Test revenue statistics with no data
        $revenueStats = $this->service->getRevenueStatistics($futureDate, $futureEndDate);
        $this->assertEquals(0, $revenueStats['total_revenue']);
        $this->assertEquals(0, $revenueStats['average_revenue_per_patient']);

        // Test new patients statistics with no data
        $patientStats = $this->service->getNewPatientsStatistics($futureDate, $futureEndDate);
        $this->assertEquals(0, $patientStats['new_patients']);
        $this->assertEquals(0, $patientStats['growth_rate']);
    }
}
