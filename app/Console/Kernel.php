<?php

namespace App\Console;

use App\Services\BackupService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Create a daily backup at midnight
        $schedule->call(function () {
            try {
                $backupService = app(BackupService::class);
                $result = $backupService->createFullBackup();
                
                if ($result['success']) {
                    Log::channel('sensitive_operations')->info('Scheduled backup completed successfully', [
                        'filename' => $result['filename'],
                        'size' => $result['size']
                    ]);
                } else {
                    Log::channel('sensitive_operations')->error('Scheduled backup failed', [
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                }
            } catch (\Exception $e) {
                Log::channel('sensitive_operations')->error('Scheduled backup exception', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        })->dailyAt('00:00')->name('daily-backup');
        
        // Weekly backup on Sunday at 2 AM
        $schedule->call(function () {
            try {
                $backupService = app(BackupService::class);
                $result = $backupService->createFullBackup();
                
                if ($result['success']) {
                    Log::channel('sensitive_operations')->info('Weekly scheduled backup completed successfully', [
                        'filename' => $result['filename'],
                        'size' => $result['size']
                    ]);
                } else {
                    Log::channel('sensitive_operations')->error('Weekly scheduled backup failed', [
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                }
            } catch (\Exception $e) {
                Log::channel('sensitive_operations')->error('Weekly scheduled backup exception', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        })->weekly()->sundays()->at('02:00')->name('weekly-backup');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
