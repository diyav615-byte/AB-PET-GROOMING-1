<?php
$page_title = "Reviews";
require_once 'includes/header.php';

include '../config/db.php';

// Handle approve/reject actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'approve') {
        mysqli_query($conn, "UPDATE reviews SET status = 'approved' WHERE id = $id");
        $_SESSION['toast'] = ['message' => 'Review approved successfully!', 'type' => 'success'];
    } elseif ($action === 'reject') {
        mysqli_query($conn, "UPDATE reviews SET status = 'rejected' WHERE id = $id");
        $_SESSION['toast'] = ['message' => 'Review rejected!', 'type' => 'info'];
    } elseif ($action === 'delete') {
        mysqli_query($conn, "DELETE FROM reviews WHERE id = $id");
        $_SESSION['toast'] = ['message' => 'Review deleted!', 'type' => 'success'];
    }
    
    header('Location: reviews.php');
    exit;
}

$reviews = mysqli_query($conn, "SELECT * FROM reviews ORDER BY id DESC");
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Reviews</h1>
    <p>Manage customer reviews - Approve or reject reviews to display on the website</p>
</div>

<!-- REVIEWS TABLE -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-star"></i> Customer Reviews</h2>
        <span style="color: var(--text-light); font-size: 14px;">
            <i class="fas fa-list"></i> Total: <?php echo mysqli_num_rows($reviews); ?> reviews
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($reviews) > 0): ?>
                        <?php while($review = mysqli_fetch_assoc($reviews)): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--primary);">#<?php echo $review['id']; ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($review['name'] ?? 'Anonymous'); ?></div>
                            </td>
                            <td>
                                <div class="rating-stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $review['rating'] ? '' : 'empty-star'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted);"><?php echo $review['rating']; ?>/5</div>
                            </td>
                            <td>
                                <div style="max-width: 300px;">
                                    <?php echo htmlspecialchars($review['review']); ?>
                                </div>
                            </td>
                            <td>
                                <?php 
                                $status_class = 'status-pending';
                                if ($review['status'] === 'approved') $status_class = 'status-approved';
                                elseif ($review['status'] === 'rejected') $status_class = 'status-rejected';
                                ?>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo ucfirst($review['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($review['status'] == 'pending'): ?>
                                    <a href="reviews.php?action=approve&id=<?php echo $review['id']; ?>" class="btn btn-success btn-sm" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="reviews.php?action=reject&id=<?php echo $review['id']; ?>" class="btn btn-warning btn-sm" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <?php elseif($review['status'] == 'approved'): ?>
                                    <a href="reviews.php?action=reject&id=<?php echo $review['id']; ?>" class="btn btn-warning btn-sm" title="Unapprove">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="reviews.php?action=delete&id=<?php echo $review['id']; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this review?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 60px;">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h3>No Reviews Yet</h3>
                                    <p>Customer reviews will appear here once submitted.</p>
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
        <div class="stat-card-icon">⭐</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows($reviews); ?></div>
        <div class="stat-card-label">Total Reviews</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">⏳</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM reviews WHERE status = 'pending'")); ?></div>
        <div class="stat-card-label">Pending</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">✅</div>
        <div class="stat-card-value"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM reviews WHERE status = 'approved'")); ?></div>
        <div class="stat-card-label">Approved</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">⭐</div>
        <?php 
        $avg_rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rating) as avg FROM reviews WHERE status = 'approved'"));
        $avg = $avg_rating['avg'] ? round($avg_rating['avg'], 1) : '0';
        ?>
        <div class="stat-card-value"><?php echo $avg; ?></div>
        <div class="stat-card-label">Average Rating</div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>