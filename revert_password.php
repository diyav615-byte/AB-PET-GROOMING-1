<?php
include "config/db.php";

$plainPassword = 'varma123';

$stmt = mysqli_prepare($conn, "UPDATE admin_users SET password=? WHERE username='diyavarma'");
mysqli_stmt_bind_param($stmt, "s", $plainPassword);
if (mysqli_stmt_execute($stmt)) {
    echo "Password reverted to plaintext: varma123\n";
} else {
    echo "Error: " . mysqli_stmt_error($stmt) . "\n";
}
mysqli_stmt_close($stmt);