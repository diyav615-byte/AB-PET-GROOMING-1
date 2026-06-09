<?php
require_once '../includes/bootstrap.php';
include '../config/db.php';

$page_title = "Reviews";
require_once 'includes/header.php';

$id = (int)$_GET['id'];

// approve
$stmt = mysqli_prepare($conn, "UPDATE reviews SET status='approved' WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// max 10 reviews only - delete excess approved reviews
$stmt = mysqli_prepare($conn, "
DELETE FROM reviews 
WHERE status='approved' 
AND id NOT IN (
  SELECT id FROM (
    SELECT id FROM reviews 
    WHERE status='approved' 
    ORDER BY id DESC 
    LIMIT 10
  ) temp
)");
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: reviews.php");
?>