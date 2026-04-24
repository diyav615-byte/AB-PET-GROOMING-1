<?php
$page_title = "Bookings";
require_once 'includes/header.php';

include '../config/db.php';
require_once 'includes/Pagination.php';
require_once 'includes/AdminUtils.php';

$search = $_GET['search'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$status_filter = $_GET['status'] ?? '';
$pet_type_filter = $_GET['pet_type'] ?? '';

// Build where clause
$whereConditions = [];
$params = [];
$types = '';

if ($search) {
    $whereConditions[] = "(c.name LIKE ? OR c.phone LIKE ? OR p.name LIKE ? OR b.id LIKE ?)";
    $searchParam = "%{$search}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= 'ssss';
}

if ($status_filter) {
    $whereConditions[] = "b.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

if ($pet_type_filter) {
    $whereConditions[] = "p.pet_type = ?";
    $params[] = $pet_type_filter;
    $types .= 's';
}

if ($date_from) {
    $whereConditions[] = "b.booking_date >= ?";
    $params[] = $date_from;
    $types .= 's';
}

if ($date_to) {
    $whereConditions[] = "b.booking_date <= ?";
    $params[] = $date_to;
    $types .= 's';
}

$whereClause = implode(' AND ', $whereConditions);

// Pagination
$paginator = new Pagination($conn, 'bookings b');
if ($whereClause) {
    $fromClause = "FROM bookings b 
        LEFT JOIN customers c ON b.customer_id = c.id 
        LEFT JOIN pets p ON b.pet_id = p.id 
        LEFT JOIN services s ON b.service_id = s.id
        WHERE {$whereClause}";
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total {$fromClause}";
    $stmt = $conn->prepare($countSql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalRecords = $row['total'];
    
    $totalPages = ceil($totalRecords / $paginator->getPerPage());
    $page = $paginator->getPage();
    if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
    $offset = ($page - 1) * $paginator->getPerPage();
    
    // Get data
    $dataSql = "SELECT b.*, c.name as customer_name, c.email, c.phone as customer_phone, c.city, 
                p.name as pet_name, p.pet_type, p.breed, p.pet_size, s.name as service_name, s.price 
                {$fromClause}
                ORDER BY b.id DESC LIMIT ?, ?";
    
    $stmt = $conn->prepare($dataSql);
    $params[] = $offset;
    $params[] = $paginator->getPerPage();
    $types .= 'ii';
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $bookings = $stmt->get_result();
} else {
    $totalRecords = $paginator->getTotal();
    $bookings = $paginator->getData();
}

// Get stats
$statsQuery = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
    SUM(CASE WHEN status = 'completed' THEN total_price ELSE 0 END) as revenue
    FROM bookings b";
if ($whereClause) {
    $statsQuery .= " WHERE {$whereClause}";
}
$stmt = $conn->prepare($statsQuery);
if ($params && !empty(array_filter($params, function($v) { return $v !== $offset && $v !== $paginator->getPerPage(); }))) {
    $filterParams = array_filter($params, function($v) use ($offset, $paginator) { 
        return $v !== $offset && $v !== $paginator->getPerPage(); 
    });
    if (!empty($filterParams)) {
        $stmt->bind_param($types, ...array_values($filterParams));
    }
}
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

$status_options = ['pending', 'confirmed', 'completed', 'cancelled'];
$pet_types = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Bookings</h1>
    <p>View and manage all pet grooming appointments</p>
</div>

<!-- FILTERS -->
<div class="card mb-24">
    <div class="card-body">
        <form method="GET" class="filter-section" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            <div class="search-box" style="flex: 1; min-width: 250px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search by ID, customer, pet name..." 
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            
            <input type="date" name="date_from" value="<?php echo $date_from; ?>" 
                style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px;"
                placeholder="From Date">
            
            <input type="date" name="date_to" value="<?php echo $date_to; ?>" 
                style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px;"
                placeholder="To Date">
            
            <select name="status" style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; min-width: 140px;">
                <option value="">All Statuses</option>
                <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            
            <select name="pet_type" style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; min-width: 120px;">
                <option value="">All Pets</option>
                <option value="Dog" <?php echo $pet_type_filter === 'Dog' ? 'selected' : ''; ?>>Dog</option>
                <option value="Cat" <?php echo $pet_type_filter === 'Cat' ? 'selected' : ''; ?>>Cat</option>
            </select>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
            
            <a href="bookings.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Clear
            </a>
        </form>
    </div>
</div>

<!-- BOOKINGS TABLE -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-calendar-check"></i> Bookings List</h2>
        <div style="display: flex; gap: 12px;">
            <span style="color: var(--text-light); font-size: 14px;">
                <i class="fas fa-list"></i> Total: <?php echo $totalRecords; ?> bookings
            </span>
            <form method="POST" action="export.php">
                <input type="hidden" name="type" value="bookings">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <input type="hidden" name="date_from" value="<?php echo $date_from; ?>">
                <input type="hidden" name="date_to" value="<?php echo $date_to; ?>">
                <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-download"></i> Export CSV
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="bookingsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Info</th>
                        <th>Pet Details</th>
                        <th>Service</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($bookings && $bookings->num_rows > 0): ?>
                        <?php while($booking = $bookings->fetch_assoc()): ?>
                        <tr data-status="<?php echo $booking['status']; ?>" data-pet-type="<?php echo $booking['pet_type']; ?>">
                            <td><strong style="color: var(--primary);">#<?php echo $booking['id']; ?></strong></td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($booking['customer_name'] ?? 'N/A'); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($booking['email'] ?? '-'); ?>
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($booking['customer_phone'] ?? '-'); ?>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 24px;">
                                        <?php if($booking['pet_type'] == 'Dog'): ?><i class="fas fa-dog"></i>
                                        <?php elseif($booking['pet_type'] == 'Cat'): ?><i class="fas fa-cat"></i>
                                        <?php else: ?><i class="fas fa-paw"></i>
                                        <?php endif; ?>
                                    </span>
                                    <div>
                                        <div style="font-weight: 600;"><?php echo htmlspecialchars($booking['pet_name'] ?? 'N/A'); ?></div>
                                        <div style="font-size: 11px; color: var(--text-muted);">
                                            <?php echo htmlspecialchars($booking['pet_type'] ?? '-'); ?> - <?php echo htmlspecialchars($booking['breed'] ?? '-'); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($booking['service_name'] ?? 'N/A'); ?></div>
                            </td>
                            <td>
                                <div><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($booking['start_time'] ?? '-'); ?></div>
                            </td>
                            <td>
                                <select class="status-select" onchange="updateStatus(<?php echo $booking['id']; ?>, this.value, 'booking')" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-white); font-size: 12px; font-weight: 500;">
                                    <?php foreach ($status_options as $option): ?>
                                    <option value="<?php echo $option; ?>" <?php echo $booking['status'] === $option ? 'selected' : ''; ?>><?php echo ucfirst($option); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><strong style="color: var(--success);">$<?php echo number_format($booking['total_price'] ?: ($booking['price'] ?? 0), 2); ?></strong></td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($booking['status'] == 'pending'): ?>
                                    <button class="btn btn-success btn-sm" onclick="updateStatus(<?php echo $booking['id']; ?>, 'confirmed', 'booking')" title="Accept"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-danger btn-sm" onclick="updateStatus(<?php echo $booking['id']; ?>, 'cancelled', 'booking')" title="Reject"><i class="fas fa-times"></i></button>
                                    <?php endif; ?>
                                    <button class="btn btn-secondary btn-sm" onclick="openModal('viewBookingModal<?php echo $booking['id']; ?>')" title="View Details"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteRow(<?php echo $booking['id']; ?>, 'booking')" title="Delete"><i class="fas fa-trash"></i></button>
                                </div>
                                
                                <!-- View Modal -->
                                <div id="viewBookingModal<?php echo $booking['id']; ?>" class="modal">
                                    <div class="modal-content" style="max-width: 700px;">
                                        <div class="modal-header">
                                            <h2><i class="fas fa-calendar-check"></i> Booking #<?php echo $booking['id']; ?> Details</h2>
                                            <button class="modal-close" onclick="closeModal('viewBookingModal<?php echo $booking['id']; ?>')">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="info-grid">
                                                <div class="info-item"><div class="info-item-label">Owner Name</div><div class="info-item-value"><?php echo htmlspecialchars($booking['customer_name'] ?? 'N/A'); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Email</div><div class="info-item-value"><?php echo htmlspecialchars($booking['email'] ?? '-'); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Phone</div><div class="info-item-value"><?php echo htmlspecialchars($booking['customer_phone'] ?? '-'); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">City</div><div class="info-item-value"><?php echo htmlspecialchars($booking['city'] ?? '-'); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Pet Name</div><div class="info-item-value"><?php echo htmlspecialchars($booking['pet_name'] ?? 'N/A'); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Pet Type</div><div class="info-item-value"><?php echo htmlspecialchars($booking['pet_type'] ?? '-'); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Service</div><div class="info-item-value"><?php echo htmlspecialchars($booking['service_name'] ?? 'N/A'); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Price</div><div class="info-item-value" style="color: var(--success);">$<?php echo number_format($booking['total_price'] ?: ($booking['price'] ?? 0), 2); ?></div></div>
                                                <div class="info-item" style="grid-column: span 2;"><div class="info-item-label">Notes</div><div class="info-item-value"><?php echo htmlspecialchars($booking['notes'] ?? 'None'); ?></div></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border); display: flex; gap: 12px; justify-content: flex-end;">
                                            <button class="btn btn-secondary" onclick="closeModal('viewBookingModal<?php echo $booking['id']; ?>')"><i class="fas fa-times"></i> Close</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 60px;">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-calendar-check"></i></div>
                                    <h3>No Bookings Found</h3>
                                    <p>There are no bookings matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php 
        $queryParams = $_GET;
        unset($queryParams['page']);
        $baseUrl = '?' . http_build_query($queryParams);
        $baseUrl = str_replace(['?'], [''], $baseUrl);
        $baseUrl = $baseUrl ? '?' . $baseUrl . '&' : '?';
        ?>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 16px;">
            <div style="color: var(--text-muted); font-size: 14px;">
                Showing <?php echo min($totalRecords, 1); ?> to <?php echo min($totalRecords, $paginator->getPerPage()); ?> of <?php echo $totalRecords; ?> records
            </div>
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                <a href="<?php echo $baseUrl; ?>page=<?php echo $page - 1; ?>" class="pagination-btn">&laquo; Prev</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <a href="<?php echo $baseUrl; ?>page=<?php echo $i; ?>" class="pagination-btn <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                <a href="<?php echo $baseUrl; ?>page=<?php echo $page + 1; ?>" class="pagination-btn">Next &raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- STATS SUMMARY -->
<div class="stats-grid mt-24">
    <div class="stat-card">
        <div class="stat-card-icon">📊</div>
        <div class="stat-card-value"><?php echo $stats['total']; ?></div>
        <div class="stat-card-label">Total Bookings</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">⏳</div>
        <div class="stat-card-value"><?php echo $stats['pending']; ?></div>
        <div class="stat-card-label">Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">✅</div>
        <div class="stat-card-value"><?php echo $stats['confirmed']; ?></div>
        <div class="stat-card-label">Confirmed</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">💰</div>
        <div class="stat-card-value">$<?php echo number_format($stats['revenue'] ?? 0, 2); ?></div>
        <div class="stat-card-label">Revenue</div>
    </div>
</div>

<style>
.pagination { display: flex; gap: 8px; flex-wrap: wrap; }
.pagination-btn { padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: var(--text); font-size: 14px; transition: all 0.2s; }
.pagination-btn:hover { background: var(--bg-soft); }
.pagination-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
</style>

<?php require_once 'includes/footer.php'; ?>