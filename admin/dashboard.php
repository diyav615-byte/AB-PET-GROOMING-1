<?php
$page_title = "Dashboard";
require_once 'includes/header.php';

include '../config/db.php';

$total_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings"));
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_price), 0) as total FROM bookings WHERE status = 'completed'"))['total'];
$pending_requests = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE status = 'pending'"));
$total_customers = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM customers"));

$recent_bookings = mysqli_query($conn, "SELECT b.*, c.name as customer_name, c.email as customer_email, c.phone as customer_phone, p.name as pet_name, p.type as pet_type, p.breed as pet_breed, s.name as service_name 
    FROM bookings b 
    LEFT JOIN customers c ON b.customer_id = c.id 
    LEFT JOIN pets p ON b.pet_id = p.id 
    LEFT JOIN services s ON b.service_id = s.id 
    ORDER BY b.id DESC LIMIT 5");

$daily_data = [
    'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    'bookings' => [8, 12, 10, 15, 9, 18, 12],
    'revenue' => [320, 480, 400, 600, 360, 720, 480]
];

$weekly_data = [
    'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
    'bookings' => [45, 52, 48, 65],
    'revenue' => [1800, 2080, 1920, 2600]
];

$monthly_data = [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    'bookings' => [120, 145, 160, 155, 180, 195],
    'revenue' => [4800, 5800, 6400, 6200, 7200, 7800]
];
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
        <div class="stat-card-value"><?php echo $total_bookings; ?></div>
        <div class="stat-card-label">Total Bookings</div>
        <div class="stat-card-trend up">
            <i class="fas fa-arrow-up"></i>
            <span>12% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">💰</div>
        <div class="stat-card-value">$<?php echo number_format($total_revenue, 2); ?></div>
        <div class="stat-card-label">Total Revenue</div>
        <div class="stat-card-trend up">
            <i class="fas fa-arrow-up"></i>
            <span>8% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">⏳</div>
        <div class="stat-card-value"><?php echo $pending_requests; ?></div>
        <div class="stat-card-label">Pending Requests</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">👥</div>
        <div class="stat-card-value"><?php echo $total_customers; ?></div>
        <div class="stat-card-label">Total Customers</div>
        <div class="stat-card-trend up">
            <i class="fas fa-arrow-up"></i>
            <span>5% from last month</span>
        </div>
    </div>
</div>

<!-- CHARTS SECTION -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <div class="card">
        <div class="card-header">
            <h2>Daily Bookings</h2>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Weekly Trends</h2>
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
        <h2>Monthly Overview</h2>
    </div>
    <div class="card-body">
        <div class="chart-container" style="height: 350px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<!-- RECENT BOOKINGS -->
<div class="card mt-24">
    <div class="card-header">
        <h2>Recent Bookings</h2>
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
                        <th>Customer</th>
                        <th>Pet</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($recent_bookings) > 0): ?>
                        <?php while($booking = mysqli_fetch_assoc($recent_bookings)): ?>
                        <tr>
                            <td><strong>#<?php echo $booking['id']; ?></strong></td>
                            <td>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($booking['customer_name'] ?? 'N/A'); ?></div>
                                <div style="font-size: 11px; color: var(--text-muted);"><?php echo htmlspecialchars($booking['customer_email'] ?? ''); ?></div>
                            </td>
                            <td>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($booking['pet_name'] ?? 'N/A'); ?></div>
                                <div style="font-size: 11px; color: var(--text-muted);"><?php echo htmlspecialchars($booking['pet_breed'] ?? ''); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($booking['service_name'] ?? 'N/A'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $booking['status']; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </td>
                            <td><strong>$<?php echo number_format($booking['total_price'], 2); ?></strong></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <i class="fas fa-calendar-check" style="font-size: 32px; color: var(--text-muted);"></i>
                                <p style="margin-top: 12px; color: var(--text-light);">No bookings yet</p>
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
        <?php echo json_encode($daily_data['labels']); ?>,
        <?php echo json_encode($daily_data['bookings']); ?>,
        'Daily Bookings'
    );

    // Weekly Chart
    createLineChart('weeklyChart',
        <?php echo json_encode($weekly_data['labels']); ?>,
        <?php echo json_encode($weekly_data['bookings']); ?>,
        'Weekly Bookings'
    );

    // Monthly Chart
    createLineChart('monthlyChart',
        <?php echo json_encode($monthly_data['labels']); ?>,
        <?php echo json_encode($monthly_data['bookings']); ?>,
        'Monthly Bookings'
    );
</script>