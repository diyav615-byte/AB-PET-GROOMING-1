<?php
// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Check session timeout (Changed to 43200 seconds for a 12-hour timeout)
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > 43200) { 
        session_unset(); // Clear session variables safely
        session_destroy(); // Destroy the session completely
        header("Location: login.php?timeout=1");
        exit;
    }
}

// Refresh the activity timestamp so the 12-hour window resets on every page interaction
$_SESSION['last_activity'] = time();
?>