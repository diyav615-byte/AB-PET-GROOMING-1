<?php
class AdminUtils {
    public static function sanitize($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
    
    public static function getSearchClause($fields, $searchTerm) {
        if (!$searchTerm) return '';
        
        $conditions = [];
        foreach ($fields as $field) {
            $conditions[] = "{$field} LIKE ?";
        }
        return '(' . implode(' OR ', $conditions) . ')';
    }
    
    public static function getSearchParams($fields, $searchTerm, $type = 's') {
        $params = [];
        for ($i = 0; $i < count($fields); $i++) {
            $params[] = "%{$searchTerm}%";
        }
        return $params;
    }
    
    public static function buildDateFilter($dateFrom, $dateTo, $dateField = 'created_at') {
        $conditions = [];
        $params = [];
        $types = '';
        
        if ($dateFrom) {
            $conditions[] = "{$dateField} >= ?";
            $params[] = $dateFrom . ' 00:00:00';
            $types .= 's';
        }
        
        if ($dateTo) {
            $conditions[] = "{$dateField} <= ?";
            $params[] = $dateTo . ' 23:59:59';
            $types .= 's';
        }
        
        return [
            'clause' => implode(' AND ', $conditions),
            'params' => $params,
            'types' => $types
        ];
    }
    
    public static function generateCsv($headers, $rows) {
        $csv = [];
        
        $csv[] = $headers;
        
        foreach ($rows as $row) {
            $csv[] = array_map(function($val) {
                if (is_null($val)) return '';
                if (is_array($val)) return json_encode($val);
                return str_replace([',', '"', "\n", "\r"], [' ', ' ', ' ', ' ', ' '], $val);
            }, $row);
        }
        
        return $csv;
    }
    
    public static function downloadCsv($filename, $headers, $rows) {
        $csv = self::generateCsv($headers, $rows);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        foreach ($csv as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
    
    public static function formatDate($date, $format = 'M d, Y') {
        if (!$date) return '-';
        return date($format, strtotime($date));
    }
    
    public static function formatDateTime($datetime, $format = 'M d, Y h:i A') {
        if (!$datetime) return '-';
        return date($format, strtotime($datetime));
    }
    
    public static function getStatusBadge($status, $type = 'default') {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            'active' => 'success',
            'inactive' => 'secondary',
            'approved' => 'success',
            'rejected' => 'danger'
        ];
        
        $color = $colors[$status] ?? 'default';
        
        return '<span class="status-badge status-' . $color . '">' . ucfirst($status) . '</span>';
    }
    
    public static function timeAgo($datetime) {
        if (!$datetime) return '-';
        
        $time = strtotime($datetime);
        $diff = time() - $time;
        
        if ($diff < 60) return $diff . ' seconds ago';
        if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        if ($diff < 604800) return floor($diff / 86400) . ' days ago';
        
        return self::formatDate($datetime);
    }
}

function admin_sanitize($data) {
    return AdminUtils::sanitize($data);
}

function format_status_badge($status) {
    return AdminUtils::getStatusBadge($status);
}

function time_ago($datetime) {
    return AdminUtils::timeAgo($datetime);
}