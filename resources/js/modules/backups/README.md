# Backup Module

This module provides a user interface for managing backups in the dental clinic application. It allows administrators to create, download, restore, and delete backups of the application data.

## Features

- Create full backups of the application (database and important files)
- List available backups with details (filename, size, creation date)
- Download backups for external storage
- Restore backups when needed
- Delete old or unnecessary backups

## Components

- **Index.vue**: Main view for managing backups
- **store.ts**: Pinia store for backup operations
- **routes.js**: Route configuration for the backup module

## Security

Access to the backup module is restricted to users with the `manage-backups` permission. This permission is typically assigned to administrator roles only.

## API Endpoints

The module interacts with the following API endpoints:

- `GET /api/v1/backups`: List all available backups
- `POST /api/v1/backups`: Create a new backup
- `POST /api/v1/backups/restore`: Restore a backup
- `GET /api/v1/backups/download/{filename}`: Download a backup file
- `DELETE /api/v1/backups/{filename}`: Delete a backup

## Backend Implementation

The backend implementation includes:

1. **BackupService**: Service class for creating, listing, and restoring backups
2. **BackupController**: API controller for handling backup requests
3. **Console Commands**: Command-line tools for backup and restore operations
   - `php artisan app:create-backup`: Create a backup
   - `php artisan app:restore-backup`: Restore a backup

## Automated Backups

The application is configured to automatically create backups:

- Daily backup at midnight
- Weekly backup on Sunday at 2 AM

These scheduled tasks are managed by Laravel's task scheduler.

## Configuration

Backup settings can be customized in the `config/app_configs.php` file or through environment variables:

- `MAX_BACKUPS`: Maximum number of backups to keep (default: 10)
- `BACKUP_SCHEDULE`: Cron expression for daily backup (default: `0 0 * * *`)

## For More Information

For detailed information about backup and recovery procedures, see the [backup-procedures.md](../../../docs/backup-procedures.md) documentation file.
