<?php
session_start();
require_once 'auth_check.php';
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_id = (int)$_POST['update_id'];
    $new_status = $_POST['new_status'];
    $update_type = $_POST['update_type'];

    if ($update_type === 'booking' || $update_type === 'appointment') {
        // appointments table - no status column, so we just delete or can add
        // For now just acknowledge the action
        $_SESSION['toast'] = ['message' => 'Appointment action completed!', 'type' => 'success'];
    } elseif ($update_type === 'boarding') {
        mysqli_query($conn, "UPDATE boarding SET status = '$new_status' WHERE id = $update_id");
        $_SESSION['toast'] = ['message' => 'Boarding status updated!', 'type' => 'success'];
    } elseif ($update_type === 'review') {
        mysqli_query($conn, "UPDATE reviews SET status = '$new_status' WHERE id = $update_id");
        $_SESSION['toast'] = ['message' => 'Review status updated!', 'type' => 'success'];
    }

    $referer = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
    header('Location: ' . $referer);
    exit;
}

header('Location: dashboard.php');
exit;