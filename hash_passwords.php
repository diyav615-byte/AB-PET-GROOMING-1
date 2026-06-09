<?php
/**
 * Script to hash plaintext passwords in admin_users table
 * Run once after deploying password hashing changes
 */

require_once 'config/db.php';

echo "Hashing passwords in admin_users table...\n";

$stmt = mysqli_prepare($conn, "SELECT id, password FROM admin_users WHERE password NOT LIKE '$2y$%' AND password NOT LIKE '$argon2i$%' AND password NOT LIKE '$argon2id$%'");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$updated = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $plainPassword = $row['password'];
    
    // Hash the password using bcrypt (cost factor 12)
    $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    
    $stmt = mysqli_prepare($conn, "UPDATE admin_users SET password = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $id);
    if (mysqli_stmt_execute($stmt)) {
        echo "Updated user ID $id\n";
        $updated++;
    } else {
        echo "Failed to update user ID $id: " . mysqli_stmt_error($stmt) . "\n";
    }
    mysqli_stmt_close($stmt);
}

echo "\nDone! Updated $updated user(s).\n";