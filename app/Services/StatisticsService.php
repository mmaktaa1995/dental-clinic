<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use DB;

class StatisticsService
{

    public function __construct(protected Request $request)
    {
    }

    public function getExpenses(): Collection
    {
        return Expense::query()
            ->where('user_id', auth()->id())
            ->when($this->request->has('day'), fn($query) => $query->whereDay('date', $this->request->get('day', date('d'))))
            ->when($this->request->has('month'), fn($query) => $query->whereMonth('date', $this->request->get('month', date('m'))))
            ->when($this->isYearly(), fn($query) => $query->whereYear('date', $this->getYear()))
            ->select([DB::raw("SUM(amount) as value"), DB::raw("CONCAT(YEAR(date),'-', DATE_FORMAT(`date`,'%m')) as label")])
            ->groupByRaw("label")
            ->get();
    }

    public function getVisits(): Collection
    {
        return Visit::query()
            ->where('user_id', auth()->id())
            ->when($this->request->has('day'), fn($query) => $query->whereDay('date', $this->request->get('day', date('d'))))
            ->when($this->request->has('month'), fn($query) => $query->whereMonth('date', $this->request->get('month', date('m'))))
            ->when($this->isYearly(), fn($query) => $query->whereYear('date', $this->getYear()))
            ->select([DB::raw("DISTINCT COUNT(1) as value"), DB::raw("CONCAT(YEAR(date),'-', DATE_FORMAT(`date`,'%m')) as label")])
            ->groupByRaw("label")
            ->get();
    }

    public function getIncomes(): Collection
    {
        return Payment::query()
            ->where('user_id', auth()->id())
            ->when($this->request->has('day'), fn($query) => $query->whereDay('date', $this->request->get('day', date('d'))))
            ->when($this->request->has('month'), fn($query) => $query->whereMonth('date', $this->request->get('month', date('m'))))
            ->when($this->isYearly(), fn($query) => $query->whereYear('date', $this->getYear()))
            ->select([DB::raw("SUM(amount) as value"), DB::raw("CONCAT(YEAR(date),'-', DATE_FORMAT(`date`,'%m')) as label")])
            ->groupByRaw("label")
            ->get();
    }

    public function getPatients(): Collection
    {
        return Patient::query()
            ->where('user_id', auth()->id())
            ->when($this->request->has('day'), fn($query) => $query->whereDay('created_at', $this->request->get('day', date('d'))))
            ->when($this->request->has('month'), fn($query) => $query->whereMonth('created_at', $this->request->get('month', date('m'))))
            ->when($this->isYearly(), fn($query) => $query->whereYear('created_at', $this->getYear()))
            ->select([DB::raw("COUNT(1) as value"), DB::raw("CONCAT(YEAR(created_at),'-', DATE_FORMAT(`created_at`,'%m')) as label")])
            ->groupByRaw("label")
            ->get();
    }

    public function getTotalPatientsCount(): int
    {
        return Patient::query()->where('user_id', auth()->id())->count();
    }

    public function getTotalExpenses(): int
    {
        return Expense::query()->where('user_id', auth()->id())->sum('amount');
    }

    public function getTotalIncome(): int
    {
        return Payment::query()->where('user_id', auth()->id())->sum('amount');
    }

    public function getTotalDebts(): int
    {
        return Payment::query()
            ->when($this->request->has('day'), fn($query) => $query->whereDay('date', $this->request->get('day', date('d'))))
            ->when($this->request->has('month'), fn($query) => $query->whereMonth('date', $this->request->get('month', date('m'))))
            ->when($this->isYearly(), fn($query) => $query->whereYear('date', $this->getYear()))
            ->sum('remaining_amount');
    }

    public function getDebts(): Collection
    {
        return Payment::query()
            ->when($this->request->has('day'), fn($query) => $query->whereDay('date', $this->request->get('day', date('d'))))
            ->when($this->request->has('month'), fn($query) => $query->whereMonth('date', $this->request->get('month', date('m'))))
            ->when($this->isYearly(), fn($query) => $query->whereYear('date', $this->getYear()))
            ->select([DB::raw("SUM(remaining_amount) as value"), DB::raw("CONCAT(YEAR(date),'-', DATE_FORMAT(`date`,'%m')) as label")])
            ->orderBy('label')
            ->groupByRaw("label")
            ->get();
    }

    private function isYearly(): bool
    {
        return $this->request->get('type', 'YEARLY') === 'YEARLY';
    }

    private function getYear(): string
    {
        return $this->request->get('year', date('Y'));
    }
}
