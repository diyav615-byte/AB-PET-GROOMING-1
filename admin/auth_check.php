Z<?php
// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Check session timeout
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > 3600) { // 1 hour timeout
        session_destroy();
        header("Location: login.php?timeout=1");
        exit;
    }
}

$_SESSION['last_activity'] = time();
?>
