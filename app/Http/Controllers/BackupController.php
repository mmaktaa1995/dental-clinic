<?php

namespace App\Http\Controllers;

use App\Http\Requests\BackupSearchRequest;
use App\Http\Resources\BackupResource;
use App\Http\Resources\BaseCollection;
use App\Services\BackupService;
use App\Services\Search\BackupSearch;
use App\Services\SensitiveOperationsLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * The backup service instance.
     *
     * @var BackupService
     */
    protected BackupService $backupService;

    /**
     * Create a new controller instance.
     *
     * @param BackupService $backupService
     * @return void
     */
    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display a listing of available backups with pagination.
     *
     * @param BackupSearchRequest $request
     * @return JsonResponse
     */
    public function index(BackupSearchRequest $request): JsonResponse
    {
        // Log the attempt to list backups
        SensitiveOperationsLogger::attempt('list', 'backups', null, $request->validated());

        try {
            // Use the BackupSearch class to get paginated results
            $search = new BackupSearch($request, $this->backupService);
            $paginatedBackups = $search->getEntries();

            // Log successful backup listing
            SensitiveOperationsLogger::success('list', 'backups', null, [
                'count' => $paginatedBackups->total(),
                'page' => $paginatedBackups->currentPage(),
                'per_page' => $paginatedBackups->perPage()
            ]);

            // Use BaseCollection with BackupResource for consistent response format
            return response()->json(
                BaseCollection::make($paginatedBackups, BackupResource::class)
            );
        } catch (\Exception $e) {
            // Log failed backup listing
            SensitiveOperationsLogger::failure('list', 'backups', null, [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to retrieve backups: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new backup.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        // Log the attempt to create a backup
        SensitiveOperationsLogger::attempt('create', 'backup', null, []);

        try {
            $result = $this->backupService->createFullBackup();

            if ($result['success']) {
                // Log successful backup creation
                SensitiveOperationsLogger::success('create', 'backup', null, [
                    'filename' => $result['filename'],
                    'size' => $result['size']
                ]);

                return response()->json([
                    'message' => $result['message'],
                    'backup' => [
                        'filename' => $result['filename'],
                        'size' => $result['size'],
                        'created_at' => $result['created_at']
                    ]
                ]);
            } else {
                // Log failed backup creation
                SensitiveOperationsLogger::failure('create', 'backup', null, [
                    'error' => $result['error']
                ]);

                return response()->json([
                    'message' => $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            // Log failed backup creation
            SensitiveOperationsLogger::failure('create', 'backup', null, [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to create backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a backup.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(Request $request): JsonResponse
    {
        $request->validate([
            'filename' => 'required|string'
        ]);

        $filename = $request->input('filename');

        // Log the attempt to restore a backup
        SensitiveOperationsLogger::attempt('restore', 'backup', null, [
            'filename' => $filename
        ]);

        try {
            $result = $this->backupService->restoreBackup($filename);

            if ($result['success']) {
                // Log successful backup restoration
                SensitiveOperationsLogger::success('restore', 'backup', null, [
                    'filename' => $filename
                ]);

                return response()->json([
                    'message' => $result['message']
                ]);
            } else {
                // Log failed backup restoration
                SensitiveOperationsLogger::failure('restore', 'backup', null, [
                    'filename' => $filename,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);

                return response()->json([
                    'message' => $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            // Log failed backup restoration
            SensitiveOperationsLogger::failure('restore', 'backup', null, [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to restore backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a backup file.
     *
     * @param string $filename
     * @return mixed
     */
    public function download(string $filename)
    {
        $backupPath = storage_path("app/backups/{$filename}");

        // Log the attempt to download a backup
        SensitiveOperationsLogger::attempt('download', 'backup', null, [
            'filename' => $filename
        ]);

        if (file_exists($backupPath)) {
            // Log successful backup download
            SensitiveOperationsLogger::success('download', 'backup', null, [
                'filename' => $filename,
                'size' => filesize($backupPath)
            ]);

            return Response::download($backupPath, $filename);
        } else {
            // Log failed backup download
            SensitiveOperationsLogger::failure('download', 'backup', null, [
                'filename' => $filename,
                'error' => 'File not found'
            ]);

            return response()->json([
                'message' => 'Backup file not found'
            ], 404);
        }
    }

    /**
     * Delete a backup file.
     *
     * @param string $filename
     * @return JsonResponse
     */
    public function destroy(string $filename): JsonResponse
    {
        $backupPath = storage_path("app/backups/{$filename}");

        // Log the attempt to delete a backup
        SensitiveOperationsLogger::attempt('delete', 'backup', null, [
            'filename' => $filename
        ]);

        if (file_exists($backupPath)) {
            try {
                unlink($backupPath);

                // Log successful backup deletion
                SensitiveOperationsLogger::success('delete', 'backup', null, [
                    'filename' => $filename
                ]);

                return response()->json([
                    'message' => 'Backup deleted successfully'
                ]);
            } catch (\Exception $e) {
                // Log failed backup deletion
                SensitiveOperationsLogger::failure('delete', 'backup', null, [
                    'filename' => $filename,
                    'error' => $e->getMessage()
                ]);

                return response()->json([
                    'message' => 'Failed to delete backup: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // Log failed backup deletion
            SensitiveOperationsLogger::failure('delete', 'backup', null, [
                'filename' => $filename,
                'error' => 'File not found'
            ]);

            return response()->json([
                'message' => 'Backup file not found'
            ], 404);
        }
    }
}
