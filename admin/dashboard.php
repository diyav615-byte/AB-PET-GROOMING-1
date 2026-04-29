<?php
$page_title = "Dashboard";
require_once 'includes/header.php';

include '../config/db.php';

// Get stats from appointments table
$total_appointments = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments"));
$today = date('Y-m-d');
$today_appointments = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE appointment_date = '$today'"));
$upcoming = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE appointment_date >= '$today'"));
$total_customers = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT phone FROM appointments"));

// Get recent appointments
$recent = mysqli_query($conn, "SELECT * FROM appointments ORDER BY id DESC LIMIT 5");

// Get appointments by date for charts (last 7 days)
$daily_labels = [];
$daily_counts = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $day = date('D', strtotime($date));
    $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM appointments WHERE appointment_date = '$date'"))['cnt'];
    $daily_labels[] = $day;
    $daily_counts[] = $count;
}

// Weekly (last 4 weeks)
$weekly_labels = [];
$weekly_counts = [];
for ($i = 3; $i >= 0; $i--) {
    $week_start = date('Y-m-d', strtotime("-" . ($i * 7 + 7) . " days"));
    $week_end = date('Y-m-d', strtotime("-" . ($i * 7) . " days"));
    $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM appointments WHERE appointment_date BETWEEN '$week_start' AND '$week_end'"))['cnt'];
    $weekly_labels[] = "Week " . (4 - $i);
    $weekly_counts[] = $count;
}

// Monthly (last 6 months)
$monthly_labels = [];
$monthly_counts = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('M', strtotime("-$i months"));
    $year_month = date('Y-m', strtotime("-$i months"));
    $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM appointments WHERE appointment_date LIKE '$year_month%'"))['cnt'];
    $monthly_labels[] = $month;
    $monthly_counts[] = $count;
}
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Welcome back! Here's your business overview.</p>
</div>

<!-- STATS GRID -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-icon">📅</div>
        <div class="stat-card-value"><?php echo $total_appointments; ?></div>
        <div class="stat-card-label">Total Appointments</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">📆</div>
        <div class="stat-card-value"><?php echo $today_appointments; ?></div>
        <div class="stat-card-label">Today's Appointments</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">⏰</div>
        <div class="stat-card-value"><?php echo $upcoming; ?></div>
        <div class="stat-card-label">Upcoming</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">👥</div>
        <div class="stat-card-value"><?php echo $total_customers; ?></div>
        <div class="stat-card-label">Total Customers</div>
    </div>
</div>

<!-- QUICK STATS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="font-size: 32px;">🐕</div>
            <div>
                <div style="font-size: 24px; font-weight: 700;">
                    <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE pet_category = 'Dog'")); ?>
                </div>
                <div style="font-size: 13px; color: var(--text-muted);">Dog Appointments</div>
            </div>
        </div>
    </div>
    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="font-size: 32px;">🐱</div>
            <div>
                <div style="font-size: 24px; font-weight: 700;">
                    <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE pet_category = 'Cat'")); ?>
                </div>
                <div style="font-size: 13px; color: var(--text-muted);">Cat Appointments</div>
            </div>
        </div>
    </div>
    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="font-size: 32px;">🛁</div>
            <div>
                <div style="font-size: 24px; font-weight: 700;">
                    <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE main_service LIKE '%Classic%'")); ?>
                </div>
                <div style="font-size: 13px; color: var(--text-muted);">Classic Packages</div>
            </div>
        </div>
    </div>
    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="font-size: 32px;">✨</div>
            <div>
                <div style="font-size: 24px; font-weight: 700;">
                    <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE addons != '' AND addons IS NOT NULL")); ?>
                </div>
                <div style="font-size: 13px; color: var(--text-muted);">With Add-ons</div>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS SECTION -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <div class="card">
        <div class="card-header">
            <h2>Daily Appointments (Last 7 Days)</h2>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Weekly Trends (Last 4 Weeks)</h2>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- MONTHLY CHART -->
<div class="card">
    <div class="card-header">
        <h2>Monthly Overview (Last 6 Months)</h2>
    </div>
    <div class="card-body">
        <div class="chart-container" style="height: 350px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<!-- RECENT APPOINTMENTS -->
<div class="card mt-24">
    <div class="card-header">
        <h2>Recent Appointments</h2>
        <a href="bookings.php" class="btn btn-primary btn-sm">
            <i class="fas fa-eye"></i>
            View All
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="recentBookingsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Owner</th>
                        <th>Pet</th>
                        <th>Service</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($recent) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($recent)): ?>
                        <tr>
                            <td><strong>#<?php echo $row['id']; ?></strong></td>
                            <td>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($row['owner_name']); ?></div>
                                <div style="font-size: 11px; color: var(--text-muted);"><?php echo htmlspecialchars($row['phone']); ?></div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 20px;">
                                        <?php if($row['pet_category'] == 'Dog'): ?><i class="fas fa-dog"></i>
                                        <?php elseif($row['pet_category'] == 'Cat'): ?><i class="fas fa-cat"></i>
                                        <?php else: ?><i class="fas fa-paw"></i>
                                        <?php endif; ?>
                                    </span>
                                    <div>
                                        <div style="font-weight: 500;"><?php echo htmlspecialchars($row['pet_name']); ?></div>
                                        <div style="font-size: 11px; color: var(--text-muted);"><?php echo htmlspecialchars($row['breed']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><?php echo htmlspecialchars($row['main_service']); ?></div>
                                <?php if($row['addons']): ?>
                                <div style="font-size: 11px; color: var(--text-muted);">+ <?php echo htmlspecialchars($row['addons']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></div>
                                <div style="font-size: 11px; color: var(--text-muted);"><?php echo $row['appointment_time']; ?></div>
                            </td>
                            <td>
                                <?php if($row['appointment_date'] >= $today): ?>
                                <span class="status-badge status-info">Upcoming</span>
                                <?php else: ?>
                                <span class="status-badge status-success">Completed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">
                                <i class="fas fa-calendar-check" style="font-size: 32px; color: var(--text-muted);"></i>
                                <p style="margin-top: 12px; color: var(--text-light);">No appointments yet</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
    // Daily Chart
    createBarChart('dailyChart', 
        <?php echo json_encode($daily_labels); ?>,
        <?php echo json_encode($daily_counts); ?>,
        'Daily Appointments'
    );

    // Weekly Chart
    createLineChart('weeklyChart',
        <?php echo json_encode($weekly_labels); ?>,
        <?php echo json_encode($weekly_counts); ?>,
        'Weekly Appointments'
    );

    // Monthly Chart
    createLineChart('monthlyChart',
        <?php echo json_encode($monthly_labels); ?>,
        <?php echo json_encode($monthly_counts); ?>,
        'Monthly Appointments'
    );
</script>