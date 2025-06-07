<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains general configuration settings for the application.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    */

    // Maximum number of backups to keep
    'max_backups' => env('MAX_BACKUPS', 10),

    // Backup schedule (cron expression)
    'backup_schedule' => env('BACKUP_SCHEDULE', '0 0 * * *'), // Default: daily at midnight

    // Paths to include in backups
    'backup_include_paths' => [
        storage_path('app/public'),
        public_path('uploads'),
    ],

    // Database connections to backup
    'backup_db_connections' => [
        env('DB_CONNECTION', 'mysql'),
    ],
];
