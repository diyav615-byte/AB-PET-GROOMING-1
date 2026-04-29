<?php
session_start();
require_once 'auth_check.php';

$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Dashboard'; ?> - AB Pet Grooming Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">🐾</div>
                    <span class="sidebar-logo-text">AB Pet Grooming</span>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="dashboard.php" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="services.php" class="<?php echo $current_page == 'services' ? 'active' : ''; ?>">
                        <i class="fas fa-cut"></i>
                        <span>Add Services</span>
                    </a>
                </li>
                <li>
                    <a href="add_boarding.php" class="<?php echo $current_page == 'add_boarding' ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i>
                        <span>Add Boarding</span>
                    </a>
                </li>
                <li>
                    <a href="bookings.php" class="<?php echo $current_page == 'bookings' ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-check"></i>
                        <span>Bookings</span>
                    </a>
                </li>
                <li>
                    <a href="boarding.php" class="<?php echo $current_page == 'boarding' ? 'active' : ''; ?>">
                        <i class="fas fa-hotel"></i>
                        <span>Boarding</span>
                    </a>
                </li>
                <li>
                    <a href="reviews.php" class="<?php echo $current_page == 'reviews' ? 'active' : ''; ?>">
                        <i class="fas fa-star"></i>
                        <span>Reviews</span>
                    </a>
                </li>
                <li>
                    <a href="contact_messages.php" class="<?php echo $current_page == 'contact_messages' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i>
                        <span>Contact Messages</span>
                    </a>
                </li>
                <li>
                    <a href="activity_logs.php" class="<?php echo $current_page == 'activity_logs' ? 'active' : ''; ?>">
                        <i class="fas fa-history"></i>
                        <span>Activity Logs</span>
                    </a>
                </li>
                <li>
                    <a href="payment_details.php" class="<?php echo $current_page == 'payment_details' ? 'active' : ''; ?>">
                        <i class="fas fa-credit-card"></i>
                        <span>Payment Details</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div class="user-details">
                        <h4>Admin</h4>
                        <p><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Administrator'); ?></p>
                    </div>
                </div>
                <form method="POST" action="logout.php" style="margin: 0;">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- TOP NAVBAR -->
            <div class="top-navbar">
                <div class="navbar-left">
                    <div class="navbar-title"><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Dashboard'; ?></div>
                </div>
                <div class="navbar-right">
                    <button class="nav-icon-btn" title="Search">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="nav-icon-btn" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </button>
                    <button class="nav-icon-btn" title="Settings">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>