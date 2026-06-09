<?php

/**
 * Rate Limiting Utility
 * Provides rate limiting for login attempts and form submissions
 */

class RateLimiter {
    private static $storage = [];

    /**
     * Check if action is rate limited
     * @param string $key Unique key for the rate limit (e.g., IP + action)
     * @param int $maxAttempts Maximum attempts allowed
     * @param int $windowSeconds Time window in seconds
     * @return array ['allowed' => bool, 'remaining' => int, 'resetTime' => int]
     */
    public static function checkLimit(string $key, int $maxAttempts, int $windowSeconds): array {
        $now = time();
        $key = 'ratelimit_' . md5($key);
        
        // Initialize storage if not exists
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => [],
                'window_start' => $now
            ];
        }
        
        $data = &$_SESSION[$key];
        
        // Clean old attempts outside the window
        $data['attempts'] = array_filter($data['attempts'], function($timestamp) use ($now, $windowSeconds) {
            return ($now - $timestamp) < $windowSeconds;
        });
        
        $attemptCount = count($data['attempts']);
        $remaining = max(0, $maxAttempts - $attemptCount);
        $resetTime = $data['window_start'] + $windowSeconds;
        
        if ($attemptCount >= $maxAttempts) {
            return [
                'allowed' => false,
                'remaining' => 0,
                'resetTime' => $resetTime,
                'retryAfter' => $resetTime - $now
            ];
        }
        
        return [
            'allowed' => true,
            'remaining' => $remaining,
            'resetTime' => $resetTime
        ];
    }
    
    /**
     * Record an attempt for the given key
     * @param string $key
     */
    public static function recordAttempt(string $key): void {
        $key = 'ratelimit_' . md5($key);
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => [],
                'window_start' => time()
            ];
        }
        $_SESSION[$key]['attempts'][] = time();
    }
    
    /**
     * Check login rate limit (5 attempts per 15 minutes)
     * @param string $identifier Usually IP address
     * @return array
     */
    public static function checkLoginLimit(string $identifier): array {
        return self::checkLimit("login_{$identifier}", 5, 900); // 5 attempts per 15 min
    }
    
    /**
     * Record login attempt
     * @param string $identifier
     */
    public static function recordLoginAttempt(string $identifier): void {
        self::recordAttempt("login_{$identifier}");
    }
    
    /**
     * Check form submission rate limit (10 submissions per minute)
     * @param string $identifier
     * @return array
     */
    public static function checkFormLimit(string $identifier): array {
        return self::checkLimit("form_{$identifier}", 10, 60); // 10 per minute
    }
    
    /**
     * Record form submission
     * @param string $identifier
     */
    public static function recordFormAttempt(string $identifier): void {
        self::recordAttempt("form_{$identifier}");
    }
    
    /**
     * Check API rate limit (30 requests per minute)
     * @param string $identifier
     * @return array
     */
    public static function checkApiLimit(string $identifier): array {
        return self::checkLimit("api_{$identifier}", 30, 60);
    }
    
    /**
     * Record API request
     * @param string $identifier
     */
    public static function recordApiAttempt(string $identifier): void {
        self::recordAttempt("api_{$identifier}");
    }
    
    /**
     * Get client IP address
     * @return string
     */
    public static function getClientIP(): string {
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                return trim($ips[0]);
            }
        }
        return 'unknown';
    }
}

/**
 * Check login rate limit and handle exceeded
 * @return bool True if allowed, false if rate limited (exits)
 */
function checkLoginRateLimit(): bool {
    $ip = RateLimiter::getClientIP();
    $result = RateLimiter::checkLoginLimit($ip);
    
    if (!$result['allowed']) {
        $retryMinutes = ceil($result['retryAfter'] / 60);
        header('HTTP/1.1 429 Too Many Requests');
        header('Retry-After: ' . $result['retryAfter']);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => "Too many login attempts. Please try again in {$retryMinutes} minute(s)."
            ]);
        } else {
            $error = "Too many login attempts. Please try again in {$retryMinutes} minute(s).";
            if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
                $_SESSION['login_error'] = $error;
                header('Location: login.php');
            } else {
                echo "<div class='alert alert-danger'>{$error}</div>";
            }
        }
        exit();
    }
    
    return true;
}

/**
 * Record failed login attempt
 */
function recordFailedLogin(): void {
    $ip = RateLimiter::getClientIP();
    RateLimiter::recordLoginAttempt($ip);
}

/**
 * Check form submission rate limit
 * @return bool True if allowed
 */
function checkFormRateLimit(): bool {
    $ip = RateLimiter::getClientIP();
    $result = RateLimiter::checkFormLimit($ip);
    
    if (!$result['allowed']) {
        header('HTTP/1.1 429 Too Many Requests');
        header('Retry-After: ' . $result['retryAfter']);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Too many form submissions. Please wait a moment.'
            ]);
        } else {
            echo "<div class='alert alert-danger'>Too many submissions. Please wait a moment.</div>";
        }
        exit();
    }
    
    return true;
}

/**
 * Record form submission attempt
 */
function recordFormAttempt(): void {
    $ip = RateLimiter::getClientIP();
    RateLimiter::recordFormAttempt($ip);
}