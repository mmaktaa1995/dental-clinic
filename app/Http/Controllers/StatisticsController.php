<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function __construct(
        private StatisticsService $statisticsService,
        private ReportService $reportService
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'expenses' => $this->statisticsService->getExpenses(),
            'visits' => $this->statisticsService->getVisits(),
            'patients' => $this->statisticsService->getPatients(),
            'incomes' => $this->statisticsService->getIncomes(),
            'debts' => $this->statisticsService->getDebts(),
            'totalPatientsCount' => $this->statisticsService->getTotalPatientsCount(),
            'totalExpenses' => $this->statisticsService->getTotalExpenses(),
            'totalIncome' => $this->statisticsService->getTotalIncome(),
            'totalDebts' => $this->statisticsService->getTotalDebts(),
        ]);
    }

    public function overview(Request $request): JsonResponse
    {
        return response()->json($this->statisticsService->getOverviewData());
    }

    public function patientGrowth(Request $request): JsonResponse
    {
        return response()->json($this->statisticsService->getPatientGrowth());
    }

    public function revenue(Request $request): JsonResponse
    {
        return response()->json($this->statisticsService->getRevenue());
    }

    public function services(Request $request): JsonResponse
    {
        return response()->json($this->statisticsService->getServices());
    }

    public function expenses(Request $request): JsonResponse
    {
        return response()->json($this->statisticsService->getExpenses());
    }

    public function appointments(Request $request): JsonResponse
    {
        return response()->json($this->statisticsService->getAppointments());
    }
}
