<?php
require_once '../includes/bootstrap.php';
include '../config/db.php';

if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
    die('Invalid CSRF token');
}

$admin_id = $_SESSION['admin_id'];

$old_password = trim($_POST['old_password'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

if ($new_password !== $confirm_password) {
    die("Passwords do not match");
}

if (strlen($new_password) < 8) {
    die("Password must be at least 8 characters");
}

$stmt = mysqli_prepare($conn, "SELECT password FROM admin_users WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $admin_id);
mysqli_stmt_execute($stmt);
$admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$admin || !password_verify($old_password, $admin['password'])) {
    die("Old password incorrect");
}

$hashedPassword = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);

$stmt = mysqli_prepare($conn, "UPDATE admin_users SET password=? WHERE id=?");
mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $admin_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: settings.php?success=password_changed");
exit;