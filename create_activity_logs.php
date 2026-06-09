<?php
include "config/db.php";

$sql = "CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT,
    old_data JSON,
    new_data JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activity_admin (admin_user_id),
    INDEX idx_activity_entity (entity_type, entity_id),
    INDEX idx_activity_created (created_at)
)";

if (mysqli_query($conn, $sql)) {
    echo "activity_logs table created successfully\n";
} else {
    echo "Error: " . mysqli_error($conn) . "\n";
}