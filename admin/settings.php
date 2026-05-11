<?php
include '../config/db.php';

$admin_id = $_SESSION['admin_id'] ?? 1;

$query = mysqli_query(
    $conn,
    "SELECT * FROM admin_users WHERE id='$admin_id'"
);

$admin = mysqli_fetch_assoc($query);

$page_title = "Settings";
require_once 'includes/header.php';
?>


<div class="page-header">
    <h1>Settings</h1>
    <p>Manage your account settings</p>
</div>

<div class="settings-container">

    <!-- USERNAME -->

    <div class="settings-card">

        <h2>Change Username</h2>

        <form method="POST" action="update_username.php">

            <div class="form-group">
                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    value="<?php echo htmlspecialchars($admin['username'] ?? 'admin'); ?>"
                    required
                >
            </div>

            <button type="submit" class="save-btn">
                Update Username
            </button>

        </form>

    </div>


    <!-- PASSWORD -->

    <div class="settings-card">

        <h2>Change Password</h2>

        <form method="POST" action="update_password.php">

            <div class="form-group">
                <label>Old Password</label>

                <input
                    type="password"
                    name="old_password"
                    required
                >
            </div>

            <div class="form-group">
                <label>New Password</label>

                <input
                    type="password"
                    name="new_password"
                    required
                >
            </div>

            <div class="form-group">
                <label>Confirm Password</label>

                <input
                    type="password"
                    name="confirm_password"
                    required
                >
            </div>

           <button type="submit" class="save-btn">
    Update Password</button>
        </form>

    </div>

</div>

<?php require_once 'includes/footer.php'; ?>