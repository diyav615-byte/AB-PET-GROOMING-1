<?php
include "../config/db.php";

$id = (int)($_GET['id'] ?? 0);
$data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM services WHERE id=$id"));

$page_title = 'Edit Service';
require_once 'includes/header.php';

if($_POST){
  $title=$_POST['title'];
  $price=$_POST['price'];
  $desc=$_POST['description'];
  $cat=$_POST['category'];

  mysqli_query($conn,"UPDATE services SET 
  title='$title',
  price='$price',
  description='$desc',
  category='$cat'
  WHERE id=$id");

  header("Location: services.php");
  exit;
}
?>

<div class="card admin-card">
  <form method="POST" class="admin-form">

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