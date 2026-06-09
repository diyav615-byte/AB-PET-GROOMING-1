<?php
// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Check session timeout (1 hour = 3600 seconds)
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > 3600) { 
        session_unset(); // Clear session variables safely
        session_destroy(); // Destroy the session completely
        header("Location: login.php?timeout=1");
        exit;
    }
}

// Refresh the activity timestamp so the window resets on every page interaction
$_SESSION['last_activity'] = time();
?>