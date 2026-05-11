<?php

session_start();

include '../config/db.php';

$admin_id = $_SESSION['admin_id'];

$username =
mysqli_real_escape_string(
$conn,
$_POST['username']
);

mysqli_query($conn,
"UPDATE admin_users
SET username='$username'
WHERE id='$admin_id'");

$_SESSION['admin_username'] = $username;

header("Location: settings.php");

?>