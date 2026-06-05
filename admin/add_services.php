<?php
include "../config/db.php";

$page_title = 'Add Service';
require_once 'includes/header.php';

if($_POST){
  $title=$_POST['title'];
  $price=$_POST['price'];
  $desc=$_POST['description'];
  $cat=$_POST['category'];

  mysqli_query($conn,"INSERT INTO services (title,price,description,category)
  VALUES ('$title','$price','$desc','$cat')");

  header("Location: services.php");
  exit;
}
?>

<div class="card admin-card">
  <form method="POST" class="admin-form">

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