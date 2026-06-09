<?php
require_once '../includes/bootstrap.php';
include '../config/db.php';

// DELETE
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM service_cards WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: services.php");
    exit;
}

// EDIT FETCH
$editData = null;
$editItems = null;

if(isset($_GET['edit'])){
    $id = (int)$_GET['edit'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM service_cards WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $editData = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "SELECT * FROM service_card_items WHERE service_id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $editItems = mysqli_stmt_get_result($stmt);
}

// ADD
if(isset($_POST['add_service'])){
    // Verify CSRF token (without regenerating on failure)
    if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
        die('Invalid CSRF token');
    }
    
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    
    $stmt = mysqli_prepare($conn, "INSERT INTO service_cards (title, category) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $title, $category);
    mysqli_stmt_execute($stmt);
    $service_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    saveItems($conn, $service_id);
}

// UPDATE
if(isset($_POST['update_service'])){
    // Verify CSRF token (without regenerating on failure)
    if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
        die('Invalid CSRF token');
    }
    
    $id = (int)$_GET['edit'];
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);

    $stmt = mysqli_prepare($conn, "UPDATE service_cards SET title=?, category=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssi", $title, $category, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM service_card_items WHERE service_id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    saveItems($conn, $id);
}

// FUNCTION
function saveItems($conn, $service_id){
    // ITEMS
    if(isset($_POST['item_name'])){
        foreach($_POST['item_name'] as $key => $name){
            $price = $_POST['item_price'][$key] ?: NULL;

            if($name != ''){
                $stmt = mysqli_prepare($conn, "INSERT INTO service_card_items (service_id, type, name, price) VALUES (?, 'item', ?, ?)");
                mysqli_stmt_bind_param($stmt, "isd", $service_id, $name, $price);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

    // BREEDS
    if(isset($_POST['breed_name'])){
        foreach($_POST['breed_name'] as $key => $name){
            $price = $_POST['breed_price'][$key] ?: NULL;

            if($name != ''){
                $stmt = mysqli_prepare($conn, "INSERT INTO service_card_items (service_id, type, name, price) VALUES (?, 'breed', ?, ?)");
                mysqli_stmt_bind_param($stmt, "isd", $service_id, $name, $price);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

    header("Location: services.php");
    exit;
}

// FETCH
$services = mysqli_query($conn, "SELECT * FROM service_cards ORDER BY id ASC");

$page_title = "Services";
require_once 'includes/header.php';
?>

<!-- ================= FORM ================= -->
<div class="card admin-card">

<form method="POST" class="admin-form">
    <?php echo csrf_field(); ?>

<h2 class="form-title">Manage Services</h2>

<label>Title</label>
<input type="text" name="title"
value="<?php echo $editData['title'] ?? ''; ?>" required>

<label>Category</label>
<select name="category">
  <option value="dog" <?php if(($editData['category'] ?? '')=='dog') echo 'selected'; ?>>Dog</option>
  <option value="cat" <?php if(($editData['category'] ?? '')=='cat') echo 'selected'; ?>>Cat</option>
</select>

<!-- ITEMS -->
<h3>Items</h3>
<div id="items">

<?php 
if($editItems){
while($i = mysqli_fetch_assoc($editItems)){
if($i['type']=='item'){ ?>
<div class="item-row">
<input type="text" name="item_name[]" value="<?php echo $i['name']; ?>">
<input type="number" name="item_price[]" value="<?php echo $i['price']; ?>">
<button type="button" onclick="removeItem(this)">✖</button>
</div>
<?php } } } ?>

<div class="item-row">
<input type="text" name="item_name[]" placeholder="Service Item">
<input type="number" name="item_price[]" placeholder="₹ Price">
<button type="button" onclick="removeItem(this)">✖</button>
</div>

</div>

<button type="button" onclick="addItem()">+ Add Item</button>

<!-- BREEDS -->
<h3>Breed Pricing</h3>
<div id="breeds">

<?php 
if($editItems){
mysqli_data_seek($editItems,0);
while($i = mysqli_fetch_assoc($editItems)){
if($i['type']=='breed'){ ?>
<div class="item-row">
<input type="text" name="breed_name[]" value="<?php echo $i['name']; ?>">
<input type="number" name="breed_price[]" value="<?php echo $i['price']; ?>">
<button type="button" onclick="removeItem(this)">✖</button>
</div>
<?php } } } ?>

<div class="item-row">
<input type="text" name="breed_name[]" placeholder="Small / Large / Giant">
<input type="number" name="breed_price[]" placeholder="₹ Price">
<button type="button" onclick="removeItem(this)">✖</button>
</div>

</div>

<button type="button" onclick="addBreed()">+ Add Breed</button>

<br><br>

<?php if($editData){ ?>
<button type="submit" name="update_service" class="btn btn-primary">Update</button>
<?php } else { ?>
<button type="submit" name="add_service" class="btn btn-primary">Add</button>
<?php } ?>

</form>
</div>


<!-- ================= TABLE ================= -->
<div class="card admin-card">

<h2>All Services</h2>

<table class="premium-table">

<tr>
<th>Title</th>
<th>Category</th>
<th>Items</th>
<th>Action</th>
</tr>

<?php while($s = mysqli_fetch_assoc($services)): ?>
<tr>

<td><?php echo $s['title']; ?></td>

<td><?php echo ucfirst($s['category']); ?></td>

<td>
<?php
$items = mysqli_query($conn, "SELECT * FROM service_card_items WHERE service_id=".$s['id']);

while($i = mysqli_fetch_assoc($items)){

    echo "<div>";

    echo $i['name'];

    if($i['price']){
        echo " - ₹".$i['price'];
    }

    echo " (".$i['type'].")";

    echo "</div>";
}
?>


</td>

<td class="action-buttons">

<a href="?edit=<?php echo $s['id']; ?>" 
class="edit-btn">
Edit
</a>

<a href="?delete=<?php echo $s['id']; ?>" 
class="delete-btn"
onclick="return confirm('Delete this service?')">
Delete
</a>

</td>
</tr>
<?php endwhile; ?>

</table>
</div>


<!-- ================= JS ================= -->
<script>
function addItem(){
document.getElementById("items").innerHTML += `
<div class="item-row">
<input type="text" name="item_name[]" placeholder="Service Item">
<input type="number" name="item_price[]" placeholder="₹ Price">
<button type="button" onclick="removeItem(this)">✖</button>
</div>`;
}

function addBreed(){
document.getElementById("breeds").innerHTML += `
<div class="item-row">
<input type="text" name="breed_name[]" placeholder="Breed">
<input type="number" name="breed_price[]" placeholder="₹ Price">
<button type="button" onclick="removeItem(this)">✖</button>
</div>`;
}

function removeItem(btn){
btn.parentElement.remove();
}
</script>

<style>
.action-buttons{
  display:flex;
  gap:10px;
  align-items:center;
}

.edit-btn{
  background: linear-gradient(135deg,#7158a6,#7158a6);
  color:#fff;
  padding:8px 16px;
  border-radius:10px;
  text-decoration:none;
  font-weight:700;
  font-size:14px;
  transition:0.3s;
}

.edit-btn:hover{
  transform:translateY(-2px);
  opacity:0.9;
}

.delete-btn{
  background: linear-gradient(135deg,#ff4d6d,#ff1744);
  color:#fff;
  padding:8px 16px;
  border-radius:10px;
  text-decoration:none;
  font-weight:700;
  font-size:14px;
  transition:0.3s;
}

.delete-btn:hover{
  transform:translateY(-2px);
  opacity:0.9;
}
</style>

<?php require_once 'includes/footer.php'; ?>