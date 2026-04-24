<?php
$page_title = "Bookings";
require_once 'includes/header.php';

include '../config/db.php';

$bookings = mysqli_query($conn, "SELECT b.*, c.name as owner_name, c.email, c.phone, c.city, c.address, p.name as pet_name, p.type as pet_type, p.breed, p.size as pet_size, p.age as pet_age, p.color as pet_color, p.vaccinated, p.special_notes as pet_notes, s.name as service_name, s.price 
    FROM bookings b 
    LEFT JOIN customers c ON b.customer_id = c.id 
    LEFT JOIN pets p ON b.pet_id = p.id 
    LEFT JOIN services s ON b.service_id = s.id 
    ORDER BY b.id DESC");

$status_options = ['pending', 'confirmed', 'completed', 'cancelled'];
$pet_types = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
$service_list = mysqli_query($conn, "SELECT id, name, price FROM services WHERE active = 1 ORDER BY name");
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Bookings</h1>
    <p>View and manage all pet grooming appointments</p>
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
                    placeholder="Search by booking ID, customer, pet name..."
                    onkeyup="filterTable('searchInput', 'bookingsTable')"
                >
            </div>
            <select id="statusFilter" class="filter-select" onchange="filterByStatus()" style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; min-width: 160px;">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select id="petTypeFilter" class="filter-select" onchange="filterByPetType()" style="padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; min-width: 140px;">
                <option value="">All Pets</option>
                <option value="Dog">Dog</option>
                <option value="Cat">Cat</option>
                <option value="Bird">Bird</option>
                <option value="Rabbit">Rabbit</option>
            </select>
        </div>
    </div>
</div>

<!-- BOOKINGS TABLE -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-calendar-check"></i> Bookings List</h2>
        <span style="color: var(--text-light); font-size: 14px;">
            <i class="fas fa-list"></i> Total: <?php echo mysqli_num_rows($bookings); ?> bookings
        </span>
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
                    <?php if(mysqli_num_rows($bookings) > 0): ?>
                        <?php while($booking = mysqli_fetch_assoc($bookings)): ?>
                        <tr data-status="<?php echo $booking['status']; ?>" data-pet-type="<?php echo $booking['pet_type']; ?>">
                            <td>
                                <strong style="color: var(--primary);">#<?php echo $booking['id']; ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($booking['owner_name'] ?? 'N/A'); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($booking['email'] ?? '-'); ?>
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($booking['phone'] ?? '-'); ?>
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
                                            <?php echo htmlspecialchars($booking['pet_type'] ?? '-'); ?> • <?php echo htmlspecialchars($booking['breed'] ?? '-'); ?>
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted);">
                                            Size: <?php echo htmlspecialchars($booking['pet_size'] ?? '-'); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($booking['service_name'] ?? 'N/A'); ?></div>
                                <?php if($booking['notes']): ?>
                                <div style="font-size: 11px; color: var(--text-muted); max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($booking['notes']); ?>">
                                    <?php echo htmlspecialchars($booking['notes']); ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($booking['start_time'] ?? '-'); ?></div>
                            </td>
                            <td>
                                <select class="status-select" onchange="updateStatus(<?php echo $booking['id']; ?>, this.value, 'booking')" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-white); font-size: 12px; font-weight: 500;">
                                    <?php foreach ($status_options as $option): ?>
                                    <option value="<?php echo $option; ?>" <?php echo $booking['status'] === $option ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($option); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <strong style="color: var(--success);">$<?php echo number_format($booking['total_price'] ?: $booking['price'], 2); ?></strong>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($booking['status'] == 'pending'): ?>
                                    <button class="btn btn-success btn-sm" onclick="updateStatus(<?php echo $booking['id']; ?>, 'confirmed', 'booking')" title="Accept">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="updateStatus(<?php echo $booking['id']; ?>, 'cancelled', 'booking')" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-secondary btn-sm" onclick="openModal('viewBookingModal<?php echo $booking['id']; ?>')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteRow(<?php echo $booking['id']; ?>, 'booking')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                                                <div class="info-item">
                                                    <div class="info-item-label">Owner Name</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['owner_name'] ?? 'N/A'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Email</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['email'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Phone Number</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['phone'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">City/Area</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['city'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Pet Name</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['pet_name'] ?? 'N/A'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Pet Category</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['pet_type'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Breed</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['breed'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Size/Type</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['pet_size'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Age</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['pet_age'] ?? '-'); ?> years</div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Vaccinated</div>
                                                    <div class="info-item-value"><?php echo $booking['vaccinated'] ? 'Yes' : 'No'; ?></div>
                                                </div>
                                                <div class="info-item" style="grid-column: span 2;">
                                                    <div class="info-item-label">Special Notes</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['pet_notes'] ?? 'None'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Service</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['service_name'] ?? 'N/A'); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Price</div>
                                                    <div class="info-item-value" style="color: var(--success);">$<?php echo number_format($booking['total_price'] ?: $booking['price'], 2); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Check-in Date</div>
                                                    <div class="info-item-value"><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-item-label">Check-in Time</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['start_time'] ?? '-'); ?></div>
                                                </div>
                                                <div class="info-item" style="grid-column: span 2;">
                                                    <div class="info-item-label">Additional Notes</div>
                                                    <div class="info-item-value"><?php echo htmlspecialchars($booking['notes'] ?? 'None'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border); display: flex; gap: 12px; justify-content: flex-end;">
                                            <button class="btn btn-secondary" onclick="closeModal('viewBookingModal<?php echo $booking['id']; ?>')">
                                                <i class="fas fa-times"></i> Close
                                            </button>
                                            <?php if($booking['status'] == 'pending'): ?>
                                            <button class="btn btn-success" onclick="updateStatus(<?php echo $booking['id']; ?>, 'confirmed', 'booking'); closeModal('viewBookingModal<?php echo $booking['id']; ?>');">
                                                <i class="fas fa-check"></i> Accept Booking
                                            </button>
                                            <?php endif; ?>
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
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <h3>No Bookings Found</h3>
                                    <p>There are no bookings yet. New appointments will appear here.</p>
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
        <div class="stat-card-icon">📊</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows($bookings); ?></div>
        <div class="stat-card-label">Total Bookings</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">⏳</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE status = 'pending'")); ?></div>
        <div class="stat-card-label">Pending</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">✅</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE status = 'confirmed'")); ?></div>
        <div class="stat-card-label">Confirmed</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">💰</div>
        <div class="stat-card-value">$<?php echo number_format(mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_price), 0) as total FROM bookings WHERE status = 'completed'"))['total'], 2); ?></div>
        <div class="stat-card-label">Total Revenue</div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
function filterByStatus() {
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#bookingsTable tbody tr');
    
    rows.forEach(row => {
        if (statusFilter === '' || row.getAttribute('data-status') === statusFilter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterByPetType() {
    const petTypeFilter = document.getElementById('petTypeFilter').value;
    const rows = document.querySelectorAll('#bookingsTable tbody tr');
    
    rows.forEach(row => {
        if (petTypeFilter === '' || row.getAttribute('data-pet-type') === petTypeFilter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>