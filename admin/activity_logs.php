<?php
$page_title = "Activity Logs";
require_once 'includes/header.php';

include '../config/db.php';
require_once 'includes/Pagination.php';

$entity_type = $_GET['entity_type'] ?? '';
$action = $_GET['action'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

$whereConditions = [];
$params = [];
$types = '';

if ($entity_type) {
    $whereConditions[] = "al.entity_type = ?";
    $params[] = $entity_type;
    $types .= 's';
}

if ($action) {
    $whereConditions[] = "al.action = ?";
    $params[] = $action;
    $types .= 's';
}

if ($date_from) {
    $whereConditions[] = "al.created_at >= ?";
    $params[] = $date_from . ' 00:00:00';
    $types .= 's';
}

if ($date_to) {
    $whereConditions[] = "al.created_at <= ?";
    $params[] = $date_to . ' 23:59:59';
    $types .= 's';
}

$whereClause = implode(' AND ', $whereConditions);

// Pagination for activity logs
$perPage = 20;
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

// Get total count
$countSql = "SELECT COUNT(*) as total FROM activity_logs al";
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
$offset = ($page - 1) * $perPage;

// Get logs
$sql = "SELECT al.*, au.username as admin_username 
        FROM activity_logs al
        LEFT JOIN admin_users au ON al.admin_user_id = au.id";

if ($whereClause) {
    $sql .= " WHERE {$whereClause}";
}

$sql .= " ORDER BY al.created_at DESC LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$params[] = $offset;
$params[] = $perPage;
$types .= 'ii';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$logs = $stmt->get_result();

// Get summary stats
$statsSql = "SELECT 
    action,
    COUNT(*) as count 
    FROM activity_logs 
    GROUP BY action 
    ORDER BY count DESC";
$statsResult = mysqli_query($conn, $statsSql);
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Activity Logs</h1>
    <p>Track all admin actions and changes</p>
</div>

<!-- FILTERS -->
<div class="card mb-24">
    <div class="card-body">
        <form method="GET" class="filter-section" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            <select name="entity_type" style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; min-width: 140px;">
                <option value="">All Entities</option>
                <option value="booking" <?php echo $entity_type === 'booking' ? 'selected' : ''; ?>>Booking</option>
                <option value="customer" <?php echo $entity_type === 'customer' ? 'selected' : ''; ?>>Customer</option>
                <option value="pet" <?php echo $entity_type === 'pet' ? 'selected' ?>>Pet</option>
                <option value="service" <?php echo $entity_type === 'service' ? 'selected' : ''; ?>>Service</option>
                <option value="review" <?php echo $entity_type === 'review' ? 'selected' : ''; ?>>Review</option>
            </select>
            
            <select name="action" style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; min-width: 140px;">
                <option value="">All Actions</option>
                <option value="create" <?php echo $action === 'create' ? 'selected' : ''; ?>>Create</option>
                <option value="update" <?php echo $action === 'update' ? 'selected' : ''; ?>>Update</option>
                <option value="delete" <?php echo $action === 'delete' ? 'selected' : ''; ?>>Delete</option>
                <option value="status_change" <?php echo $action === 'status_change' ? 'selected' : ''; ?>>Status Change</option>
            </select>
            
            <input type="date" name="date_from" value="<?php echo $date_from; ?>" 
                style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px;">
            
            <input type="date" name="date_to" value="<?php echo $date_to; ?>" 
                style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px;">
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
            
            <a href="activity_logs.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Clear
            </a>
        </form>
    </div>
</div>

<!-- STATS -->
<div class="stats-grid mb-24">
    <?php while($stat = $statsResult->fetch_assoc()): ?>
    <div class="stat-card">
        <div class="stat-card-icon">
            <?php switch($stat['action']) {
                case 'create': echo '➕'; break;
                case 'update': echo '✏️'; break;
                case 'delete': echo '🗑️'; break;
                case 'status_change': echo '🔄'; break;
                default: echo '📋';
            } ?>
        </div>
        <div class="stat-card-value"><?php echo $stat['count']; ?></div>
        <div class="stat-card-label"><?php echo ucfirst($stat['action']); ?></div>
    </div>
    <?php endwhile; ?>
</div>

<!-- LOGS TABLE -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-history"></i> Activity History</h2>
        <span style="color: var(--text-light); font-size: 14px;">
            <i class="fas fa-list"></i> Total: <?php echo $totalRecords; ?> activities
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>Entity ID</th>
                        <th>Details</th>
                        <th>IP Address</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($logs && $logs->num_rows > 0): ?>
                        <?php while($log = $logs->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?php echo $log['id']; ?></strong></td>
                            <td>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($log['admin_username'] ?? 'System'); ?></div>
                            </td>
                            <td>
                                <?php 
                                $actionColors = [
                                    'create' => 'success',
                                    'update' => 'info',
                                    'delete' => 'danger',
                                    'status_change' => 'warning'
                                ];
                                $color = $actionColors[$log['action']] ?? 'default';
                                ?>
                                <span class="status-badge status-<?php echo $color; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $log['action'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-primary">
                                    <?php echo ucfirst($log['entity_type']); ?>
                                </span>
                            </td>
                            <td>#<?php echo $log['entity_id']; ?></td>
                            <td>
                                <?php if($log['old_data'] || $log['new_data']): ?>
                                <button class="btn btn-sm btn-outline" onclick="toggleDetails(<?php echo $log['id']; ?>)">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <div id="details_<?php echo $log['id']; ?>" style="display: none; margin-top: 8px; padding: 8px; background: var(--bg-soft); border-radius: 4px; font-size: 12px;">
                                    <strong>Old:</strong> <?php echo htmlspecialchars($log['old_data'] ?? 'N/A'); ?><br>
                                    <strong>New:</strong> <?php echo htmlspecialchars($log['new_data'] ?? 'N/A'); ?>
                                </div>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 12px; color: var(--text-muted);">
                                <?php echo htmlspecialchars($log['ip_address'] ?? '-'); ?>
                            </td>
                            <td>
                                <div><?php echo date('M d, Y', strtotime($log['created_at'])); ?></div>
                                <div style="font-size: 11px; color: var(--text-muted);"><?php echo date('h:i A', strtotime($log['created_at'])); ?></div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 60px;">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-history"></i></div>
                                    <h3>No Activity Found</h3>
                                    <p>There are no activities matching your criteria.</p>
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
                Showing <?php echo min($totalRecords, 1); ?> to <?php echo min($totalRecords, $perPage); ?> of <?php echo $totalRecords; ?> records
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

<script>
function toggleDetails(id) {
    var details = document.getElementById('details_' + id);
    if (details.style.display === 'none') {
        details.style.display = 'block';
    } else {
        details.style.display = 'none';
    }
}
</script>

<style>
.pagination { display: flex; gap: 8px; flex-wrap: wrap; }
.pagination-btn { padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: var(--text); font-size: 14px; transition: all 0.2s; }
.pagination-btn:hover { background: var(--bg-soft); }
.pagination-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
</style>

<?php require_once 'includes/footer.php'; ?>