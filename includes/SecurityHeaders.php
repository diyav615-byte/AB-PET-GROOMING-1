<?php

/**
 * Security Headers Utility
 * Sets security-related HTTP headers for all responses
 */

class SecurityHeaders {
    
    /**
     * Set all security headers
     * @param bool $isAdmin Whether this is an admin page
     */
    public static function setHeaders(bool $isAdmin = false): void {
        // Prevent clickjacking
        header('X-Frame-Options: DENY');
        
        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Enable XSS protection (legacy but still useful)
        header('X-XSS-Protection: 1; mode=block');
        
        // Referrer policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Permissions policy (restrict dangerous features)
        header('Permissions-Policy: accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()');
        
        // Content Security Policy
        $csp = self::buildCSP($isAdmin);
        header("Content-Security-Policy: $csp");
        
        // HSTS - only set if HTTPS detected
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
        
        // Prevent caching of sensitive pages
        if (self::isSensitivePage()) {
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        
        // Remove server signature
        header_remove('X-Powered-By');
        
        // Cross-Origin policies
        header('Cross-Origin-Opener-Policy: same-origin');
        header('Cross-Origin-Resource-Policy: same-origin');
    }
    
    /**
     * Build Content Security Policy string
     * @param bool $isAdmin
     * @return string
     */
    private static function buildCSP(bool $isAdmin): string {
        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.gstatic.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' https://wa.me https://api.whatsapp.com",
            "frame-src 'self' https://wa.me https://www.google.com https://maps.google.com https://maps.googleapis.com https://*.google.com https://*.googleapis.com",
            "form-action 'self' https://wa.me",
            "base-uri 'self'",
            "object-src 'none'",
        ];
        
        if ($isAdmin) {
            // Admin panel may need more permissive CSP for Chart.js
            $directives[] = "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com";
            $directives[] = "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com";
        }
        
        return implode('; ', $directives);
    }
    
    /**
     * Check if current page is sensitive (login, admin, forms)
     * @return bool
     */
    private static function isSensitivePage(): bool {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $sensitivePaths = [
            '/admin/',
            '/login.php',
            '/logout.php',
            '/submit-',
            '/admin/',
        ];
        
        foreach ($sensitivePaths as $path) {
            if (strpos($uri, $path) !== false) {
                return true;
            }
        }
        return false;
    }
}

/**
 * Convenience function to set security headers
 * @param bool $isAdmin
 */
function setSecurityHeaders(bool $isAdmin = false): void {
    SecurityHeaders::setHeaders($isAdmin);
}