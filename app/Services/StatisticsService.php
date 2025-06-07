<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use DB;

class StatisticsService
{
    private ReportService $reportService;

    public function __construct(protected Request $request)
    {
        $this->reportService = App::make(ReportService::class);
    }

    // Core Statistics Methods
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
            'current_period' => $stats['new_patients'] ?? 0,
            'previous_period' => $previousPeriod['new_patients'] ?? 0,
            'growth_rate' => $stats['growth_rate'] ?? 0,
            'growth_trend' => $this->getGrowthTrendData($startDate, $endDate)
        ];
    }

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
                $revenue['total_revenue'] ?? 0,
                $previousPeriod['total_revenue'] ?? 0
            ),
            'monthly_trend' => $this->reportService->getRevenueByMonth(
                $startDate->copy()->subMonths(6),
                $endDate
            )
        ];
    }

    public function getServices(): array
    {
        return [
            'top_services' => [],
            'service_categories' => []
        ];
    }

    public function getAppointments(): array
    {
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();

        $appointments = $this->reportService->getAppointmentStatistics($startDate, $endDate);
        $byStatus = $this->reportService->getAppointmentsByStatus($startDate, $endDate);

        return [
            'total' => $appointments['total'] ?? 0,
            'completed' => $appointments['completed'] ?? 0,
            'cancelled' => $appointments['cancelled'] ?? 0,
            'completion_rate' => $appointments['completion_rate'] ?? 0,
            'by_status' => $byStatus
        ];
    }

    // Legacy methods for backward compatibility
    public function getExpenses(): Collection
    {
        return new Collection();
    }

    public function getVisits(): Collection
    {
        return new Collection();
    }

    public function getIncomes(): Collection
    {
        return new Collection();
    }

    public function getPatients(): Collection
    {
        return new Collection();
    }

    public function getDebts(): Collection
    {
        return new Collection();
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

    private function calculateGrowthRate(float $current, float $previous): float
    {
        if ($previous === 0.0) {
            return $current > 0 ? 100.0 : 0.0;
        }
        return round((($current - $previous) / abs($previous)) * 100, 2);
    }

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

    private function isYearly(): bool
    {
        return $this->request->get('type', 'YEARLY') === 'YEARLY';
    }

    private function getYear(): int
    {
        return $this->request->get('year', now()->year);
    }
}
