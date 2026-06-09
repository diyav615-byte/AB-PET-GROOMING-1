<?php
require_once '../includes/bootstrap.php';
require_once 'auth_check.php';
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token (no regen on failure)
    if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
        $_SESSION['toast'] = ['message' => 'Invalid CSRF token', 'type' => 'error'];
        $referer = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
        header('Location: ' . $referer);
        exit;
    }
    $update_id = (int)$_POST['update_id'];
    $new_status = $_POST['new_status'];
    $update_type = $_POST['update_type'];

    if ($update_type === 'boarding') {
        $stmt = mysqli_prepare($conn, "UPDATE boarding SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $new_status, $update_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['toast'] = ['message' => 'Boarding status updated!', 'type' => 'success'];
    } elseif ($update_type === 'review') {
        $stmt = mysqli_prepare($conn, "UPDATE reviews SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $new_status, $update_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['toast'] = ['message' => 'Review status updated!', 'type' => 'success'];
    }

    $referer = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
    header('Location: ' . $referer);
    exit;
}

header('Location: dashboard.php');
exit;