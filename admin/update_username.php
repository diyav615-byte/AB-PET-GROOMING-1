<?php
require_once '../includes/bootstrap.php';
include '../config/db.php';

// Verify CSRF token (no regen on failure)
if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
    die('Invalid CSRF token');
}

$admin_id = $_SESSION['admin_id'];
$username = trim($_POST['username'] ?? '');

if (empty($username)) {
    die('Username cannot be empty');
}

$stmt = mysqli_prepare($conn, "UPDATE admin_users SET username=? WHERE id=?");
mysqli_stmt_bind_param($stmt, "si", $username, $admin_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$_SESSION['admin_username'] = $username;

header("Location: settings.php?success=username_changed");
exit;
