<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentSearchRequest;
use App\Http\Requests\ExpenseSearchRequest;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\PatientSearchRequest;
use App\Http\Requests\ServiceSearchRequest;
use App\Http\Requests\UserSearchRequest;
use App\Models\Appointment;
use App\Models\Expense;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use App\Services\ImportExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportExportController extends Controller
{
    protected ImportExportService $importExportService;

    public function __construct(ImportExportService $importExportService)
    {
        $this->importExportService = $importExportService;
    }

    /**
     * Export patients data
     *
     * @param PatientSearchRequest $request
     * @return BinaryFileResponse
     */
    public function exportPatients(PatientSearchRequest $request): BinaryFileResponse
    {
        // If template is requested, pass empty filters
        if ($request->boolean('template')) {
            return $this->importExportService->exportPatients([], $request->get('format', 'xlsx'));
        }

        return $this->importExportService->exportPatients(
            $request,
            $request->get('format', 'xlsx')
        );
    }

    /**
     * Import patients data
     *
     * @param ImportRequest $request
     * @return JsonResponse
     */
    public function importPatients(ImportRequest $request): JsonResponse
    {
        $result = $this->importExportService->importPatients($request->file('file'));

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Export services data
     *
     * @param ServiceSearchRequest $request
     * @return BinaryFileResponse
     */
    public function exportServices(ServiceSearchRequest $request): BinaryFileResponse
    {
        // If template is requested, pass empty filters
        if ($request->boolean('template')) {
            return $this->importExportService->exportServices([], $request->get('format', 'xlsx'));
        }

        return $this->importExportService->exportServices(
            $request,
            $request->get('format', 'xlsx')
        );
    }

    /**
     * Import services data
     *
     * @param ImportRequest $request
     * @return JsonResponse
     */
    public function importServices(ImportRequest $request): JsonResponse
    {
        $result = $this->importExportService->importServices($request->file('file'));

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Export expenses data
     *
     * @param ExpenseSearchRequest $request
     * @return BinaryFileResponse
     */
    public function exportExpenses(ExpenseSearchRequest $request): BinaryFileResponse
    {
        // If template is requested, pass empty filters
        if ($request->boolean('template')) {
            return $this->importExportService->exportExpenses([], $request->get('format', 'xlsx'));
        }

        return $this->importExportService->exportExpenses(
            $request,
            $request->get('format', 'xlsx')
        );
    }

    /**
     * Import expenses data
     *
     * @param ImportRequest $request
     * @return JsonResponse
     */
    public function importExpenses(ImportRequest $request): JsonResponse
    {
        $result = $this->importExportService->importExpenses($request->file('file'));

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Export users data
     *
     * @param UserSearchRequest $request
     * @return BinaryFileResponse
     */
    public function exportUsers(UserSearchRequest $request): BinaryFileResponse
    {
        // If template is requested, pass empty filters
        if ($request->boolean('template')) {
            return $this->importExportService->exportUsers([], $request->get('format', 'xlsx'));
        }

        return $this->importExportService->exportUsers(
            $request,
            $request->get('format', 'xlsx')
        );
    }

    /**
     * Import users data
     *
     * @param ImportRequest $request
     * @return JsonResponse
     */
    public function importUsers(ImportRequest $request): JsonResponse
    {
        $result = $this->importExportService->importUsers($request->file('file'));

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Export appointments data
     *
     * @param AppointmentSearchRequest $request
     * @return BinaryFileResponse
     */
    public function exportAppointments(AppointmentSearchRequest $request): BinaryFileResponse
    {
        // If template is requested, pass empty filters
        if ($request->boolean('template')) {
            return $this->importExportService->exportAppointments([], $request->get('format', 'xlsx'));
        }

        return $this->importExportService->exportAppointments(
            $request,
            $request->get('format', 'xlsx')
        );
    }

    /**
     * Import appointments data
     *
     * @param ImportRequest $request
     * @return JsonResponse
     */
    public function importAppointments(ImportRequest $request): JsonResponse
    {
        $validationRules = [
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ];

        $rowProcessor = function ($data) {
            Appointment::create([
                'patient_id' => $data['patient_id'],
                'date' => $data['date'],
                'notes' => $data['notes'] ?? null,
                'user_id' => Auth::id(),
            ]);
        };

        $result = $this->importExportService->import(
            $request,
            'file',
            $validationRules,
            $rowProcessor
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
