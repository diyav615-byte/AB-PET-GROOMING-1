<?php

/**
 * HTTPS Enforcement Utility
 * Enforces HTTPS in production environments
 */

class HttpsEnforcement {
    
    /**
     * Enforce HTTPS redirect
     * Only runs in production (when HTTPS is available but not used)
     */
    public static function enforce(): void {
        // Skip if already HTTPS
        if (self::isHttps()) {
            return;
        }
        
        // Skip for localhost/development
        if (self::isLocalhost()) {
            return;
        }
        
        // Skip for CLI
        if (php_sapi_name() === 'cli') {
            return;
        }
        
        // Redirect to HTTPS
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $httpsUrl = "https://{$host}{$uri}";
        
        header("Location: {$httpsUrl}", true, 301);
        exit;
    }
    
    /**
     * Check if current request is HTTPS
     */
    private static function isHttps(): bool {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        }
        
        // Check for proxy headers (behind load balancer/proxy)
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 
            $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        }
        
        if (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && 
            $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if running on localhost/development
     */
    private static function isLocalhost(): bool {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        
        return in_array($host, ['localhost', '127.0.0.1', '::1'], true) ||
               strpos($host, 'localhost') !== false ||
               strpos($host, '127.0.0.1') !== false ||
               strpos($host, '::1') !== false;
    }
}

/**
 * Enforce HTTPS (call early in bootstrap)
 */
function enforceHttps(): void {
    HttpsEnforcement::enforce();
}