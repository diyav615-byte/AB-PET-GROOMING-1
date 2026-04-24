<?php
$page_title = "Appointments";
require_once 'includes/header.php';

include '../config/db.php';

$search = $_GET['search'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$pet_type_filter = $_GET['pet_type'] ?? '';

// Build where clause
$whereConditions = [];
$params = [];
$types = '';

if ($search) {
    $whereConditions[] = "(owner_name LIKE ? OR phone LIKE ? OR pet_name LIKE ? OR id LIKE ?)";
    $searchParam = "%{$search}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= 'ssss';
}

if ($pet_type_filter) {
    $whereConditions[] = "pet_category = ?";
    $params[] = $pet_type_filter;
    $types .= 's';
}

if ($date_from) {
    $whereConditions[] = "appointment_date >= ?";
    $params[] = $date_from;
    $types .= 's';
}

if ($date_to) {
    $whereConditions[] = "appointment_date <= ?";
    $params[] = $date_to;
    $types .= 's';
}

$whereClause = implode(' AND ', $whereConditions);

// Pagination
$perPage = 15;
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

// Get total count
$countSql = "SELECT COUNT(*) as total FROM appointments";
if ($whereClause) {
    $countSql .= " WHERE {$whereClause}";
}
$stmt = $conn->prepare($countSql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalRecords = $stmt->get_result()->fetch_assoc()['total'];

$totalPages = ceil($totalRecords / $perPage);
if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
if ($totalRecords == 0) $totalPages = 1;
$offset = ($page - 1) * $perPage;

// Get appointments
$sql = "SELECT * FROM appointments";
if ($whereClause) {
    $sql .= " WHERE {$whereClause}";
}
$sql .= " ORDER BY id DESC LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$params[] = $offset;
$params[] = $perPage;
$types .= 'ii';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Get stats
$statsSql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN appointment_date >= CURDATE() THEN 1 ELSE 0 END) as upcoming
    FROM appointments";
$stmt = $conn->prepare($statsSql);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

$today = date('Y-m-d');
$todayCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM appointments WHERE appointment_date = '$today'"))['cnt'];
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Appointments</h1>
    <p>View and manage all appointment requests</p>
</div>

<!-- FILTERS -->
<div class="card mb-24">
    <div class="card-body">
        <form method="GET" class="filter-section" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            <div class="search-box" style="flex: 1; min-width: 250px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search by name, phone, pet name..." 
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            
            <input type="date" name="date_from" value="<?php echo $date_from; ?>" 
                style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px;"
                placeholder="From Date">
            
            <input type="date" name="date_to" value="<?php echo $date_to; ?>" 
                style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px;"
                placeholder="To Date">
            
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

<!-- STATS -->
<div class="stats-grid mb-24">
    <div class="stat-card">
        <div class="stat-card-icon">📅</div>
        <div class="stat-card-value"><?php echo $stats['total']; ?></div>
        <div class="stat-card-label">Total Appointments</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">📆</div>
        <div class="stat-card-value"><?php echo $todayCount; ?></div>
        <div class="stat-card-label">Today</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">⏰</div>
        <div class="stat-card-value"><?php echo $stats['upcoming']; ?></div>
        <div class="stat-card-label">Upcoming</div>
    </div>
</div>

<!-- APPOINTMENTS TABLE -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-calendar-check"></i> Appointments List</h2>
        <span style="color: var(--text-light); font-size: 14px;">
            <i class="fas fa-list"></i> Total: <?php echo $totalRecords; ?> appointments
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="appointmentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Owner Info</th>
                        <th>Pet Details</th>
                        <th>Service</th>
                        <th>Date & Time</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr data-pet-type="<?php echo $row['pet_category']; ?>">
                            <td><strong style="color: var(--primary);">#<?php echo $row['id']; ?></strong></td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($row['owner_name']); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?>
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 24px;">
                                        <?php if($row['pet_category'] == 'Dog'): ?><i class="fas fa-dog"></i>
                                        <?php elseif($row['pet_category'] == 'Cat'): ?><i class="fas fa-cat"></i>
                                        <?php else: ?><i class="fas fa-paw"></i>
                                        <?php endif; ?>
                                    </span>
                                    <div>
                                        <div style="font-weight: 600;"><?php echo htmlspecialchars($row['pet_name']); ?></div>
                                        <div style="font-size: 11px; color: var(--text-muted);">
                                            <?php echo htmlspecialchars($row['pet_category']); ?> - <?php echo htmlspecialchars($row['breed']); ?>
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted);">
                                            Size: <?php echo htmlspecialchars($row['pet_size']); ?> | Qty: <?php echo $row['pet_count']; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($row['main_service']); ?></div>
                                <?php if($row['addons']): ?>
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    + <?php echo htmlspecialchars($row['addons']); ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($row['appointment_time']); ?></div>
                            </td>
                            <td style="max-width: 150px;">
                                <?php if($row['notes']): ?>
                                <div style="font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($row['notes']); ?>">
                                    <?php echo htmlspecialchars($row['notes']); ?>
                                </div>
                                <?php else: ?>
                                <span style="color: var(--text-muted);">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-secondary btn-sm" onclick="openModal('viewModal<?php echo $row['id']; ?>')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteRow(<?php echo $row['id']; ?>, 'appointment')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <!-- View Modal -->
                                <div id="viewModal<?php echo $row['id']; ?>" class="modal">
                                    <div class="modal-content" style="max-width: 600px;">
                                        <div class="modal-header">
                                            <h2><i class="fas fa-calendar-check"></i> Appointment #<?php echo $row['id']; ?> Details</h2>
                                            <button class="modal-close" onclick="closeModal('viewModal<?php echo $row['id']; ?>')">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="info-grid">
                                                <div class="info-item"><div class="info-item-label">Owner Name</div><div class="info-item-value"><?php echo htmlspecialchars($row['owner_name']); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Email</div><div class="info-item-value"><?php echo htmlspecialchars($row['email']); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Phone</div><div class="info-item-value"><?php echo htmlspecialchars($row['phone']); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Pet Name</div><div class="info-item-value"><?php echo htmlspecialchars($row['pet_name']); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Pet Category</div><div class="info-item-value"><?php echo htmlspecialchars($row['pet_category']); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Breed</div><div class="info-item-value"><?php echo htmlspecialchars($row['breed']); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Size</div><div class="info-item-value"><?php echo htmlspecialchars($row['pet_size']); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Number of Pets</div><div class="info-item-value"><?php echo $row['pet_count']; ?></div></div>
                                                <?php if($row['multi_pet_note']): ?>
                                                <div class="info-item" style="grid-column: span 2;"><div class="info-item-label">Multi-Pet Note</div><div class="info-item-value"><?php echo htmlspecialchars($row['multi_pet_note']); ?></div></div>
                                                <?php endif; ?>
                                                <div class="info-item"><div class="info-item-label">Main Service</div><div class="info-item-value"><?php echo htmlspecialchars($row['main_service']); ?></div></div>
                                                <?php if($row['addons']): ?>
                                                <div class="info-item"><div class="info-item-label">Add-ons</div><div class="info-item-value"><?php echo htmlspecialchars($row['addons']); ?></div></div>
                                                <?php endif; ?>
                                                <div class="info-item"><div class="info-item-label">Date</div><div class="info-item-value"><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></div></div>
                                                <div class="info-item"><div class="info-item-label">Time</div><div class="info-item-value"><?php echo htmlspecialchars($row['appointment_time']); ?></div></div>
                                                <div class="info-item" style="grid-column: span 2;"><div class="info-item-label">Notes</div><div class="info-item-value"><?php echo htmlspecialchars($row['notes'] ?: 'None'); ?></div></div>
                                                <div class="info-item" style="grid-column: span 2;"><div class="info-item-label">Created</div><div class="info-item-value"><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></div></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border); display: flex; gap: 12px; justify-content: flex-end;">
                                            <button class="btn btn-secondary" onclick="closeModal('viewModal<?php echo $row['id']; ?>')"><i class="fas fa-times"></i> Close</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 60px;">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-calendar-check"></i></div>
                                    <h3>No Appointments Found</h3>
                                    <p>There are no appointments matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 16px;">
            <div style="color: var(--text-muted); font-size: 14px;">
                Showing <?php echo min($totalRecords, $offset + 1); ?> to <?php echo min($totalRecords, $offset + $perPage); ?> of <?php echo $totalRecords; ?> records
            </div>
            <div class="pagination">
                <?php 
                $queryParams = $_GET;
                unset($queryParams['page']);
                $baseUrl = '?' . http_build_query($queryParams);
                $baseUrl = $baseUrl === '?' ? '?' : $baseUrl . '&';
                ?>
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
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.pagination { display: flex; gap: 8px; flex-wrap: wrap; }
.pagination-btn { padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: var(--text); font-size: 14px; transition: all 0.2s; }
.pagination-btn:hover { background: var(--bg-soft); }
.pagination-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
</style>

<?php require_once 'includes/footer.php'; ?>