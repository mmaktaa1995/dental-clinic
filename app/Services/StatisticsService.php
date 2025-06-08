<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection as SupportCollection;

class StatisticsService
{
    /** @var ReportService */
    private ReportService $reportService;

    public function __construct(protected Request $request)
    {
        $this->reportService = App::make(ReportService::class);
    }

    /**
     * Get overview statistics data
     *
     * @return array{
     *     appointments: array<string, mixed>,
     *     revenue: array<string, mixed>,
     *     patients: array<string, mixed>,
     *     revenue_trend: array<int, array{month: string, total: float}>
     * }
     */
    public function getOverviewData(): array
    {
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();

        return [
            'appointments' => $this->reportService->getAppointmentStatistics($startDate, $endDate),
            'revenue' => $this->reportService->getRevenueStatistics($startDate, $endDate),
            'patients' => $this->reportService->getNewPatientsStatistics($startDate, $endDate),
            'revenue_trend' => $this->reportService->getRevenueByMonth(
                $startDate->copy()->subMonths(6),
                $endDate
            )
        ];
    }

    /**
     * Get patient growth statistics
     *
     * @return array{
     *     current_period: int,
     *     previous_period: int,
     *     growth_rate: float,
     *     growth_trend: array<int, array{period: string, count: int}>
     * }
     */
    public function getPatientGrowth(): array
    {
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();

        $stats = $this->reportService->getNewPatientsStatistics($startDate, $endDate);
        $previousPeriod = $this->reportService->getNewPatientsStatistics(
            $startDate->copy()->subMonths(3),
            $endDate->copy()->subMonths(3)
        );

        return [
            'current_period' => (int)($stats['new_patients'] ?? 0),
            'previous_period' => (int)($previousPeriod['new_patients'] ?? 0),
            'growth_rate' => (float)($stats['growth_rate'] ?? 0),
            'growth_trend' => $this->getGrowthTrendData($startDate, $endDate)
        ];
    }

    /**
     * Get revenue statistics
     *
     * @return array{
     *     current: array<string, mixed>,
     *     previous_period: array<string, mixed>,
     *     growth_rate: float,
     *     monthly_trend: array<int, array{month: string, total: float}>
     * }
     */
    public function getRevenue(): array
    {
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();

        $revenue = $this->reportService->getRevenueStatistics($startDate, $endDate);
        $previousPeriod = $this->reportService->getRevenueStatistics(
            $startDate->copy()->subMonths(3),
            $endDate->copy()->subMonths(3)
        );

        return [
            'current' => $revenue,
            'previous_period' => $previousPeriod,
            'growth_rate' => $this->calculateGrowthRate(
                (float)($revenue['total_revenue'] ?? 0),
                (float)($previousPeriod['total_revenue'] ?? 0)
            ),
            'monthly_trend' => $this->reportService->getRevenueByMonth(
                $startDate->copy()->subMonths(6),
                $endDate
            )
        ];
    }

    /**
     * Get services statistics
     *
     * @return array{
     *     top_services: array<int, array{id: int, name: string, count: int}>,
     *     service_categories: array<int, array{id: int, name: string, count: int}>
     * }
     */
    public function getServices(): array
    {
        return [
            'top_services' => [],
            'service_categories' => []
        ];
    }

    /**
     * Get appointment statistics
     *
     * @return array{
     *     total: int,
     *     completed: int,
     *     cancelled: int,
     *     completion_rate: float,
     *     by_status: array<string, int>
     * }
     */
    public function getAppointments(): array
    {
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();

        $appointments = $this->reportService->getAppointmentStatistics($startDate, $endDate);
        $byStatus = $this->reportService->getAppointmentsByStatus($startDate, $endDate);

        return [
            'total' => (int)($appointments['total'] ?? 0),
            'completed' => (int)($appointments['completed'] ?? 0),
            'cancelled' => (int)($appointments['cancelled'] ?? 0),
            'completion_rate' => (float)($appointments['completion_rate'] ?? 0),
            // Type hint ensures $byStatus is always an array from ReportService
            'by_status' => $byStatus
        ];
    }

    // Legacy methods for backward compatibility

    /**
     * Get expenses collection
     *
     * @return Collection<int, \App\Models\Expense>
     */
    public function getExpenses(): Collection
    {
        /** @var Collection<int, \App\Models\Expense> $collection */
        $collection = new Collection();
        return $collection;
    }

    /**
     * Get visits collection
     *
     * @return Collection<int, \App\Models\Visit>
     */
    public function getVisits(): Collection
    {
        /** @var Collection<int, \App\Models\Visit> $collection */
        $collection = new Collection();
        return $collection;
    }

    /**
     * Get incomes collection
     *
     * @return Collection<int, \App\Models\Payment>
     */
    public function getIncomes(): Collection
    {
        /** @var Collection<int, \App\Models\Payment> $collection */
        $collection = new Collection();
        return $collection;
    }

    /**
     * Get patients collection
     *
     * @return Collection<int, \App\Models\Patient>
     */
    public function getPatients(): Collection
    {
        /** @var Collection<int, \App\Models\Patient> $collection */
        $collection = new Collection();
        return $collection;
    }

    /**
     * Get debts collection
     *
     * @return SupportCollection<int, array{
     *     id: int,
     *     patient_id: int,
     *     patient_name: string,
     *     amount: float,
     *     due_date: string|null
     * }>
     */
    public function getDebts(): SupportCollection
    {
        /** @var SupportCollection<int, array{id: int, patient_id: int, patient_name: string, amount: float, due_date: string|null}> $collection */
        $collection = new SupportCollection();
        return $collection;
    }

    public function getTotalPatientsCount(): int
    {
        return 0;
    }

    public function getTotalExpenses(): int
    {
        return 0;
    }

    public function getTotalIncome(): int
    {
        return 0;
    }

    public function getTotalDebts(): int
    {
        return 0;
    }

    // Helper Methods
    private function getStartDate(): CarbonInterface
    {
        $year = $this->request->get('year', now()->year);
        $month = $this->request->get('month', 1);
        $day = $this->request->get('day', 1);

        return Carbon::create($year, $month, $day)->startOfDay();
    }

    private function getEndDate(): CarbonInterface
    {
        $year = $this->request->get('year', now()->year);
        $month = $this->request->get('month', 12);
        $day = $this->request->get('day', 31);

        return Carbon::create($year, $month, $day)->endOfDay();
    }

    /**
     * Calculate growth rate between two values
     *
     * @param float $current Current period value
     * @param float $previous Previous period value
     * @return float Growth rate as a percentage (e.g., 15.5 for 15.5%)
     */
    private function calculateGrowthRate(float $current, float $previous): float
    {
        if ($previous === 0.0) {
            return $current > 0 ? 100.0 : 0.0;
        }
        return round((($current - $previous) / abs($previous)) * 100, 2);
    }

    /**
     * Get growth trend data for patients
     *
     * @param CarbonInterface $startDate Start date
     * @param CarbonInterface $endDate End date
     * @return array<int, array{period: string, count: int}>
     */
    private function getGrowthTrendData(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        $period = $startDate->diffInMonths($endDate);
        $data = [];

        for ($i = $period; $i >= 0; $i--) {
            $date = $endDate->copy()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $stats = $this->reportService->getNewPatientsStatistics($monthStart, $monthEnd);
            $data[] = [
                'period' => $monthStart->format('M Y'),
                'count' => $stats['new_patients'] ?? 0
            ];
        }

        return $data;
    }
}
