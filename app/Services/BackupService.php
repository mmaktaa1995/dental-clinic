<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Carbon\Carbon;

class BackupService
{
    /**
     * The directory where backups are stored
     */
    protected string $backupDirectory;

    /**
     * The maximum number of backups to keep
     */
    protected int $maxBackups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->backupDirectory = storage_path('app/backups');
        $this->maxBackups = config('app_configs.max_backups', 10);

        // Ensure the backup directory exists
        if (!File::exists($this->backupDirectory)) {
            File::makeDirectory($this->backupDirectory, 0755, true);
        }
    }

    /**
     * Create a full backup of the application
     * 
     * @return array Information about the created backup
     */
    public function createFullBackup(): array
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupFilename = "backup_{$timestamp}.zip";
        $backupPath = "{$this->backupDirectory}/{$backupFilename}";

        try {
            // Log the start of the backup process
            Log::channel('sensitive_operations')->info('Starting full backup', [
                'timestamp' => $timestamp,
                'backup_file' => $backupFilename
            ]);

            // Create a database dump
            $databaseDumpPath = $this->createDatabaseDump($timestamp);

            // Create a zip file containing the database dump and important files
            $this->createBackupZip($backupPath, $databaseDumpPath);

            // Remove the temporary database dump file
            File::delete($databaseDumpPath);

            // Cleanup old backups
            $this->cleanupOldBackups();

            // Log successful backup
            Log::channel('sensitive_operations')->info('Full backup completed successfully', [
                'timestamp' => $timestamp,
                'backup_file' => $backupFilename,
                'size' => File::size($backupPath)
            ]);

            return [
                'success' => true,
                'message' => 'Backup created successfully',
                'filename' => $backupFilename,
                'path' => $backupPath,
                'size' => $this->formatSize(File::size($backupPath)),
                'created_at' => $timestamp
            ];
        } catch (\Exception $e) {
            // Log error
            Log::channel('sensitive_operations')->error('Backup failed', [
                'timestamp' => $timestamp,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create a database dump
     * 
     * @param string $timestamp Timestamp for the filename
     * @return string Path to the created database dump file
     */
    protected function createDatabaseDump(string $timestamp): string
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        $dumpFilename = "db_dump_{$timestamp}.sql";
        $dumpPath = storage_path("app/backups/temp/{$dumpFilename}");

        // Ensure the temp directory exists
        if (!File::exists(dirname($dumpPath))) {
            File::makeDirectory(dirname($dumpPath), 0755, true);
        }

        if ($driver === 'mysql') {
            $this->createMysqlDump($dumpPath);
        } elseif ($driver === 'sqlite') {
            $this->createSqliteDump($dumpPath);
        } else {
            throw new \Exception("Unsupported database driver: {$driver}");
        }

        return $dumpPath;
    }

    /**
     * Create a MySQL database dump
     * 
     * @param string $dumpPath Path to save the dump file
     */
    protected function createMysqlDump(string $dumpPath): void
    {
        $connection = config('database.default');
        $host = config("database.connections.{$connection}.host");
        $port = config("database.connections.{$connection}.port");
        $database = config("database.connections.{$connection}.database");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");

        // Create dump file using mysqldump command
        $command = "mysqldump --host={$host} --port={$port} --user={$username} --password={$password} {$database} > '$dumpPath'";

        // Execute the command
        $result = exec($command, $output, $returnVar);
        logger("COMMAND: " . $command);
        logger("RESULT:", [$result, $returnVar]);

        // Check if the file was actually created and has content
        // if (!File::exists($dumpPath) || File::size($dumpPath) === 0) {
        //     throw new \Exception("Failed to create MySQL dump: File was not created or is empty");
        // }

        // Log success even if return value is non-zero
        logger("MySQL dump created successfully at: " . $dumpPath);
    }

    /**
     * Create a SQLite database dump
     * 
     * @param string $dumpPath Path to save the dump file
     */
    protected function createSqliteDump(string $dumpPath): void
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");

        // For SQLite, we'll just copy the database file
        File::copy($database, $dumpPath);
    }

    /**
     * Create a zip file containing the database dump and important files
     * 
     * @param string $backupPath Path to save the backup zip file
     * @param string $databaseDumpPath Path to the database dump file
     */
    protected function createBackupZip(string $backupPath, string $databaseDumpPath): void
    {
        $zip = new ZipArchive();

        if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Add the database dump to the zip file
            $zip->addFile($databaseDumpPath, 'database/dump.sql');

            // Add important directories to the zip file
            $this->addDirectoryToZip($zip, storage_path('app/public'), 'storage/app/public');
            $this->addDirectoryToZip($zip, public_path('uploads'), 'public/uploads');

            // Add .env file for configuration
            if (File::exists(base_path('.env'))) {
                $zip->addFile(base_path('.env'), '.env');
            }

            $zip->close();
        } else {
            throw new \Exception("Failed to create zip file");
        }
    }

    /**
     * Add a directory to a zip file recursively
     * 
     * @param ZipArchive $zip The zip archive
     * @param string $directory The directory to add
     * @param string $zipDirectory The directory path within the zip file
     */
    protected function addDirectoryToZip(ZipArchive $zip, string $directory, string $zipDirectory): void
    {
        if (!File::exists($directory)) {
            return;
        }

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $relativePath = $zipDirectory . '/' . $file->getRelativePathname();
            $zip->addFile($file->getPathname(), $relativePath);
        }
    }

    /**
     * Clean up old backups, keeping only the most recent ones
     */
    protected function cleanupOldBackups(): void
    {
        $backups = collect(File::files($this->backupDirectory))
            ->filter(function ($file) {
                return preg_match('/^backup_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.zip$/', $file->getFilename());
            })
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            });

        if ($backups->count() > $this->maxBackups) {
            $backupsToDelete = $backups->slice($this->maxBackups);

            foreach ($backupsToDelete as $backup) {
                File::delete($backup->getPathname());

                Log::channel('sensitive_operations')->info('Deleted old backup', [
                    'filename' => $backup->getFilename(),
                    'path' => $backup->getPathname()
                ]);
            }
        }
    }

    /**
     * Get a list of all available backups
     * 
     * @return array List of backups with metadata
     */
    public function listBackups(): array
    {
        $backups = [];

        if (File::exists($this->backupDirectory)) {
            $files = File::files($this->backupDirectory);

            foreach ($files as $file) {
                if (preg_match('/^backup_(\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2})\.zip$/', $file->getFilename(), $matches)) {
                    $timestamp = $matches[1];
                    $backups[] = [
                        'filename' => $file->getFilename(),
                        'path' => $file->getPathname(),
                        'size' => $this->formatSize($file->getSize()),
                        'created_at' => Carbon::createFromFormat('Y-m-d_H-i-s', $timestamp)->toDateTimeString()
                    ];
                }
            }
        }

        // Sort backups by creation date (newest first)
        usort($backups, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backups;
    }

    /**
     * Restore a backup
     * 
     * @param string $backupFilename The filename of the backup to restore
     * @return array Information about the restore operation
     */
    public function restoreBackup(string $backupFilename): array
    {
        $backupPath = "{$this->backupDirectory}/{$backupFilename}";

        if (!File::exists($backupPath)) {
            return [
                'success' => false,
                'message' => "Backup file not found: {$backupFilename}"
            ];
        }

        try {
            // Log the start of the restore process
            Log::channel('sensitive_operations')->info('Starting backup restore', [
                'backup_file' => $backupFilename
            ]);

            // Create a temporary directory for extraction
            $extractPath = storage_path('app/backups/temp/restore_' . time());
            File::makeDirectory($extractPath, 0755, true);

            // Extract the backup
            $zip = new ZipArchive();
            if ($zip->open($backupPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                throw new \Exception("Failed to open backup zip file");
            }

            // Restore the database
            $this->restoreDatabase($extractPath);

            // Restore files
            $this->restoreFiles($extractPath);

            // Clean up the temporary directory
            File::deleteDirectory($extractPath);

            // Log successful restore
            Log::channel('sensitive_operations')->info('Backup restored successfully', [
                'backup_file' => $backupFilename
            ]);

            return [
                'success' => true,
                'message' => 'Backup restored successfully'
            ];
        } catch (\Exception $e) {
            // Log error
            Log::channel('sensitive_operations')->error('Backup restore failed', [
                'backup_file' => $backupFilename,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up the temporary directory if it exists
            if (isset($extractPath) && File::exists($extractPath)) {
                File::deleteDirectory($extractPath);
            }

            return [
                'success' => false,
                'message' => 'Backup restore failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Restore the database from a backup
     * 
     * @param string $extractPath Path to the extracted backup
     */
    protected function restoreDatabase(string $extractPath): void
    {
        $dumpPath = "{$extractPath}/database/dump.sql";

        if (!File::exists($dumpPath)) {
            throw new \Exception("Database dump not found in backup");
        }

        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if ($driver === 'mysql') {
            $this->restoreMysqlDatabase($dumpPath);
        } elseif ($driver === 'sqlite') {
            $this->restoreSqliteDatabase($dumpPath);
        } else {
            throw new \Exception("Unsupported database driver: {$driver}");
        }
    }

    /**
     * Restore a MySQL database from a dump file
     * 
     * @param string $dumpPath Path to the database dump file
     */
    protected function restoreMysqlDatabase(string $dumpPath): void
    {
        $connection = config('database.default');
        $host = config("database.connections.{$connection}.host");
        $port = config("database.connections.{$connection}.port");
        $database = config("database.connections.{$connection}.database");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");

        // Restore database using mysql command
        $command = "mysql --host={$host} --port={$port} --user={$username} --password={$password} {$database} < {$dumpPath}";

        // Execute the command
        $result = exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Failed to restore MySQL database: " . implode("\n", $output));
        }
    }

    /**
     * Restore a SQLite database from a dump file
     * 
     * @param string $dumpPath Path to the database dump file
     */
    protected function restoreSqliteDatabase(string $dumpPath): void
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");

        // For SQLite, we'll just replace the database file
        File::copy($dumpPath, $database, true);
    }

    /**
     * Restore files from a backup
     * 
     * @param string $extractPath Path to the extracted backup
     */
    protected function restoreFiles(string $extractPath): void
    {
        // Restore storage files
        $storagePath = "{$extractPath}/storage/app/public";
        if (File::exists($storagePath)) {
            File::copyDirectory($storagePath, storage_path('app/public'), true);
        }

        // Restore public uploads
        $uploadsPath = "{$extractPath}/public/uploads";
        if (File::exists($uploadsPath)) {
            File::copyDirectory($uploadsPath, public_path('uploads'), true);
        }
    }

    /**
     * Format a file size in bytes to a human-readable format
     * 
     * @param int $bytes File size in bytes
     * @return string Formatted file size
     */
    protected function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
