<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Otherwise redirect to login
header("Location: login.php");
exit;
?>
