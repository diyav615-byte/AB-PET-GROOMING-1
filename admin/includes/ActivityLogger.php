<?php
class ActivityLogger {
    private $conn;
    private $adminUserId;
    
    public function __construct($conn, $adminUserId = null) {
        $this->conn = $conn;
        $this->adminUserId = $adminUserId ?? ($_SESSION['admin_id'] ?? 0);
    }
    
    public function log($action, $entityType, $entityId, $oldData = [], $newData = []) {
        $adminUserId = $this->adminUserId;
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        
        $oldJson = !empty($oldData) ? json_encode($oldData) : null;
        $newJson = !empty($newData) ? json_encode($newData) : null;
        
        $sql = "INSERT INTO activity_logs (admin_user_id, action, entity_type, entity_id, old_data, new_data, ip_address) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iisisss', $adminUserId, $action, $entityType, $entityId, $oldJson, $newJson, $ipAddress);
        $stmt->execute();
        
        return $this->conn->insert_id;
    }
    
    public function create($entityType, $entityId, $data) {
        return $this->log('create', $entityType, $entityId, [], $data);
    }
    
    public function update($entityType, $entityId, $oldData, $newData) {
        return $this->log('update', $entityType, $entityId, $oldData, $newData);
    }
    
    public function delete($entityType, $entityId, $data) {
        return $this->log('delete', $entityType, $entityId, $data, []);
    }
    
    public function statusChange($entityType, $entityId, $oldStatus, $newStatus, $reason = '') {
        $oldData = ['status' => $oldStatus];
        $newData = ['status' => $newStatus, 'reason' => $reason];
        return $this->log('status_change', $entityType, $entityId, $oldData, $newData);
    }
    
    public function getLogs($entityType = null, $entityId = null, $limit = 50) {
        $sql = "SELECT al.*, au.username as admin_username 
                FROM activity_logs al 
                LEFT JOIN admin_users au ON al.admin_user_id = au.id";
        
        $conditions = [];
        $params = [];
        $types = '';
        
        if ($entityType) {
            $conditions[] = "al.entity_type = ?";
            $params[] = $entityType;
            $types .= 's';
        }
        
        if ($entityId) {
            $conditions[] = "al.entity_id = ?";
            $params[] = $entityId;
            $types .= 'i';
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $sql .= " ORDER BY al.created_at DESC LIMIT ?";
        $params[] = $limit;
        $types .= 'i';
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        
        return $stmt->get_result();
    }
}

function getActivityLogger($conn, $adminUserId = null) {
    return new ActivityLogger($conn, $adminUserId);
}