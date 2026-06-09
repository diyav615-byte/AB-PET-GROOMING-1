<?php
require_once '../includes/bootstrap.php';
include "../config/db.php";

$page_title = 'Add Service';
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

  $stmt = mysqli_prepare($conn, "INSERT INTO services (title, price, description, category) VALUES (?, ?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "ssss", $title, $price, $desc, $cat);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  header("Location: services.php");
  exit;
}
?>

<div class="card admin-card">
  <form method="POST" class="admin-form">
    <?php echo csrf_field(); ?>

    <h2 class="form-title">Add Service</h2>

    <label>Service Title</label>
    <input name="title" placeholder="Service Title" required>

    <label>Price</label>
    <input name="price" placeholder="Price">

    <label>Category</label>
    <select name="category">
      <option value="Grooming">Grooming</option>
      <option value="Boarding">Boarding</option>
    </select>

    <label>Description</label>
    <textarea name="description" placeholder="Description"></textarea>

    <br>
    <button type="submit" class="btn btn-primary">Add Service</button>

  </form>
</div>

<?php require_once 'includes/footer.php'; ?>