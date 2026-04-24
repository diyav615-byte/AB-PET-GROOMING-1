<?php
$page_title = "Add Services";
require_once 'includes/header.php';

include '../config/db.php';

// Handle add/edit/delete service
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        
        mysqli_query($conn, "INSERT INTO services (name, price, description, category, active) VALUES ('$name', '$price', '$description', '$category', 1)");
        $_SESSION['toast'] = ['message' => 'Service added successfully!', 'type' => 'success'];
    } elseif (isset($_POST['update_service'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        
        mysqli_query($conn, "UPDATE services SET name = '$name', price = '$price', description = '$description', category = '$category' WHERE id = $id");
        $_SESSION['toast'] = ['message' => 'Service updated successfully!', 'type' => 'success'];
    }
    header('Location: services.php');
    exit;
}

// Handle delete service
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM services WHERE id = $id");
    $_SESSION['toast'] = ['message' => 'Service deleted!', 'type' => 'success'];
    header('Location: services.php');
    exit;
}

// Handle toggle active
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    mysqli_query($conn, "UPDATE services SET active = NOT active WHERE id = $id");
    header('Location: services.php');
    exit;
}

$services = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC");
$categories = ['Grooming', 'Boarding', 'Spa', 'Training', 'Medical', 'Other'];
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Services</h1>
    <p>Add and manage your pet grooming services</p>
</div>

<!-- ADD SERVICE FORM -->
<div class="card mb-24">
    <div class="card-header">
        <h2><i class="fas fa-plus-circle"></i> Add New Service</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="services.php">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-cut"></i> Service Name</label>
                    <input type="text" name="name" placeholder="e.g., Full Grooming, Bath & Trim" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Price ($)</label>
                    <input type="number" name="price" placeholder="0.00" step="0.01" min="0" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-folder"></i> Category</label>
                    <select name="category" required>
                        <option value="">Select Category</option>
                        <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" name="add_service" class="btn btn-primary btn-block">
                        <i class="fas fa-plus"></i> Add Service
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" placeholder="Describe what this service includes..."></textarea>
            </div>
        </form>
    </div>
</div>

<!-- SERVICES LIST -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-list"></i> Services List</h2>
        <span style="color: var(--text-light); font-size: 14px;">
            Total: <?php echo mysqli_num_rows($services); ?> services
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($services) > 0): ?>
                        <?php while($service = mysqli_fetch_assoc($services)): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--primary);">#<?php echo $service['id']; ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($service['name']); ?></div>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $service['category'] === 'Grooming' ? 'status-confirmed' : ($service['category'] === 'Boarding' ? 'status-active' : 'status-pending'); ?>">
                                    <?php echo htmlspecialchars($service['category']); ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color: var(--success); font-size: 16px;">$<?php echo number_format($service['price'], 2); ?></strong>
                            </td>
                            <td>
                                <div style="max-width: 250px; font-size: 13px; color: var(--text-light);">
                                    <?php echo htmlspecialchars($service['description']); ?>
                                </div>
                            </td>
                            <td>
                                <?php if($service['active']): ?>
                                <span class="status-badge status-approved">Active</span>
                                <?php else: ?>
                                <span class="status-badge status-cancelled">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-secondary btn-sm" onclick="openModal('editServiceModal<?php echo $service['id']; ?>')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="services.php?toggle=<?php echo $service['id']; ?>" class="btn btn-<?php echo $service['active'] ? 'warning' : 'success'; ?> btn-sm" title="<?php echo $service['active'] ? 'Deactivate' : 'Activate'; ?>">
                                        <i class="fas fa-<?php echo $service['active'] ? 'pause' : 'play'; ?>"></i>
                                    </a>
                                    <a href="services.php?delete=<?php echo $service['id']; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this service?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>

                                <!-- Edit Modal -->
                                <div id="editServiceModal<?php echo $service['id']; ?>" class="modal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2><i class="fas fa-edit"></i> Edit Service</h2>
                                            <button class="modal-close" onclick="closeModal('editServiceModal<?php echo $service['id']; ?>')">&times;</button>
                                        </div>
                                        <form method="POST" action="services.php">
                                            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                            <div class="form-group">
                                                <label>Service Name</label>
                                                <input type="text" name="name" value="<?php echo htmlspecialchars($service['name']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Price ($)</label>
                                                <input type="number" name="price" value="<?php echo $service['price']; ?>" step="0.01" min="0" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select name="category" required>
                                                    <?php foreach($categories as $cat): ?>
                                                    <option value="<?php echo $cat; ?>" <?php echo $service['category'] === $cat ? 'selected' : ''; ?>>
                                                        <?php echo $cat; ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description"><?php echo htmlspecialchars($service['description']); ?></textarea>
                                            </div>
                                            <div class="flex gap-16" style="margin-top: 20px;">
                                                <button type="button" class="btn btn-secondary" onclick="closeModal('editServiceModal<?php echo $service['id']; ?>')" style="flex: 1;">
                                                    Cancel
                                                </button>
                                                <button type="submit" name="update_service" class="btn btn-primary" style="flex: 1;">
                                                    <i class="fas fa-save"></i> Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 60px;">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-cut"></i>
                                    </div>
                                    <h3>No Services Yet</h3>
                                    <p>Add your first service using the form above.</p>
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