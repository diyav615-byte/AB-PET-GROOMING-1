<?php

class ActivityLogger {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function log($adminId, $action, $entityType, $entityId, $oldData = null, $newData = null) {
        $oldDataJson = $oldData ? json_encode($oldData) : '{}';
        $newDataJson = $newData ? json_encode($newData) : '{}';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        
        $stmt = mysqli_prepare($this->conn, 
            "INSERT INTO activity_logs (admin_user_id, action, entity_type, entity_id, old_data, new_data, ip_address) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        
        if (!$stmt) {
            error_log("ActivityLogger prepare failed: " . mysqli_error($this->conn));
            return false;
        }
        
        mysqli_stmt_bind_param($stmt, "ississs", $adminId, $action, $entityType, $entityId, $oldDataJson, $newDataJson, $ip);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function delete($table, $id, $oldData) {
        $adminId = $_SESSION['admin_id'] ?? 0;
        return $this->log($adminId, 'DELETE', $table, $id, $oldData, null);
    }

    public function create($table, $id, $newData) {
        $adminId = $_SESSION['admin_id'] ?? 0;
        return $this->log($adminId, 'CREATE', $table, $id, null, $newData);
    }

    public function update($table, $id, $oldData, $newData) {
        $adminId = $_SESSION['admin_id'] ?? 0;
        return $this->log($adminId, 'UPDATE', $table, $id, $oldData, $newData);
    }
}

function getActivityLogger($conn) {
    return new ActivityLogger($conn);
}