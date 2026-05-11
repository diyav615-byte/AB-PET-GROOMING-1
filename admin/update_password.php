<?php

session_start();
include '../config/db.php';

$admin_id = $_SESSION['admin_id'];

$old_password = trim($_POST['old_password']);
$new_password = trim($_POST['new_password']);
$confirm_password = trim($_POST['confirm_password']);

if($new_password !== $confirm_password){
    die("Passwords do not match");
}

$query = mysqli_query(
    $conn,
    "SELECT * FROM admin_users WHERE id='$admin_id'"
);

$admin = mysqli_fetch_assoc($query);

if($old_password !== $admin['password']){
    die("Old password incorrect");
}

mysqli_query(
    $conn,
    "UPDATE admin_users 
     SET password='$new_password' 
     WHERE id='$admin_id'"
);

$_SESSION['admin_password'] = $new_password;

header("Location: settings.php?success=password_changed");
exit;

?>