<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RestoreBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore-backup 
                            {filename? : The backup filename to restore}
                            {--force : Force restore without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore a backup of the application';

    /**
     * The backup service instance.
     *
     * @var BackupService
     */
    protected $backupService;

    /**
     * Create a new command instance.
     *
     * @param BackupService $backupService
     * @return void
     */
    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    /**
     * Execute the console command.
     *
     * @return integer
     */
    public function handle()
    {
        $this->info('Starting backup restore process...');

        // Get available backups
        $backups = $this->backupService->listBackups();

        if (empty($backups)) {
            $this->error('No backups found.');
            return Command::FAILURE;
        }

        // Get the backup filename to restore
        $filename = $this->argument('filename');

        if (!$filename) {
            // Display available backups
            $this->info('Available backups:');
            $backupData = [];

            foreach ($backups as $index => $backup) {
                $backupData[] = [
                    $index + 1,
                    $backup['filename'],
                    $backup['size'],
                    $backup['created_at']
                ];
            }

            $this->table(
                ['#', 'Filename', 'Size', 'Created At'],
                $backupData
            );

            // Ask the user to select a backup
            $selection = $this->ask('Enter the number of the backup to restore');

            if (!is_numeric($selection) || $selection < 1 || $selection > count($backups)) {
                $this->error('Invalid selection.');
                return Command::FAILURE;
            }

            $filename = $backups[$selection - 1]['filename'];
        } else {
            // Verify that the specified backup exists
            $backupExists = false;

            foreach ($backups as $backup) {
                if ($backup['filename'] === $filename) {
                    $backupExists = true;
                    break;
                }
            }

            if (!$backupExists) {
                $this->error("Backup file not found: {$filename}");
                return Command::FAILURE;
            }
        }

        // Confirm restore
        if (!$this->option('force')) {
            $this->warn('WARNING: This will overwrite your current database and files with the backup.');
            if (!$this->confirm("Are you sure you want to restore the backup '{$filename}'?")) {
                $this->info('Backup restore cancelled.');
                return Command::SUCCESS;
            }
        }

        try {
            $this->info("Restoring backup '{$filename}'...");
            $result = $this->backupService->restoreBackup($filename);

            if ($result['success']) {
                $this->info('Backup restored successfully!');

                Log::channel('sensitive_operations')->info('Manual backup restore completed successfully', [
                    'filename' => $filename,
                    'user' => 'CLI'
                ]);

                return Command::SUCCESS;
            } else {
                $this->error('Failed to restore backup: ' . ($result['message'] ?? 'Unknown error'));

                Log::channel('sensitive_operations')->error('Manual backup restore failed', [
                    'filename' => $filename,
                    'error' => $result['message'] ?? 'Unknown error',
                    'user' => 'CLI'
                ]);

                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());

            Log::channel('sensitive_operations')->error('Manual backup restore exception', [
                'filename' => $filename,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => 'CLI'
            ]);

            return Command::FAILURE;
        }
    }
}
