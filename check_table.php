<?php
include "config/db.php";
$r = mysqli_query($conn, "SHOW TABLES LIKE 'activity_logs'");
echo mysqli_num_rows($r) ? "EXISTS" : "MISSING";