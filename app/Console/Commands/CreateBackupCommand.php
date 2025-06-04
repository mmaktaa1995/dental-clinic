<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-backup {--force : Force backup creation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full backup of the application';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Starting backup process...');
        
        // Check if we should force backup creation
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to create a backup? This may take some time depending on the database size.')) {
                $this->info('Backup creation cancelled.');
                return Command::SUCCESS;
            }
        }
        
        try {
            $this->info('Creating backup...');
            $result = $this->backupService->createFullBackup();
            
            if ($result['success']) {
                $this->info('Backup created successfully!');
                $this->table(
                    ['Filename', 'Size', 'Created At'],
                    [[$result['filename'], $result['size'], $result['created_at']]]
                );
                
                Log::channel('sensitive_operations')->info('Manual backup created successfully', [
                    'filename' => $result['filename'],
                    'size' => $result['size'],
                    'user' => 'CLI'
                ]);
                
                return Command::SUCCESS;
            } else {
                $this->error('Failed to create backup: ' . ($result['error'] ?? 'Unknown error'));
                
                Log::channel('sensitive_operations')->error('Manual backup failed', [
                    'error' => $result['error'] ?? 'Unknown error',
                    'user' => 'CLI'
                ]);
                
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            
            Log::channel('sensitive_operations')->error('Manual backup exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => 'CLI'
            ]);
            
            return Command::FAILURE;
        }
    }
}
