# Dental Clinic Application - Backup and Recovery Procedures

This document outlines the backup and recovery procedures for the Dental Clinic application. It provides detailed instructions for system administrators on how to perform backups, schedule automated backups, and restore data when needed.

## Overview

The application includes a comprehensive backup system that:

1. Creates full backups of the database and important files
2. Supports automated scheduled backups (daily and weekly)
3. Provides a user interface for managing backups
4. Includes command-line tools for backup and recovery operations

## Backup Components

Each backup includes:

- Complete database dump
- User uploaded files (from `storage/app/public`)
- Public uploads directory (`public/uploads`)
- Configuration files (`.env`)

## Backup Methods

### 1. Using the Web Interface

Administrators with the `manage-backups` permission can create and manage backups through the web interface:

1. Log in as an administrator
2. Navigate to Settings > Backups
3. Use the "Create Backup" button to create a new backup
4. View the list of available backups
5. Download or restore backups as needed

### 2. Using Command Line Tools

The application provides command-line tools for backup and recovery operations:

#### Creating a Backup

```bash
php artisan app:create-backup
```

Options:
- `--force`: Create backup without confirmation

#### Restoring a Backup

```bash
php artisan app:restore-backup [filename]
```

Options:
- `--force`: Restore backup without confirmation

If no filename is provided, the command will list available backups and prompt you to select one.

### 3. Automated Scheduled Backups

The application is configured to automatically create backups:

- Daily backup at midnight
- Weekly backup on Sunday at 2 AM

To ensure scheduled tasks run properly, add the following Cron entry to your server:

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Backup Configuration

Backup settings can be customized in the `config/app_configs.php` file or through environment variables:

| Setting | Environment Variable | Default | Description |
|---------|---------------------|---------|-------------|
| Maximum backups | `MAX_BACKUPS` | 10 | Maximum number of backups to keep |
| Backup schedule | `BACKUP_SCHEDULE` | `0 0 * * *` | Cron expression for daily backup |

## Recovery Procedures

### Using the Web Interface

1. Log in as an administrator
2. Navigate to Settings > Backups
3. Find the backup you want to restore
4. Click the "Restore" button
5. Confirm the restoration

### Using Command Line

```bash
php artisan app:restore-backup
```

This will display a list of available backups and prompt you to select one for restoration.

To restore a specific backup:

```bash
php artisan app:restore-backup backup_2023-05-26_00-00-00.zip
```

## Important Notes

1. **Testing**: Always test the restore process periodically to ensure backups are valid
2. **External Storage**: Consider copying backups to external storage for additional safety
3. **Security**: Backup files contain sensitive information - ensure they are stored securely
4. **Monitoring**: Regularly check that scheduled backups are running successfully

## Troubleshooting

### Backup Creation Fails

1. Check database connection settings
2. Ensure sufficient disk space is available
3. Verify that the application has write permissions to the backup directory
4. Check the logs at `storage/logs/sensitive_operations.log` for detailed error information

### Restore Process Fails

1. Verify that the backup file exists and is not corrupted
2. Ensure database connection settings are correct
3. Check that the application has write permissions to the necessary directories
4. Review the logs for detailed error information

## Best Practices

1. Create manual backups before major system updates
2. Regularly test the restore process with a test environment
3. Maintain backups in multiple locations
4. Document any custom configurations or modifications
5. Periodically review and update backup procedures as the system evolves
