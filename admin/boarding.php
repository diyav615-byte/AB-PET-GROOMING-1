<?php
require_once '../includes/bootstrap.php';
include '../config/db.php';

$page_title = "Boarding";
require_once 'includes/header.php';

$boarding = mysqli_query($conn, "SELECT * FROM boarding ORDER BY id ASC");
$status_options = ['active', 'completed', 'cancelled'];
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Boarding Management</h1>
    <p>View and manage all pet boarding reservations</p>
</div>

<!-- FILTER SECTION -->
<div class="card mb-24">
    <div class="card-body">

        <div class="filter-section">

            <!-- SEARCH -->
            <div class="search-box">
                <i class="fas fa-search"></i>

                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search by owner, phone, pet name..."
                >
            </div>
           

            <!-- STATUS FILTER -->
            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <!-- PET FILTER -->
            <select id="petFilter" class="filter-select">
                <option value="">All Pets</option>
                <option value="dog">Dog</option>
                <option value="cat">Cat</option>
            </select>

            <!-- FILTER BUTTON -->
            <button class="btn btn-primary" onclick="applyFilters()">
                <i class="fas fa-filter"></i>
                Filter
            </button>

            <!-- CLEAR BUTTON -->
            <button class="btn btn-light" onclick="clearFilters()">
                <i class="fas fa-times"></i>
                Clear
            </button>

        </div>

    </div>
</div>


<!-- BOARDING TABLE -->
<div class="card">

    <div class="card-header">
        <h2><i class="fas fa-hotel"></i> Boarding Reservations</h2>

        <span style="color: var(--text-light); font-size: 14px;">
            <i class="fas fa-list"></i>
            Total: <?php echo mysqli_num_rows($boarding); ?> reservations
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
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(mysqli_num_rows($boarding) > 0): ?>

                    <?php while($row = mysqli_fetch_assoc($boarding)): ?>

                    <tr
                        data-status="<?php echo strtolower($row['status'] ?? ''); ?>"
                        data-pet="<?php echo strtolower($row['pet_type'] ?? ''); ?>"
                    >

                        <!-- ID -->
                        <td>
                            <strong style="color: var(--primary);">
                                #<?php echo $row['id']; ?>
                            </strong>
                        </td>

                        <!-- OWNER -->
                        <td>
                            <div style="font-weight:600;">
                                <?php echo htmlspecialchars($row['owner_name'] ?? 'N/A'); ?>
                            </div>

                            <div class="small-text">
                                <i class="fas fa-phone"></i>
                                <?php echo htmlspecialchars($row['phone'] ?? '-'); ?>
                            </div>

                            <div class="small-text">
                                <i class="fas fa-envelope"></i>
                                <?php echo htmlspecialchars($row['email'] ?? '-'); ?>
                            </div>

                            <div class="small-text">
                                <i class="fas fa-city"></i>
                                <?php echo htmlspecialchars($row['city'] ?? '-'); ?>
                            </div>
                        </td>

                        <!-- PET -->
                        <td>

                            <div class="pet-box">

                                <span class="pet-icon">

                                <?php if(($row['pet_type'] ?? '') == 'Dog'): ?>

                                    <i class="fas fa-dog"></i>

                                <?php elseif(($row['pet_type'] ?? '') == 'Cat'): ?>

                                    <i class="fas fa-cat"></i>

                                <?php else: ?>

                                    <i class="fas fa-paw"></i>

                                <?php endif; ?>

                                </span>

                                <div>

                                    <div style="font-weight:600;">
                                        <?php echo htmlspecialchars($row['pet_name'] ?? 'N/A'); ?>
                                    </div>

                                    <div class="small-text">
                                        <?php echo htmlspecialchars($row['pet_type'] ?? '-'); ?>
                                        •
                                        <?php echo htmlspecialchars($row['breed'] ?? '-'); ?>
                                    </div>

                                    <div class="small-text">
                                        Age:
                                        <?php echo htmlspecialchars($row['age'] ?? '-'); ?>
                                        yrs
                                        •
                                        Gender:
                                        <?php echo htmlspecialchars($row['gender'] ?? '-'); ?>
                                    </div>

                                </div>

                            </div>

                        </td>

                        <!-- PLAN -->
                        <td>

                            <div style="font-weight:600;">
                                <?php echo htmlspecialchars($row['plan'] ?? 'N/A'); ?>
                            </div>

                            <div class="small-text">
                                Type:
                                <?php echo htmlspecialchars($row['boarding_type'] ?? '-'); ?>
                            </div>

                        </td>

                        <!-- DATES -->
                        <td>

                            <div>
                                <i class="fas fa-sign-in-alt"></i>

                                <?php
                                echo !empty($row['checkin_date'])
                                ? date('M d, Y', strtotime($row['checkin_date']))
                                : '-';
                                ?>
                            </div>

                            <div class="small-text">
                                <i class="fas fa-sign-out-alt"></i>

                                <?php
                                echo !empty($row['checkout_date'])
                                ? date('M d, Y', strtotime($row['checkout_date']))
                                : '-';
                                ?>
                            </div>

                        </td>

                        <!-- VACCINATED -->
                        <td>

                        <?php if(($row['vaccinated_confirm'] ?? '') == 'Yes'): ?>

                            <span class="status-badge status-confirmed">
                                Yes
                            </span>

                        <?php else: ?>

                            <span class="status-badge status-cancelled">
                                No
                            </span>

                        <?php endif; ?>

                        </td>

                        <td>

<?php if($row['payment_method'] == 'online'): ?>

    <span style="
        background:#e7fff1;
        color:#00a651;
        padding:6px 12px;
        border-radius:30px;
        font-size:12px;
        font-weight:700;
    ">
        Online Paid
    </span>

<?php else: ?>

    <span style="
        background:#fff4dd;
        color:#ff9800;
        padding:6px 12px;
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

                            <select
                                class="status-select"
                                onchange="updateStatus(<?php echo $row['id']; ?>, this.value, 'boarding')"
                            >

                            <?php foreach ($status_options as $option): ?>

                                <option
                                    value="<?php echo $option; ?>"
                                    <?php echo ($row['status'] ?? '') === $option ? 'selected' : ''; ?>
                                >
                                    <?php echo ucfirst($option); ?>
                                </option>

                            <?php endforeach; ?>

                            </select>

                        </td>

                    <!-- ACTIONS -->
<td>

    <div class="action-buttons">

        <!-- VIEW BUTTON -->
        <button
            class="btn btn-secondary btn-sm"
            onclick="openModal('viewBoardingModal<?php echo $row['id']; ?>')"
        >
            <i class="fas fa-eye"></i>
        </button>

        <!-- DELETE BUTTON -->
        <button
            class="btn btn-danger btn-sm"
            onclick="deleteRow(<?php echo $row['id']; ?>, 'boarding')"
        >
            <i class="fas fa-trash"></i>
        </button>

    </div>

    <!-- VIEW MODAL -->
    <div
        id="viewBoardingModal<?php echo $row['id']; ?>"
        class="modal"
    >

        <div class="modal-content" style="max-width:750px;">

            <div class="modal-header">

                <h2>
                    <i class="fas fa-hotel"></i>
                    Boarding Details
                </h2>

                <button
                    class="modal-close"
                    onclick="closeModal('viewBoardingModal<?php echo $row['id']; ?>')"
                >
                    &times;
                </button>

            </div>

            <div class="modal-body">

                <div class="info-grid">

                    <div class="info-item">
                        <div class="info-item-label">Owner Name</div>
                        <div class="info-item-value">
                            <?php echo htmlspecialchars($row['owner_name'] ?? 'N/A'); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-label">Phone</div>
                        <div class="info-item-value">
                            <?php echo htmlspecialchars($row['phone'] ?? '-'); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-label">Email</div>
                        <div class="info-item-value">
                            <?php echo htmlspecialchars($row['email'] ?? '-'); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-label">Pet Name</div>
                        <div class="info-item-value">
                            <?php echo htmlspecialchars($row['pet_name'] ?? '-'); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-label">Pet Type</div>
                        <div class="info-item-value">
                            <?php echo htmlspecialchars($row['pet_type'] ?? '-'); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-label">Breed</div>
                        <div class="info-item-value">
                            <?php echo htmlspecialchars($row['breed'] ?? '-'); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-label">Plan</div>
                        <div class="info-item-value">
                            <?php echo htmlspecialchars($row['plan'] ?? '-'); ?>
                        </div>
                    </div>

                    
                    <div class="info-item">
                        <div class="info-item-label">Emergency Number</div>
                        <div class="info-item-value">
                            <?php echo htmlspecialchars($row['emergency_contact'] ?? '-'); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-label">Check-in</div>
                        <div class="info-item-value">
                            <?php echo $row['checkin_date']; ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-label">Check-out</div>
                        <div class="info-item-value">
                            <?php echo $row['checkout_date']; ?>
                        </div>
                    </div>

                    <div class="info-item" style="grid-column:span 2;">
                        <div class="info-item-label">Notes</div>
                        <div class="info-item-value">
                            <?php echo htmlspecialchars($row['notes'] ?? 'No notes'); ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</td>

                    </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" style="text-align:center; padding:60px;">

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

<!-- STATS -->
<div class="stats-grid mt-24">

    <div class="stat-card">
        <div class="stat-card-value">
            <?php echo mysqli_num_rows($boarding); ?>
        </div>
        <div class="stat-card-label">
            Total Boarding
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-value">
            <?php
            echo mysqli_num_rows(
                mysqli_query($conn, "SELECT id FROM boarding WHERE pet_type='Dog'")
            );
            ?>
        </div>

        <div class="stat-card-label">
            Dogs
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-value">
            <?php
            echo mysqli_num_rows(
                mysqli_query($conn, "SELECT id FROM boarding WHERE pet_type='Cat'")
            );
            ?>
        </div>

        <div class="stat-card-label">
            Cats
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-value">
            <?php
            echo mysqli_num_rows(
                mysqli_query($conn, "SELECT id FROM boarding WHERE status='active'")
            );
            ?>
        </div>

        <div class="stat-card-label">
            Active Boarding
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>

<!-- FILTER SCRIPT -->
<script>

function applyFilters(){

    const search =
    document.getElementById('searchInput')
    .value
    .toLowerCase();

    const status =
    document.getElementById('statusFilter')
    .value
    .toLowerCase();

    const pet =
    document.getElementById('petFilter')
    .value
    .toLowerCase();

    const rows =
    document.querySelectorAll('#boardingTable tbody tr');

    rows.forEach(row => {

        const text =
        row.innerText.toLowerCase();

        const rowStatus =
        row.dataset.status;

        const rowPet =
        row.dataset.pet;

        let show = true;

        if(search && !text.includes(search)){
            show = false;
        }

        if(status && rowStatus !== status){
            show = false;
        }

        if(pet && rowPet !== pet){
            show = false;
        }

        row.style.display = show ? '' : 'none';

    });

}

function clearFilters(){

    document.getElementById('searchInput').value = '';

    document.getElementById('statusFilter').value = '';

    document.getElementById('petFilter').value = '';

    applyFilters();

}

document.getElementById('searchInput')
.addEventListener('keyup', applyFilters);

document.getElementById('statusFilter')
.addEventListener('change', applyFilters);

document.getElementById('petFilter')
.addEventListener('change', applyFilters);

function deleteRow(id, table){

    if(confirm("Are you sure you want to delete this record?")){

        window.location.href =
        "delete.php?id=" + id + "&table=" + table;

    }

}

</script>

