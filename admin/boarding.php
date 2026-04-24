<?php
$page_title = "Boarding";
require_once 'includes/header.php';

include '../config/db.php';

$boarding = mysqli_query($conn, "SELECT * FROM boarding ORDER BY id DESC");

$status_options = ['active', 'completed', 'cancelled'];
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Boarding Management</h1>
    <p>View and manage all pet boarding reservations</p>
</div>

<!-- FILTERS -->
<div class="card mb-24">
    <div class="card-body">
        <div class="filter-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Search by owner, pet name, or ID..."
                    onkeyup="filterTable('searchInput', 'boardingTable')"
                >
            </div>
            <select id="statusFilter" class="filter-select" onchange="filterByStatus()" style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; min-width: 160px;">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>
</div>

<!-- BOARDING TABLE -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-hotel"></i> Boarding Reservations</h2>
        <span style="color: var(--text-light); font-size: 14px;">
            <i class="fas fa-list"></i> Total: <?php echo mysqli_num_rows($boarding); ?> reservations
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="boardingTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Owner Info</th>
                        <th>Pet Details</th>
                        <th>Plan & Boarding</th>
                        <th>Check-in / Check-out</th>
                        <th>Vaccinated</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($boarding) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($boarding)): ?>
                        <tr data-status="<?php echo $row['status']; ?>">
                            <td>
                                <strong style="color: var(--primary);">#<?php echo $row['id']; ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($row['owner_name'] ?? 'N/A'); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone'] ?? '-'); ?>
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email'] ?? '-'); ?>
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-city"></i> <?php echo htmlspecialchars($row['city'] ?? '-'); ?>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 24px;">
                                        <?php if(($row['pet_type'] ?? '') == 'Dog'): ?><i class="fas fa-dog"></i>
                                        <?php elseif(($row['pet_type'] ?? '') == 'Cat'): ?><i class="fas fa-cat"></i>
                                        <?php else: ?><i class="fas fa-paw"></i>
                                        <?php endif; ?>
                                    </span>
                                    <div>
                                        <div style="font-weight: 600;"><?php echo htmlspecialchars($row['pet_name'] ?? 'N/A'); ?></div>
                                        <div style="font-size: 11px; color: var(--text-muted);">
                                            <?php echo htmlspecialchars($row['pet_type'] ?? '-'); ?> • <?php echo htmlspecialchars($row['breed'] ?? '-'); ?>
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted);">
                                            Age: <?php echo htmlspecialchars($row['age'] ?? '-'); ?> yrs • Gender: <?php echo htmlspecialchars($row['gender'] ?? '-'); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($row['plan'] ?? 'N/A'); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    Type: <?php echo htmlspecialchars($row['boarding_type'] ?? '-'); ?>
                                </div>
                            </td>
                            <td>
                                <div><i class="fas fa-sign-in"></i> <?php echo date('M d, Y', strtotime($row['checkin_date'])); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-sign-out"></i> <?php echo $row['checkout_date'] ? date('M d, Y', strtotime($row['checkout_date'])) : '-'; ?>
                                </div>
                            </td>
                            <td>
                                <?php if(($row['vaccinated_confirm'] ?? '') == 'Yes'): ?>
                                <span class="status-badge status-confirmed">Yes</span>
                                <?php else: ?>
                                <span class="status-badge status-cancelled">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <select class="status-select" onchange="updateStatus(<?php echo $row['id']; ?>, this.value, 'boarding')" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-white); font-size: 12px; font-weight: 500;">
                                    <?php foreach ($status_options as $option): ?>
                                    <option value="<?php echo $option; ?>" <?php echo ($row['status'] ?? '') === $option ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($option); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-secondary btn-sm" onclick="openModal('viewBoardingModal<?php echo $row['id']; ?>')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteRow(<?php echo $row['id']; ?>, 'boarding')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- View Modal -->
                                <div id="viewBoardingModal<?php echo $row['id']; ?>" class="modal">
                                    <div class="modal-content" style="max-width: 700px;">
                                        <div class="modal-header">
                                            <h2><i class="fas fa-hotel"></i> Boarding #<?php echo $row['id']; ?> Details</h2>
                                            <button class="modal-close" onclick="closeModal('viewBoardingModal<?php echo $row['id']; ?>')">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="info-grid">
                                                <div class="info-item">
                                                    <div class="info-item-label">Owner Name</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['owner_name'] ?? 'N/A'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Phone Number</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['phone'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Email</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['email'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">City/Area</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['city'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Pet Name</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['pet_name'] ?? 'N/A'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Pet Type</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['pet_type'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Plan</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['plan'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Breed</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['breed'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Age</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['age'] ?? '-'); ?> years</div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Gender</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['gender'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Boarding Type</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['boarding_type'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Emergency Contact</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['emergency_contact'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Check-in Date</div>
                                                    <div class="info-item-value"><?php echo date('M d, Y', strtotime($row['checkin_date'])); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Check-out Date</div>
                                                    <div class="info-item-value"><?php echo $row['checkout_date'] ? date('M d, Y', strtotime($row['checkout_date'])) : '-'; ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Vaccinated</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['vaccinated_confirm'] ?? 'No'); ?></div>
                                                </div>
                                                <div class="info-item" style="grid-column: span 2;">
                                                    <div class="info-item-label">Special Notes</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($row['notes'] ?? 'None'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border); display: flex; gap: 12px; justify-content: flex-end;">
                                            <button class="btn btn-secondary" onclick="closeModal('viewBoardingModal<?php echo $row['id']; ?>')">
                                                <i class="fas fa-times"></i> Close
                                            </button>
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
                                    <div class="empty-state-icon">
                                        <i class="fas fa-hotel"></i>
                                    </div>
                                    <h3>No Boarding Reservations</h3>
                                    <p>There are no boarding reservations yet.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- STATS SUMMARY -->
<div class="stats-grid mt-24">
    <div class="stat-card">
        <div class="stat-card-icon">🏠</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows($boarding); ?></div>
        <div class="stat-card-label">Total Boarding</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">🐕</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM boarding WHERE pet_type = 'Dog'")); ?></div>
        <div class="stat-card-label">Dogs</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">🐈</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM boarding WHERE pet_type = 'Cat'")); ?></div>
        <div class="stat-card-label">Cats</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">✅</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM boarding WHERE status = 'active'")); ?></div>
        <div class="stat-card-label">Active Boarding</div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
function filterByStatus() {
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#boardingTable tbody tr');
    
    rows.forEach(row => {
        if (statusFilter === '' || row.getAttribute('data-status') === statusFilter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>