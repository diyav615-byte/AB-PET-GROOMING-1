<?php
require_once '../includes/bootstrap.php';
include '../config/db.php';

// Check if ActivityLogger exists, if not create a simple fallback
if (file_exists('includes/ActivityLogger.php')) {
    require_once 'includes/ActivityLogger.php';
} else {
    // Simple fallback logger
    function getActivityLogger($conn) {
        return new class($conn) {
            private $conn;
            public function __construct($conn) { $this->conn = $conn; }
            public function delete($table, $id, $oldData) {
                $adminId = $_SESSION['admin_id'] ?? 0;
                $action = "DELETE";
                $entityType = $table;
                $entityId = $id;
                $oldDataJson = json_encode($oldData);
                $newDataJson = '{}';
                $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                
                $stmt = mysqli_prepare($this->conn, "INSERT INTO activity_logs (admin_user_id, action, entity_type, entity_id, old_data, new_data, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ississs", $adminId, $action, $entityType, $entityId, $oldDataJson, $newDataJson, $ip);
                return mysqli_stmt_execute($stmt);
            }
        };
    }
}

if (!isset($_GET['id']) || !isset($_GET['table'])) {
    die("Invalid Request");
}

$id = (int) $_GET['id'];
$table = $_GET['table'];

$allowedTables = [
    'bookings',
    'boarding',
    'services',
    'reviews',
    'contact_messages',
    'appointments'
];

if (!in_array($table, $allowedTables)) {
    die("Table not allowed");
}

/*
|--------------------------------------------------------------------------
| GET OLD DATA
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare($conn, "SELECT * FROM $table WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$oldData = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare($conn, "DELETE FROM $table WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
$delete = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($delete) {
    $logger = getActivityLogger($conn);
    $result = $logger->delete($table, $id, $oldData);

    if (!$result) {
        error_log("Activity Log Failed for table $table, id $id");
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$page_title = "Delete";
require_once 'includes/header.php';

// Check if ActivityLogger exists, if not create a simple fallback
if (file_exists('includes/ActivityLogger.php')) {
    require_once 'includes/ActivityLogger.php';
} else {
    // Simple fallback logger
    function getActivityLogger($conn) {
        return new class($conn) {
            private $conn;
            public function __construct($conn) { $this->conn = $conn; }
            public function delete($table, $id, $oldData) {
                $adminId = $_SESSION['admin_id'] ?? 0;
                $action = "DELETE";
                $entityType = $table;
                $entityId = $id;
                $oldDataJson = json_encode($oldData);
                $newDataJson = '{}';
                $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                
                $stmt = mysqli_prepare($this->conn, "INSERT INTO activity_logs (admin_user_id, action, entity_type, entity_id, old_data, new_data, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ississs", $adminId, $action, $entityType, $entityId, $oldDataJson, $newDataJson, $ip);
                return mysqli_stmt_execute($stmt);
            }
        };
    }
}

if (!isset($_GET['id']) || !isset($_GET['table'])) {
    die("Invalid Request");
}

$id = (int) $_GET['id'];
$table = $_GET['table'];

$allowedTables = [
    'bookings',
    'boarding',
    'services',
    'reviews',
    'contact_messages',
    'appointments'
];

if (!in_array($table, $allowedTables)) {
    die("Table not allowed");
}

/*
|--------------------------------------------------------------------------
| GET OLD DATA
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare($conn, "SELECT * FROM $table WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$oldData = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare($conn, "DELETE FROM $table WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
$delete = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($delete) {
    $logger = getActivityLogger($conn);
    $result = $logger->delete($table, $id, $oldData);

    if (!$result) {
        error_log("Activity Log Failed for table $table, id $id");
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

echo "Delete failed.";

?>