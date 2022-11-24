<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $type = $request->get('type', 'YEARLY');

        $expenses = Expense::
            when($request->has('month'), function ($query) use ($request) {
                $query->whereMonth('date', $request->get('month', date('m')));
            })
            ->when($type === 'YEARLY', function ($query) use ($request) {
                $query->whereYear('date', $request->get('year', date('Y')));
            })
            ->select([\DB::raw("SUM(amount) as value"), \DB::raw("CONCAT(YEAR(date),'-', MONTH(date)) as label")])
            ->groupByRaw("label")->get();



        $visits = Visit::
            when($request->has('month'), function ($query) use ($request) {
                $query->whereMonth('date', $request->get('month', date('m')));
            })
            ->when($type === 'YEARLY', function ($query) use ($request) {
                $query->whereYear('date', $request->get('year', date('Y')));
            })
            ->select([\DB::raw("distinct count(1) as value"), \DB::raw("CONCAT(YEAR(date),'-', MONTH(date)) as label")])
            ->groupByRaw("label")->get();

        $incomes = Payment::
            when($request->has('month'), function ($query) use ($request) {
                $query->whereMonth('date', $request->get('month', date('m')));
            })
            ->when($type === 'YEARLY', function ($query) use ($request) {
                $query->whereYear('date', $request->get('year', date('Y')));
            })
            ->select([\DB::raw("SUM(amount) as value"), \DB::raw("CONCAT(YEAR(date),'-', MONTH(date)) as label")])
            ->groupByRaw("label")->get();

        $patients = Patient::  when($request->has('month'), function ($query) use ($request) {
            $query->whereMonth('created_at', $request->get('month', date('m')));
        })
            ->when($type === 'YEARLY', function ($query) use ($request) {
                $query->whereYear('created_at', $request->get('year', date('Y')));
            })
            ->select([\DB::raw("COUNT(1) as value"), \DB::raw("CONCAT(YEAR(created_at),'-', MONTH(created_at)) as label")])
            ->groupByRaw("label")->get();

        $patientsTotalCount = Patient::count();
        $expensesTotal = Expense::sum('amount');
        $incomeTotal = Payment::sum('amount');

        return response()->json(compact('expenses', 'visits', 'patients', 'incomes', 'patientsTotalCount', 'expensesTotal', 'incomeTotal'));
    }
}
