<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SensitiveOperationsLogger
{
    /**
     * Log a sensitive operation
     *
     * @param string $operation The operation being performed
     * @param string $entity The entity being affected (e.g., 'user', 'patient', 'payment')
     * @param mixed $entityId The ID of the entity (optional)
     * @param array $details Additional details about the operation (optional)
     * @param string $status The status of the operation ('success', 'failure', 'attempt')
     * @return void
     */
    public static function log(
        string $operation,
        string $entity,
        $entityId = null,
        array $details = [],
        string $status = 'success'
    ): void {
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $username = $user ? $user->username : 'system';
        
        $logData = [
            'timestamp' => now()->toIso8601String(),
            'user_id' => $userId,
            'username' => $username,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'operation' => $operation,
            'entity' => $entity,
            'entity_id' => $entityId,
            'status' => $status,
            'details' => $details,
        ];
        
        // Log to the sensitive_operations channel
        Log::channel('sensitive_operations')->info('Sensitive operation performed', $logData);
    }
    
    /**
     * Log a successful sensitive operation
     *
     * @param string $operation The operation being performed
     * @param string $entity The entity being affected
     * @param mixed $entityId The ID of the entity (optional)
     * @param array $details Additional details about the operation (optional)
     * @return void
     */
    public static function success(string $operation, string $entity, $entityId = null, array $details = []): void
    {
        self::log($operation, $entity, $entityId, $details, 'success');
    }
    
    /**
     * Log a failed sensitive operation
     *
     * @param string $operation The operation being performed
     * @param string $entity The entity being affected
     * @param mixed $entityId The ID of the entity (optional)
     * @param array $details Additional details about the operation (optional)
     * @return void
     */
    public static function failure(string $operation, string $entity, $entityId = null, array $details = []): void
    {
        self::log($operation, $entity, $entityId, $details, 'failure');
    }
    
    /**
     * Log an attempted sensitive operation
     *
     * @param string $operation The operation being performed
     * @param string $entity The entity being affected
     * @param mixed $entityId The ID of the entity (optional)
     * @param array $details Additional details about the operation (optional)
     * @return void
     */
    public static function attempt(string $operation, string $entity, $entityId = null, array $details = []): void
    {
        self::log($operation, $entity, $entityId, $details, 'attempt');
    }
}
