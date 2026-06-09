<?php
include "config/db.php";
$stmt = mysqli_prepare($conn, "SELECT password FROM admin_users WHERE username=?");
$u = 'diyavarma';
mysqli_stmt_bind_param($stmt, "s", $u);
mysqli_stmt_execute($stmt);
$r = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($r);
echo "Password hash: " . substr($row['password'], 0, 30) . "...";
echo "\n";
echo "Starts with \$2y\$ (bcrypt): " . (strpos($row['password'], '$2y$') === 0 ? 'YES' : 'NO') . "\n";
mysqli_stmt_close($stmt);