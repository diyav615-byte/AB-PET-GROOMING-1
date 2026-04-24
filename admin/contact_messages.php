<?php
$page_title = "Contact Messages";
require_once 'includes/header.php';

include '../config/db.php';

// Handle delete message
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM contact_messages WHERE id = $id");
    $_SESSION['toast'] = ['message' => 'Message deleted!', 'type' => 'success'];
    header('Location: contact_messages.php');
    exit;
}

$messages = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY id DESC");
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Contact Messages</h1>
    <p>View messages sent from the contact form</p>
</div>

<!-- MESSAGES TABLE -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-envelope"></i> Messages</h2>
        <span style="color: var(--text-light); font-size: 14px;">
            <i class="fas fa-list"></i> Total: <?php echo mysqli_num_rows($messages); ?> messages
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($messages) > 0): ?>
                        <?php while($msg = mysqli_fetch_assoc($messages)): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--primary);">#<?php echo $msg['id']; ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($msg['name']); ?></div>
                            </td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" style="color: var(--primary);">
                                    <?php echo htmlspecialchars($msg['email']); ?>
                                </a>
                            </td>
                            <td>
                                <a href="tel:<?php echo htmlspecialchars($msg['phone']); ?>" style="color: var(--text-light);">
                                    <?php echo htmlspecialchars($msg['phone'] ?? '-'); ?>
                                </a>
                            </td>
                            <td>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($msg['subject']); ?></div>
                            </td>
                            <td>
                                <div style="max-width: 200px; font-size: 13px;">
                                    <?php echo htmlspecialchars($msg['message']); ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <?php echo date('M d, Y', strtotime($msg['created_at'])); ?>
                                    <br>
                                    <?php echo isset($msg['created_at']) ? date('h:i A', strtotime($msg['created_at'])) : ''; ?>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>?subject=Re: <?php echo urlencode($msg['subject']); ?>" class="btn btn-primary btn-sm" title="Reply">
                                        <i class="fas fa-reply"></i>
                                    </a>
                                    <a href="contact_messages.php?delete=<?php echo $msg['id']; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this message?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 60px;">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h3>No Messages</h3>
                                    <p>Contact form submissions will appear here.</p>
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