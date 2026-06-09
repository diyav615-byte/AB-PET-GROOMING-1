<?php
include "config/db.php";

$sql = file_get_contents("database/migration_v1.sql");
$parts = explode(';', $sql);

foreach ($parts as $p) {
    $p = trim($p);
    if ($p) {
        if (mysqli_query($conn, $p)) {
            echo "OK\n";
        } else {
            echo "ERR: " . mysqli_error($conn) . "\n";
        }
    }
}
echo "Migration complete\n";