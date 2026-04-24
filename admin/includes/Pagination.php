<?php
class Pagination {
    private $conn;
    private $table;
    private $primaryKey = 'id';
    private $page;
    private $perPage = 15;
    private $totalRecords;
    private $totalPages;
    private $offset;
    private $whereClause = '';
    private $orderBy = 'id DESC';
    
    public function __construct($conn, $table, $perPage = 15) {
        $this->conn = $conn;
        $this->table = $table;
        $this->perPage = $perPage;
        $this->page = (int)($_GET['page'] ?? 1);
        if ($this->page < 1) $this->page = 1;
    }
    
    public function setWhere($where, $params = []) {
        $this->whereClause = $where;
        $this->whereParams = $params;
        return $this;
    }
    
    public function setOrderBy($orderBy) {
        $this->orderBy = $orderBy;
        return $this;
    }
    
    public function getTotal() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($this->whereClause) {
            $sql .= " WHERE {$this->whereClause}";
        }
        
        if (isset($this->whereParams) && !empty($this->whereParams)) {
            $stmt = $this->conn->prepare($sql);
            $types = str_repeat('s', count($this->whereParams));
            $stmt->bind_param($types, ...$this->whereParams);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = mysqli_query($this->conn, $sql);
            $row = mysqli_fetch_assoc($result);
        }
        
        $this->totalRecords = $row['total'] ?? 0;
        $this->totalPages = ceil($this->totalRecords / $this->perPage);
        
        if ($this->page > $this->totalPages && $this->totalPages > 0) {
            $this->page = $this->totalPages;
        }
        
        $this->offset = ($this->page - 1) * $this->perPage;
        
        return $this->totalRecords;
    }
    
    public function getData() {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->whereClause) {
            $sql .= " WHERE {$this->whereClause}";
        }
        $sql .= " ORDER BY {$this->orderBy} LIMIT {$this->perPage} OFFSET {$this->offset}";
        
        if (isset($this->whereParams) && !empty($this->whereParams)) {
            $stmt = $this->conn->prepare($sql);
            $types = str_repeat('s', count($this->whereParams));
            $stmt->bind_param($types, ...$this->whereParams);
            $stmt->execute();
            return $stmt->get_result();
        }
        
        return mysqli_query($this->conn, $sql);
    }
    
    public function getPage() {
        return $this->page;
    }
    
    public function getPerPage() {
        return $this->perPage;
    }
    
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    public function getOffset() {
        return $this->offset;
    }
    
    public function getPaginationLinks($baseUrl = '?') {
        if ($this->totalPages <= 1) return '';
        
        $html = '<div class="pagination">';
        
        // Previous button
        if ($this->page > 1) {
            $html .= '<a href="' . $baseUrl . '&page=' . ($this->page - 1) . '" class="pagination-btn">&laquo; Prev</a>';
        }
        
        // Page numbers
        $start = max(1, $this->page - 2);
        $end = min($this->totalPages, $this->page + 2);
        
        if ($start > 1) {
            $html .= '<a href="' . $baseUrl . '&page=1" class="pagination-btn">1</a>';
            if ($start > 2) $html .= '<span class="pagination-ellipsis">...</span>';
        }
        
        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $this->page) ? ' active' : '';
            $html .= '<a href="' . $baseUrl . '&page=' . $i . '" class="pagination-btn' . $active . '">' . $i . '</a>';
        }
        
        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) $html .= '<span class="pagination-ellipsis">...</span>';
            $html .= '<a href="' . $baseUrl . '&page=' . $this->totalPages . '" class="pagination-btn">' . $this->totalPages . '</a>';
        }
        
        // Next button
        if ($this->page < $this->totalPages) {
            $html .= '<a href="' . $baseUrl . '&page=' . ($this->page + 1) . '" class="pagination-btn">Next &raquo;</a>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    public function getLimitInfo() {
        $start = $this->offset + 1;
        $end = min($this->offset + $this->perPage, $this->totalRecords);
        return "Showing $start to $end of {$this->totalRecords} records";
    }
}

function paginate($conn, $table, $perPage = 15, $where = '', $params = [], $orderBy = 'id DESC') {
    $paginator = new Pagination($conn, $table, $perPage);
    if ($where) {
        $paginator->setWhere($where, $params);
    }
    $paginator->setOrderBy($orderBy);
    $paginator->getTotal();
    return $paginator;
}