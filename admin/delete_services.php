<?php
require_once '../includes/bootstrap.php';

$page_title = "Delete Service";
require_once 'includes/header.php';

include '../config/db.php';

$id = (int)$_GET['id'];

$stmt = mysqli_prepare($conn, "DELETE FROM services WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: services.php");
exit;