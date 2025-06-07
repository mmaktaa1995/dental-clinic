<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\BackupService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

/**
 * Command to create a full backup of the application.
 */
class CreateBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:create-backup 
                            {--force : Force backup creation without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Create a full backup of the application';

    /**
     * The backup service instance.
     */
    protected BackupService $backupService;

    /**
     * The logger instance.
     */
    protected LoggerInterface $logger;

    /**
     * Create a new command instance.
     */
    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
        $this->logger = Log::channel('sensitive_operations');
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting backup process...');

        if (!$this->shouldProceed()) {
            return Command::SUCCESS;
        }

        return $this->createBackup();
    }

    /**
     * Determine if the backup process should proceed.
     */
    protected function shouldProceed(): bool
    {
        if ($this->option('force')) {
            return true;
        }

        if (
            !$this->confirm(
                'Are you sure you want to create a backup? This may take some time depending on the database size.'
            )
        ) {
            $this->info('Backup creation cancelled.');
            return false;
        }

        return true;
    }

    /**
     * Create and handle the backup process.
     */
    protected function createBackup(): int
    {
        try {
            $this->info('Creating backup...');
            $result = $this->backupService->createFullBackup();

            if ($result['success'] ?? false) {
                $this->logSuccessfulBackup($result);
                return Command::SUCCESS;
            }

            $this->logFailedBackup($result['error'] ?? 'Unknown error');
            return Command::FAILURE;
        } catch (Exception $e) {
            $this->handleBackupException($e);
            return Command::FAILURE;
        }
    }

    /**
     * Log a successful backup operation.
     */
    protected function logSuccessfulBackup(array $result): void
    {
        $this->info('Backup created successfully!');
        $this->table(
            ['Filename', 'Size', 'Created At'],
            [
                [
                    $result['filename'] ?? 'N/A',
                    $result['size'] ?? 'N/A',
                    $result['created_at'] ?? 'N/A',
                ]
            ]
        );

        $this->logger->info('Manual backup created successfully', [
            'filename' => $result['filename'] ?? null,
            'size' => $result['size'] ?? null,
            'user' => 'CLI',
        ]);
    }

    /**
     * Log a failed backup operation.
     */
    protected function logFailedBackup(string $error): void
    {
        $errorMessage = 'Failed to create backup: ' . $error;
        $this->error($errorMessage);

        $this->logger->error('Manual backup failed', [
            'error' => $error,
            'user' => 'CLI',
        ]);
    }

    /**
     * Handle exceptions during backup.
     */
    protected function handleBackupException(Exception $e): void
    {
        $errorMessage = 'An error occurred: ' . $e->getMessage();
        $this->error($errorMessage);

        $this->logger->error('Manual backup exception', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user' => 'CLI',
        ]);
    }
}
