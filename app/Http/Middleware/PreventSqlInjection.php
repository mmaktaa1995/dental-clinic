<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class PreventSqlInjection
{
    /**
     * List of SQL keywords and patterns to block
     */
    protected array $sqlKeywords = [
        // SQL commands - only match at the start of string or after whitespace
        '/(?:^|\s)(?i)(?:select|insert|update|delete|drop|alter|truncate|union|into|load_file|outfile)\b/',
        
        // SQL comments - only match when not part of a word
        '/(?<![\w\-])--(?!\S)/',
        '/(?<![\w\-])#(?!\S)/',
        '/(?<![\w\-]);(?!\S)/',
        
        // Block comments - only match complete comment blocks
        '/\/\*.*?\*\//s',
        
        // Boolean-based injection patterns - more specific to prevent false positives
        '/(?:^|\s)(?i)(?:or|and)\s+\d+\s*=\s*[\d\-]+(?:\s|$)/',
        '/(?:^|\s)(?i)(?:or|and)\s+[\w\-]+\s*[=<>!]+\s*[\w\-]+(?:\s|$)/',
        
        // Function calls - only match at word boundaries
        '/(?:^|\s)(?i)(?:sleep|benchmark)\s*\(/',
        
        // Basic SQL injection patterns - more specific to prevent false positives
        '/(?:^|\s)(?i)(?:select|insert|update|delete)\s+[\w\*]+(?:\s*,\s*[\w\*]+)*\s+from\s+[\w\-]+/i',
        '/(?:^|\s)(?i)union\s+select\b/',
        '/(?:^|\s)(?i)select\s+[\w\*]+(?:\s*,\s*[\w\*]+)*\s+from\s+[\w\-]+/i',
        
        // Common SQL injection patterns with quotes and equals
        "/'\s*=\s*'/",
        '/"\s*=\s*"/',
        
        // Null byte injection
        '/\x00/'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        
        // Check all input parameters for SQL injection attempts
        $suspiciousInput = $this->findSuspiciousInput($input);
        
        if (!empty($suspiciousInput)) {
            $this->logSuspiciousActivity($request, $suspiciousInput);
            
            return response()->json([
                'message' => 'Invalid input detected.',
                'errors' => ['input' => ['The provided input contains potentially harmful content.']],
            ], 422);
        }
        
        return $next($request);
    }
    
    /**
     * Find suspicious input that might contain SQL injection attempts
     */
    protected function findSuspiciousInput($input, $path = ''): array
    {
        $suspicious = [];
        
        foreach ($input as $key => $value) {
            $currentPath = $path ? "{$path}.{$key}" : $key;
            
            if (is_array($value)) {
                $nestedSuspicious = $this->findSuspiciousInput($value, $currentPath);
                $suspicious = array_merge($suspicious, $nestedSuspicious);
            } elseif (is_string($value) && $this->containsSqlInjection($value)) {
                $suspicious[$currentPath] = [
                    'value' => $this->truncateForLog($value),
                    'pattern' => $this->getMatchingPattern($value)
                ];
            }
        }
        
        return $suspicious;
    }
    
    /**
     * Log suspicious activity to the security log
     */
    protected function logSuspiciousActivity(Request $request, array $suspiciousInput): void
    {
        $user = $request->user();
        
        $logContext = [
            'user_id' => $user ? $user->id : 'guest',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'suspicious_input' => $suspiciousInput,
        ];
        
        Log::channel('security')->warning('Potential SQL injection attempt detected', $logContext);
    }
    
    /**
     * Get the matching SQL injection pattern for a given input
     */
    protected function getMatchingPattern(string $input): ?string
    {
        $normalizedValue = $this->normalizeInput($input);
        
        foreach ($this->sqlKeywords as $pattern) {
            try {
                if (preg_match($pattern . 'i', $normalizedValue)) {
                    return $pattern;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        return null;
    }
    
    /**
     * Normalize input for pattern matching
     */
    protected function normalizeInput(string $input): string
    {
        // Normalize whitespace to catch attempts to bypass with line breaks
        $normalized = preg_replace('/\s+/', ' ', $input);
        
        // Remove common obfuscation techniques
        $normalized = str_ireplace(['/*!', '*/'], '', $normalized);
        $normalized = str_ireplace(['%20', '\t', '\n', '\r', '\0', '\x1a'], ' ', $normalized);
        
        return $normalized;
    }
    
    /**
     * Truncate a string for logging purposes
     */
    protected function truncateForLog(string $value, int $maxLength = 200): string
    {
        if (mb_strlen($value) <= $maxLength) {
            return $value;
        }
        
        return mb_substr($value, 0, $maxLength) . '...';
    }

    /**
     * Recursively check for SQL injection patterns in input data
     */
    protected function containsSqlInjection($value): bool
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                if ($this->containsSqlInjection($item)) {
                    return true;
                }
            }
            return false;
        }

        if (!is_string($value) || empty(trim($value))) {
            return false;
        }

        $normalizedValue = $this->normalizeInput($value);
        
        // Skip empty values after normalization
        if (empty(trim($normalizedValue))) {
            return false;
        }
        
        // Check for SQL injection patterns
        foreach ($this->sqlKeywords as $pattern) {
            try {
                if (preg_match($pattern . 'i', $normalizedValue)) {
                    return true;
                }
            } catch (\Exception $e) {
                // Log the error but don't let it break the application
                Log::warning("Invalid regex pattern in SQL injection filter: " . $e->getMessage());
            }
        }

        return false;
    }
}
