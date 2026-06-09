<?php
/**
 * Admin Authentication Check
 * Include this at the top of all admin pages that require authentication
 */

// Set session cookie parameters BEFORE starting session (must be before session_start)
ini_set('session.gc_maxlifetime', 10800);
session_set_cookie_params([
    'lifetime' => 10800, // 3 hours
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
    'domain' => ''  // Empty for localhost
]);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Allow access to login page without redirect
    $current_file = basename($_SERVER['PHP_SELF']);
    if ($current_file !== 'login.php') {
        header("Location: login.php");
        exit;
    }
}

// Check session timeout (3 hours = 10800 seconds) - matches session lifetime
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > 10800) { 
        session_unset(); // Clear session variables safely
        session_destroy(); // Destroy the session completely
        header("Location: login.php?timeout=1");
        exit;
    }
}

// Refresh the activity timestamp so the window resets on every page interaction
$_SESSION['last_activity'] = time();
?>