<?php

namespace App\Services;

use App\Exports\AppointmentExport;
use App\Exports\ExpenseExport;
use App\Exports\PatientExport;
use App\Exports\ServiceExport;
use App\Exports\UserExport;
use App\Http\Requests\AppointmentSearchRequest;
use App\Http\Requests\ExpenseSearchRequest;
use App\Http\Requests\PatientSearchRequest;
use App\Http\Requests\ServiceSearchRequest;
use App\Http\Requests\UserSearchRequest;
use App\Imports\AppointmentImport;
use App\Imports\ExpenseImport;
use App\Imports\PatientImport;
use App\Imports\ServiceImport;
use App\Imports\UserImport;
use App\Services\Search\AppointmentSearch;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use App\Services\Search\ExpenseSearch;
use App\Services\Search\PatientSearch;
use App\Services\Search\ServiceSearch;
use App\Services\Search\UserSearch;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportExportService
{
    /**
     * Export data to Excel/CSV based on search parameters
     *
     * @param BaseSearch $searchService
     * @param string $fileName
     * @param array $headings
     * @param callable $rowFormatter
     * @param string $format
     * @return BinaryFileResponse
     */
    public function export(
        BaseSearch $searchService,
        string $fileName,
        array $headings,
        callable $rowFormatter,
        string $format = 'xlsx'
    ): BinaryFileResponse {
        $data = $searchService->getEntries();
        
        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'export_');
        $handle = fopen($tempFile, 'w');
        
        // Add BOM for UTF-8 encoding
        if ($format === 'csv') {
            fputs($handle, "\xEF\xBB\xBF");
        }
        
        // Write headers
        fputcsv($handle, $headings);
        
        // Write data rows
        foreach ($data as $item) {
            fputcsv($handle, $rowFormatter($item));
        }
        
        fclose($handle);
        
        // Return the file as a download
        return response()->download(
            $tempFile, 
            $fileName . '.' . $format, 
            [
                'Content-Type' => $format === 'csv' 
                    ? 'text/csv; charset=UTF-8' 
                    : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        )->deleteFileAfterSend(true);
    }
    
    /**
     * Export patients using Laravel Excel
     * 
     * @param PatientSearchRequest|array $request
     * @param string $format
     * @return BinaryFileResponse
     */
    public function exportPatients($request, string $format = 'xlsx'): BinaryFileResponse
    {
        $patientSearch = new PatientSearch($request);
        $export = new PatientExport($patientSearch);
        
        return Excel::download($export, 'patients.' . $format);
    }

    /**
     * Import patients data
     *
     * @param UploadedFile $file
     * @return array
     */
    public function importPatients(UploadedFile $file): array
    {
        try {
            $import = new PatientImport();
            Excel::import($import, $file);
            
            return [
                'success' => true,
                'message' => 'Patients imported successfully.',
                'processed' => $import->rowsProcessed ?? 0,
                'failed' => $import->rowsFailed ?? 0
            ];
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'processed' => 0,
                'failed' => 0
            ];
        }
    }
    
    /**
     * Export services using Laravel Excel
     * 
     * @param ServiceSearchRequest|array $request
     * @param string $format
     * @return BinaryFileResponse
     */
    public function exportServices($request, string $format = 'xlsx'): BinaryFileResponse
    {
        $serviceSearch = new ServiceSearch($request);
        $export = new ServiceExport($serviceSearch);
        
        return Excel::download($export, 'services.' . $format);
    }
    
    /**
     * Import services data
     *
     * @param UploadedFile $file
     * @return array
     */
    public function importServices(UploadedFile $file): array
    {
        try {
            $import = new ServiceImport();
            Excel::import($import, $file);
            
            return [
                'success' => true,
                'message' => 'Services imported successfully.',
                'processed' => $import->rowsProcessed ?? 0,
                'failed' => $import->rowsFailed ?? 0
            ];
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'processed' => 0,
                'failed' => 0
            ];
        }
    }
    
    /**
     * Export expenses using Laravel Excel
     * 
     * @param ExpenseSearchRequest|array $request
     * @param string $format
     * @return BinaryFileResponse
     */
    public function exportExpenses($request, string $format = 'xlsx'): BinaryFileResponse
    {
        $expenseSearch = new ExpenseSearch($request);
        $export = new ExpenseExport($expenseSearch);
        
        return Excel::download($export, 'expenses.' . $format);
    }
    
    /**
     * Import expenses data
     *
     * @param UploadedFile $file
     * @return array
     */
    public function importExpenses(UploadedFile $file): array
    {
        try {
            $import = new ExpenseImport();
            Excel::import($import, $file);
            
            return [
                'success' => true,
                'message' => 'Expenses imported successfully.',
                'processed' => $import->rowsProcessed ?? 0,
                'failed' => $import->rowsFailed ?? 0
            ];
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'processed' => 0,
                'failed' => 0
            ];
        }
    }
    
    /**
     * Export users using Laravel Excel
     * 
     * @param UserSearchRequest|array $request
     * @param string $format
     * @return BinaryFileResponse
     */
    public function exportUsers($request, string $format = 'xlsx'): BinaryFileResponse
    {
        $userSearch = new UserSearch($request);
        $export = new UserExport($userSearch);
        
        return Excel::download($export, 'users.' . $format);
    }
    
    /**
     * Import users data
     *
     * @param UploadedFile $file
     * @return array
     */
    public function importUsers(UploadedFile $file): array
    {
        try {
            $import = new UserImport();
            Excel::import($import, $file);
            
            return [
                'success' => true,
                'message' => 'Users imported successfully.',
                'processed' => $import->rowsProcessed ?? 0,
                'failed' => $import->rowsFailed ?? 0
            ];
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'processed' => 0,
                'failed' => 0
            ];
        }
        
    }
    
    /**
     * Export appointments using Laravel Excel
     * 
     * @param AppointmentSearchRequest|array $request
     * @param string $format
     * @return BinaryFileResponse
     */
    public function exportAppointments($request, string $format = 'xlsx'): BinaryFileResponse
    {
        $appointmentSearch = new AppointmentSearch($request);
        $export = new AppointmentExport($appointmentSearch);
        return Excel::download($export, 'appointments.' . $format);
    }
    
    /**
     * Import appointments data
     *
     * @param UploadedFile $file
     * @return array
     */
    public function importAppointments(UploadedFile $file): array
    {
        try {
            $import = new AppointmentImport();
            Excel::import($import, $file);
            
            return [
                'success' => true,
                'message' => 'Appointments imported successfully.',
                'processed' => $import->rowsProcessed ?? 0,
                'failed' => $import->rowsFailed ?? 0
            ];
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'processed' => 0,
                'failed' => 0
            ];
        }
    }
    
    /**
     * Generic import method for backward compatibility
     *
     * @param Request $request
     * @param string $fileKey
     * @param array $validationRules
     * @param callable $rowProcessor
     * @return array
     */
    public function import(
        Request $request,
        string $fileKey,
        array $validationRules,
        callable $rowProcessor
    ): array {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            $fileKey => ['required', 'file', 'mimes:csv,xlsx,xls', 'max:10240'],
        ]);
        
        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => 'Invalid file upload',
                'errors' => $validator->errors(),
            ];
        }
        
        try {
            $file = $request->file($fileKey);
            $filePath = $file->getRealPath();
            
            // Read the file
            $handle = fopen($filePath, 'r');
            
            // Read the header row
            $headers = fgetcsv($handle);
            
            // Process the data rows
            $processed = 0;
            $failed = 0;
            $errors = [];
            
            DB::beginTransaction();
            
            while (($row = fgetcsv($handle)) !== false) {
                // Convert to associative array
                $data = array_combine($headers, $row);
                
                // Validate the row
                $rowValidator = Validator::make($data, $validationRules);
                
                if ($rowValidator->fails()) {
                    $failed++;
                    $errors[] = [
                        'row' => $processed + $failed,
                        'errors' => $rowValidator->errors()->toArray(),
                    ];
                    continue;
                }
                
                // Process the row
                try {
                    $rowProcessor($data);
                    $processed++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = [
                        'row' => $processed + $failed,
                        'errors' => [$e->getMessage()],
                    ];
                }
            }
            
            fclose($handle);
            
            if ($failed > 0 && $processed === 0) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Import failed. No records were imported.',
                    'processed' => 0,
                    'failed' => $failed,
                    'errors' => $errors,
                ];
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => $processed . ' records imported successfully' . ($failed > 0 ? ' with ' . $failed . ' failures.' : '.'),
                'processed' => $processed,
                'failed' => $failed,
                'errors' => $errors,
            ];
            
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'processed' => 0,
                'failed' => 0,
                'errors' => [$e->getMessage()],
            ];
        }
    }
}
