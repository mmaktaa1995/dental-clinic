<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $statistics = new StatisticsService($request);

        return response()->json([
            'expenses' => $statistics->getExpenses(),
            'visits' => $statistics->getVisits(),
            'patients' => $statistics->getPatients(),
            'incomes' => $statistics->getIncomes(),
            'debts' => $statistics->getDebts(),
            'totalPatientsCount' => $statistics->getTotalPatientsCount(),
            'totalExpenses' => $statistics->getTotalExpenses(),
            'totalIncome' => $statistics->getTotalIncome(),
            'totalDebts' => $statistics->getTotalDebts(),
        ]);
    }
}
