<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        
        if (empty($input)) {
            return $next($request);
        }

        $sanitized = $this->sanitize($input);
        
        // Replace the request input with sanitized data
        $request->replace($sanitized);
        
        return $next($request);
    }

    /**
     * Recursively sanitize input data.
     *
     * @param  mixed  $data
     * @return mixed
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }

        if (is_string($data)) {
            // Convert special characters to HTML entities
            $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
            
            // Remove any attribute starting with "on" or "xmlns"
            $data = preg_replace('/(<[^>]+[\s\'\"])(on[a-z]+|xmlns\s*:?[a-z]*?\s*?=)\s*([\"\'][^\s\>]+[\s\>])/i', '$1', $data);
            
            // Remove javascript: and vbscript: protocols
            $data = preg_replace('/(javascript:|vbscript:)/i', '', $data);
            
            // Remove any -moz-binding CSS property
            $data = preg_replace('/-moz-binding/i', '-moz-\\0', $data);
            
            // Remove any style attribute that contains expression
            $data = preg_replace('/(<[^>]*)style\s*=\s*[\'\"].*?expression\s*\([^\"]*?\).*?[\'\"]([^>]*>)/is', '$1$2', $data);
            
            // Remove any style attribute that contains url()
            $data = preg_replace('/(<[^>]*)style\s*=\s*[\'\"].*?url\s*\([^\"]*?\).*?[\'\"]([^>]*>)/is', '$1$2', $data);
            
            // Remove unwanted HTML tags
            $data = strip_tags($data, '<p><a><b><i><u><em><strong><br><hr><h1><h2><h3><h4><h5><h6><ul><ol><li><blockquote><pre><code>');
            
            // Trim whitespace
            $data = trim($data);
            
            // Normalize newlines
            $data = str_replace(["\r\n", "\r"], "\n", $data);
        }
        
        return $data;
    }
}