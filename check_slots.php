<?php
include "config/db.php";

$date = $_GET['date'] ?? '';

if (!$date) {
    echo "0";
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM appointments WHERE appointment_date = ?");
mysqli_stmt_bind_param($stmt, "s", $date);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

echo $row['total'] ?? 0;
?>