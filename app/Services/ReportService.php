<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Patient;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get appointment statistics for a given date range
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @return array
     */
    public function getAppointmentStatistics(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        $totalAppointments = Appointment::whereBetween('date', [$startDate, $endDate])
            ->count();

        // Since we don't have status in the appointments table,
        // we'll just return the total count for now
        return [
            'total' => $totalAppointments,
            'completed' => $totalAppointments, // Assume all are completed
            'cancelled' => 0, // No cancellation tracking
            'completion_rate' => $totalAppointments > 0 ? 100 : 0,
        ];
    }

    /**
     * Get revenue statistics for a given date range
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @return array
     */
    public function getRevenueStatistics(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        $payments = Payment::whereBetween('date', [$startDate, $endDate])
            ->select([
                DB::raw('SUM(amount) as total_revenue'),
                DB::raw('SUM(amount) as cash_revenue'), // All payments are considered cash for now
                DB::raw('0 as card_revenue'), // No card payments in schema
                DB::raw('COUNT(DISTINCT patient_id) as total_patients')
            ])
            ->first();

        return [
            'total_revenue' => (float)($payments->total_revenue ?? 0),
            'cash_revenue' => (float)($payments->cash_revenue ?? 0),
            'card_revenue' => 0, // Not supported in current schema
            'total_patients' => (int)($payments->total_patients ?? 0),
            'average_revenue_per_patient' => ($payments->total_patients ?? 0) > 0
                ? round(($payments->total_revenue ?? 0) / $payments->total_patients, 2)
                : 0,
        ];
    }

    /**
     * Get new patients statistics for a given date range
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @return array
     */
    public function getNewPatientsStatistics(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        $newPatients = Patient::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $previousPeriodStart = $startDate->copy()->subDays($endDate->diffInDays($startDate));
        $previousPeriodEnd = $startDate->copy()->subDay();

        $previousPeriodNewPatients = Patient::whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])
            ->count();

        $growthRate = $previousPeriodNewPatients > 0
            ? (($newPatients - $previousPeriodNewPatients) / $previousPeriodNewPatients) * 100
            : ($newPatients > 0 ? 100 : 0);

        return [
            'new_patients' => $newPatients,
            'previous_period_new_patients' => $previousPeriodNewPatients,
            'growth_rate' => round($growthRate, 2),
        ];
    }

    /**
     * Get appointments by status for a given date range
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @return array
     */
    public function getAppointmentsByStatus(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        // Since we don't have status in the appointments table,
        // we'll just return the total count as 'scheduled'
        $count = Appointment::whereBetween('date', [$startDate, $endDate])
            ->count();

        return [
            'scheduled' => $count
        ];
    }

    /**
     * Get revenue by month for a given date range
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @return array
     */
    public function getRevenueByMonth(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        // Get all payments in the date range
        $payments = Payment::whereBetween('date', [$startDate, $endDate])
            ->get();

        // Group payments by year and month
        $groupedPayments = $payments->groupBy(function ($payment) {
            return Carbon::parse($payment->date)->format('Y-m');
        })->sortKeys();

        // Format the results for charting
        $result = [];
        foreach ($groupedPayments as $yearMonth => $payments) {
            $date = Carbon::createFromFormat('Y-m', $yearMonth);
            $result[] = [
                'month' => $date->format('M Y'),
                'revenue' => (float)$payments->sum('amount')
            ];
        }

        return $result;
    }
}
