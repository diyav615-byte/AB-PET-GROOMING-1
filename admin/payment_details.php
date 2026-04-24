<?php
$page_title = "Payment Details";
require_once 'includes/header.php';

include '../config/db.php';

$payments = mysqli_query($conn, "SELECT p.*, b.booking_id, c.name as customer_name 
    FROM payments p 
    LEFT JOIN bookings b ON p.booking_id = b.id 
    LEFT JOIN customers c ON b.customer_id = c.id 
    ORDER BY p.id DESC");

$total_received = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed'"))['total'];
$total_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'pending'"))['total'];
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Payment Details</h1>
    <p>Track payments and revenue</p>
</div>

<!-- STATS GRID -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-icon">💰</div>
        <div class="stat-card-value">$<?php echo number_format($total_received, 2); ?></div>
        <div class="stat-card-label">Total Received</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">⏳</div>
        <div class="stat-card-value">$<?php echo number_format($total_pending, 2); ?></div>
        <div class="stat-card-label">Pending Payments</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">📊</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows($payments); ?></div>
        <div class="stat-card-label">Total Transactions</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">✅</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM payments WHERE status = 'completed'")); ?></div>
        <div class="stat-card-label">Completed</div>
    </div>
</div>

<!-- PAYMENTS TABLE -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-credit-card"></i> Transaction History</h2>
        <span style="color: var(--text-light); font-size: 14px;">
            <i class="fas fa-list"></i> Total: <?php echo mysqli_num_rows($payments); ?> transactions
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Booking ID</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($payments) > 0): ?>
                        <?php while($payment = mysqli_fetch_assoc($payments)): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--primary);">#<?php echo $payment['id']; ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($payment['customer_name'] ?? 'N/A'); ?></div>
                            </td>
                            <td>
                                #<?php echo $payment['booking_id'] ?? '-'; ?>
                            </td>
                            <td>
                                <strong style="color: var(--success); font-size: 16px;">$<?php echo number_format($payment['amount'], 2); ?></strong>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($payment['payment_method'] ?? '-'); ?>
                            </td>
                            <td>
                                <code style="font-size: 12px;"><?php echo htmlspecialchars($payment['transaction_id'] ?? '-'); ?></code>
                            </td>
                            <td>
                                <?php 
                                $status = $payment['status'] ?? 'pending';
                                $status_class = match($status) {
                                    'completed' => 'status-approved',
                                    'failed' => 'status-rejected',
                                    default => 'status-pending'
                                };
                                ?>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('M d, Y', strtotime($payment['created_at'])); ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 60px;">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <h3>No Payments Yet</h3>
                                    <p>Payment transactions will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>