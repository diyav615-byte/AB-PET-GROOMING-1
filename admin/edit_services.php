<?php
require_once '../includes/bootstrap.php';
include '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT * FROM services WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$page_title = 'Edit Service';
require_once 'includes/header.php';

if($_POST){
  // Verify CSRF token (no regen on failure)
  if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
      die('Invalid CSRF token');
  }
  
  $title = trim($_POST['title']);
  $price = trim($_POST['price']);
  $desc = trim($_POST['description']);
  $cat = trim($_POST['category']);

  $stmt = mysqli_prepare($conn, "UPDATE services SET title=?, price=?, description=?, category=? WHERE id=?");
  mysqli_stmt_bind_param($stmt, "ssssi", $title, $price, $desc, $cat, $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  header("Location: services.php");
  exit;
}
?>

<div class="card admin-card">
  <form method="POST" class="admin-form">
    <?php echo csrf_field(); ?>

    <h2 class="form-title">Edit Service</h2>

    <label>Service Title</label>
    <input name="title" value="<?php echo htmlspecialchars($data['title'] ?? ''); ?>">

    <label>Price</label>
    <input name="price" value="<?php echo htmlspecialchars($data['price'] ?? ''); ?>">

    <label>Category</label>
    <select name="category">
      <option value="Grooming" <?php if(($data['category'] ?? '')=='Grooming') echo 'selected'; ?>>Grooming</option>
      <option value="Boarding" <?php if(($data['category'] ?? '')=='Boarding') echo 'selected'; ?>>Boarding</option>
    </select>

    <label>Description</label>
    <textarea name="description"><?php echo htmlspecialchars($data['description'] ?? ''); ?></textarea>

    <br>
    <button type="submit" class="btn btn-primary">Update</button>

  </form>
</div>

<?php require_once 'includes/footer.php'; ?>