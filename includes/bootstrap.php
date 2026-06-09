<?php
/**
 * Common bootstrap file - sets up session and security headers
 * Include this at the very top of any PHP file that needs session/CSRF protection
 */

// Session configuration - 3 hours to match admin login
$session_lifetime = 10800; // 3 hours

// Set session cookie parameters BEFORE starting session
ini_set('session.gc_maxlifetime', 10800);
session_set_cookie_params([
    'lifetime' => 10800,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
    'domain' => ''  // Empty for localhost
]);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load CSRF protection
require_once __DIR__ . '/CsrfProtection.php';

// Load security headers
require_once __DIR__ . '/SecurityHeaders.php';