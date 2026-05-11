<?php

include '../config/db.php';
// DELETE APPOINTMENT
if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM appointments WHERE id=$id");

    header("Location: bookings.php");
    exit;
}


// ACCEPT APPOINTMENT
if(isset($_GET['accept'])){

    $id = (int)$_GET['accept'];

    mysqli_query($conn,
    "UPDATE appointments SET status='Accepted' WHERE id=$id");

    header("Location: bookings.php");
    exit;
}


// REJECT APPOINTMENT
if(isset($_GET['reject'])){

    $id = (int)$_GET['reject'];

    mysqli_query($conn,
    "UPDATE appointments SET status='Rejected' WHERE id=$id");

    header("Location: bookings.php");
    exit;
}

$page_title = "Appointments";
require_once 'includes/header.php';



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
$sql .= " ORDER BY id ASC LIMIT ?, ?";

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
            
            
            <input type="date" name="date_from"
class="filter-input"
value="<?php echo $date_from; ?>">
            
           <input type="date" name="date_to"
class="filter-input"
value="<?php echo $date_to; ?>">

                <select name="pet_type" class="filter-input">
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
        <div class="stat-card-icon"><svg xmlns="http://www.w3.org/2000/svg" 
width="28" 
height="28" 
fill="none" 
viewBox="0 0 24 24" 
stroke="currentColor">

<path stroke-linecap="round" 
stroke-linejoin="round" 
stroke-width="2" 
d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>

</svg></div>
        <div class="stat-card-value"><?php echo $stats['total']; ?></div>
        <div class="stat-card-label">Total Appointments</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon"><svg xmlns="http://www.w3.org/2000/svg" 
width="28" 
height="28" 
fill="none" 
viewBox="0 0 24 24" 
stroke="currentColor">

<path stroke-linecap="round" 
stroke-linejoin="round" 
stroke-width="2" 
d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-4 0a2 2 0 104 0m-4 0a2 2 0 014 0m-4 8h4"/>

</svg></div>
        <div class="stat-card-value"><?php echo $todayCount; ?></div>
        <div class="stat-card-label">Today</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon"><svg xmlns="http://www.w3.org/2000/svg" 
width="28" 
height="28" 
fill="none" 
viewBox="0 0 24 24" 
stroke="currentColor">

<path stroke-linecap="round" 
stroke-linejoin="round" 
stroke-width="2" 
d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

</svg></div>
        <div class="stat-card-value"><?php echo $stats['upcoming']; ?></div>
        <div class="stat-card-label">Upcoming</div>
    </div>
</div>

<!-- APPOINTMENTS TABLE -->
<div class="card">
    <div class="card-header">
        <h2>
            <i class="fas fa-calendar-check"></i>
            Appointments List
        </h2>

        <span style="color: var(--text-light); font-size: 14px;">
            <i class="fas fa-list"></i>
            Total: <?php echo $totalRecords; ?> appointments
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
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

<?php if($result && $result->num_rows > 0): ?>

<?php while($row = $result->fetch_assoc()): ?>

<tr data-pet-type="<?php echo $row['pet_category']; ?>">

    <!-- ID -->
    <td>
        <strong style="color: var(--primary);">
            #<?php echo $row['id']; ?>
        </strong>
    </td>

    <!-- OWNER INFO -->
    <td>

        <div style="font-weight: 600;">
            <?php echo htmlspecialchars($row['owner_name']); ?>
        </div>

        <div style="font-size:12px;color:var(--text-muted);">
            <i class="fas fa-envelope"></i>
            <?php echo htmlspecialchars($row['email']); ?>
        </div>

        <div style="font-size:12px;color:var(--text-muted);">
            <i class="fas fa-phone"></i>
            <?php echo htmlspecialchars($row['phone']); ?>
        </div>

    </td>

    <!-- PET DETAILS -->
    <td>

        <div style="display:flex;align-items:center;gap:8px;">

            <span style="font-size:24px;">

                <?php if($row['pet_category'] == 'Dog'): ?>

                    <i class="fas fa-dog"></i>

                <?php elseif($row['pet_category'] == 'Cat'): ?>

                    <i class="fas fa-cat"></i>

                <?php else: ?>

                    <i class="fas fa-paw"></i>

                <?php endif; ?>

            </span>

            <div>

                <div style="font-weight:600;">
                    <?php echo htmlspecialchars($row['pet_name']); ?>
                </div>

                <div style="font-size:11px;color:var(--text-muted);">
                    <?php echo htmlspecialchars($row['pet_category']); ?>
                    -
                    <?php echo htmlspecialchars($row['breed']); ?>
                </div>

                <div style="font-size:11px;color:var(--text-muted);">
                    Size:
                    <?php echo htmlspecialchars($row['pet_size']); ?>
                    |
                    Qty:
                    <?php echo $row['pet_count']; ?>
                </div>

            </div>

        </div>

    </td>

    <!-- SERVICE -->
    <td>

        <div style="font-weight:500;">
            <?php echo htmlspecialchars($row['main_service']); ?>
        </div>

        <?php if($row['addons']): ?>

        <div style="font-size:11px;color:var(--text-muted);">
            +
            <?php echo htmlspecialchars($row['addons']); ?>
        </div>

        <?php endif; ?>

    </td>

    <!-- DATE -->
    <td>

        <div>
            <i class="fas fa-calendar"></i>

            <?php echo date('M d, Y', strtotime($row['appointment_date'])); ?>
        </div>

        <div style="font-size:12px;color:var(--text-muted);">
            <i class="fas fa-clock"></i>

            <?php echo htmlspecialchars($row['appointment_time']); ?>
        </div>

    </td>

    <!-- NOTES -->
    <td>

        <?php echo htmlspecialchars($row['notes'] ?: '-'); ?>

    </td>

    <td>

<?php
$payment = isset($row['payment_method'])
    ? $row['payment_method']
    : 'cash';
?>

<?php if($payment == 'online'): ?>

    <span style="
        background:#e7fff1;
        color:#00a651;
        padding:6px 14px;
        border-radius:30px;
        font-size:12px;
        font-weight:700;
    ">
        Online
    </span>

<?php else: ?>

    <span style="
        background:#fff4e5;
        color:#ff9800;
        padding:6px 14px;
        border-radius:30px;
        font-size:12px;
        font-weight:700;
    ">
        Cash
    </span>

<?php endif; ?>

</td>
    <!-- STATUS -->
    <td>

        <?php if($row['status'] == 'Accepted'): ?>

            <span class="status-badge accepted">
                Accepted
            </span>

        <?php elseif($row['status'] == 'Rejected'): ?>

            <span class="status-badge rejected">
                Rejected
            </span>

        <?php else: ?>

            <span class="status-badge pending">
                Pending
            </span>

        <?php endif; ?>

    </td>

    <!-- ACTIONS -->
    <td>

        <div class="action-buttons">

            <!-- VIEW -->
            <button class="btn btn-secondary btn-sm"
                onclick="openModal('viewModal<?php echo $row['id']; ?>')">

                <i class="fas fa-eye"></i>

            </button>

            <!-- ACCEPT -->
           <a href="https://wa.me/91<?php echo $row['phone']; ?>?text=<?php echo urlencode('Hello '.$row['owner_name'].',

            Your appointment for '.$row['pet_name'].' has been accepted by AB Pet Grooming 

            Date: '.$row['appointment_date'].'
            Time: '.$row['appointment_time'].'

            Thank you '); ?>"
            target="_blank"
            class="btn btn-success btn-sm">

            <i class="fab fa-whatsapp"></i> Accept</a>

            <!-- REJECT -->
            <a href="?reject=<?php echo $row['id']; ?>"
               class="btn btn-warning btn-sm"
               title="Reject">

                <i class="fas fa-times"></i>

            </a>

            <!-- DELETE -->
            <a href="?delete=<?php echo $row['id']; ?>"
               class="btn btn-danger btn-sm"
               title="Delete"
               onclick="return confirm('Delete this appointment?')">

                <i class="fas fa-trash"></i>

            </a>

        </div>

    </td>

</tr>

<!-- VIEW MODAL -->
<div id="viewModal<?php echo $row['id']; ?>" class="modal">

    <div class="modal-content" style="max-width:600px;">

        <div class="modal-header">

            <h2>
                <i class="fas fa-calendar-check"></i>
                Appointment #<?php echo $row['id']; ?>
            </h2>

            <button class="modal-close"
                onclick="closeModal('viewModal<?php echo $row['id']; ?>')">

                &times;

            </button>

        </div>

        Add<div class="modal-body">

    <div class="info-grid">

        <div class="info-item">
            <div class="info-item-label">Owner Name</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['owner_name']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Email</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['email']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Phone</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['phone']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Pet Name</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['pet_name']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Pet Category</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['pet_category']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Breed</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['breed']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Pet Size</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['pet_size']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Pet Quantity</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['pet_count']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Main Service</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['main_service']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Addons</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['addons'] ?: 'None'); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Appointment Date</div>
            <div class="info-item-value">
                <?php echo date('M d, Y', strtotime($row['appointment_date'])); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Appointment Time</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['appointment_time']); ?>
            </div>
        </div>

        <div class="info-item">
            <div class="info-item-label">Status</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['status']); ?>
            </div>
        </div>

        

        <?php if(!empty($row['multi_pet_note'])): ?>

        <div class="info-item" style="grid-column: span 2;">
            <div class="info-item-label">Multi Pet Note</div>
            <div class="info-item-value">
                <?php echo htmlspecialchars($row['multi_pet_note']); ?>
            </div>
        </div>

        <?php endif; ?>

        <div class="info-item" style="grid-column: span 2;">
            <div class="info-item-label">Created At</div>
            <div class="info-item-value">
                <?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?>
            </div>
        </div>

        <div class="info-item-notes-box">
            <div class="info-item-label">Notes</div>
            <div class="info-item-value-notes-content">
                <?php echo htmlspecialchars($row['notes'] ?: 'No notes'); ?>
            </div>
        </div>

    </div>

</div>
        </div>

        <div class="modal-footer"
            style="margin-top:20px;text-align:right;">

            <button class="btn btn-secondary"
                onclick="closeModal('viewModal<?php echo $row['id']; ?>')">

                Close

            </button>

        </div>

    </div>

</div>

<?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="8" style="text-align:center;padding:60px;">

        <div class="empty-state">

            <div class="empty-state-icon">
                <i class="fas fa-calendar-check"></i>
            </div>

            <h3>No Appointments Found</h3>

            <p>
                There are no appointments matching your criteria.
            </p>

        </div>

    </td>
</tr>

<?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>
</div>

<style>

/* TABLE WRAPPER */
.table-responsive{
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    display: block;
    padding-bottom: 10px;
}

/* TABLE */
#appointmentsTable{
    min-width: 1450px;
    width: 1450px;
    border-collapse: collapse;
}

/* CELLS */
#appointmentsTable th,
#appointmentsTable td{
    white-space: nowrap;
    padding: 16px 18px;
    vertical-align: middle;
}

/* ACTION BUTTONS */
.action-buttons{
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;
}

/* MAIN CONTENT */
.main-content{
    width: 100%;
    overflow-x: hidden;
}

/* CARD FIX */
.card-body{
    overflow-x: auto;
}

/* ACTION BUTTONS */
.action-buttons{
    display:flex;
    gap:6px;
    flex-wrap:nowrap;
}

.action-buttons .btn{
    padding:6px 10px;
    font-size:12px;
}

/* STATUS */
.status-badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
}

.status-badge.accepted{
    background:#dcfce7;
    color:#166534;
}

.status-badge.rejected{
    background:#fee2e2;
    color:#991b1b;
}

.status-badge.pending{
    background:#fef3c7;
    color:#92400e;
}

/* BUTTON COLORS */
.btn-success{
    background:#22c55e;
    color:#fff;
}

.btn-warning{
    background:#f59e0b;
    color:#fff;
}


</style>
                            
        
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
 
 /* FILTER SECTION FIX */
.filter-section{
    display:flex;
    flex-wrap:wrap;
    gap:14px;
    align-items:center;
}

/* SEARCH BOX */
.search-box{
    position:relative;
    flex:2;
    min-width:320px;
}

.search-box input{
    width:100%;
    height:52px;
    border:2px solid #e6ddf5;
    border-radius:14px;
    padding:0 18px 0 52px;
    font-size:15px;
    background:#fff;
}

/* ICON */
.search-box i{
    position:absolute;
    left:18px;
    top:50%;
    transform:translateY(-50%);
    color:#7158a6;
    font-size:18px;
}

/* INPUTS + SELECT */
.filter-input{
    height:52px;
    padding:0 16px;
    border:2px solid #e6ddf5;
    border-radius:14px;
    min-width:170px;
    background:#fff;
}

/* BUTTONS */
.filter-section .btn{
    height:52px;
    padding:0 22px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    white-space:nowrap;
}

/* MOBILE */
@media(max-width:768px){

    .filter-section{
        flex-direction:column;
        align-items:stretch;
    }

    .search-box,
    .filter-input,
    .filter-section .btn{
        width:100%;
    }
}

.status-badge{
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
    display:inline-block;
}

.accepted{
    background:#dcfce7;
    color:#166534;
}

.rejected{
    background:#fee2e2;
    color:#991b1b;
}

.pending{
    background:#fef3c7;
    color:#92400e;
}

.btn-success{
    background:#22c55e;
    color:#fff;
}

.btn-warning{
    background:#f59e0b;
    color:#fff;
}

.info-item-value{
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    line-height: 1.6;
}

.info-item{
    overflow: hidden;
}

.modal-content{
    overflow-x: hidden;
}

.info-grid{
    align-items: stretch;
}

.notes-box{
    grid-column: 1 / -1;
}

body{
  overflow-x:hidden;
}

.card-body{
  overflow-x:auto;
}

table{
  min-width:1200px;
}

.card-body{
  overflow-x:auto;
}

.card-body::-webkit-scrollbar{
  height:8px;
}

.card-body::-webkit-scrollbar-thumb{
  background:#7158a6;
  border-radius:20px;
}

/* DETAILS MODAL BOX */
.details-box{
    background: #fff;
    border: 1px solid #ececec;
    border-radius: 14px;
    padding: 18px;
    min-height: 120px;
    overflow: hidden;
}

/* LABEL */
.details-box small{
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #999;
    margin-bottom: 8px;
    text-transform: uppercase;
}

/* CONTENT */
.details-box p{
    font-size: 16px;
    font-weight: 500;
    color: #222;
    line-height: 1.7;

    /* IMPORTANT */
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;

    max-height: 140px;
    overflow-y: auto;

    padding-right: 5px;
}

/* NOTES / MESSAGE BOX FIX */
.info-item-value{
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    line-height: 1.7;

    max-height: 160px;
    overflow-y: auto;

    padding-right: 5px;
}

.info-item-notes-box{
    grid-column: span 2;
}

.info-item-notes-box .info-item-value{
    min-height: 120px;
}


</style>


<?php require_once 'includes/footer.php'; ?>