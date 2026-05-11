<?php
include '../config/db.php';

// DELETE
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM service_cards WHERE id=$id");
    header("Location: services.php");
    exit;
}

// EDIT FETCH
$editData = null;
$editItems = null;

if(isset($_GET['edit'])){
    $id = (int)$_GET['edit'];

    $editData = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT * FROM service_cards WHERE id=$id")
    );

    $editItems = mysqli_query($conn,
        "SELECT * FROM service_card_items WHERE service_id=$id"
    );
}

// ADD
if(isset($_POST['add_service'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category =  mysqli_real_escape_string($conn, $_POST['category']);
    mysqli_query($conn,"INSERT INTO service_cards (title,category) 
    VALUES ('$title','$category')");

    $service_id = mysqli_insert_id($conn);

    saveItems($conn, $service_id);
}

// UPDATE
if(isset($_POST['update_service'])){
    $id = (int)$_GET['edit'];

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category =  mysqli_real_escape_string($conn, $_POST['category']);

    mysqli_query($conn,"UPDATE service_cards 
    SET title='$title', category='$category' WHERE id=$id");

    mysqli_query($conn,"DELETE FROM service_card_items WHERE service_id=$id");

    saveItems($conn, $id);
}

// FUNCTION
function saveItems($conn, $service_id){

    // ITEMS
    if(isset($_POST['item_name'])){
        foreach($_POST['item_name'] as $key => $name){
            $price = $_POST['item_price'][$key] ?: NULL;

            if($name != ''){
                mysqli_query($conn,"INSERT INTO service_card_items 
                (service_id,type,name,price)
                VALUES ('$service_id','item','$name',".($price ? "'$price'" : "NULL").")");
            }
        }
    }

    // BREEDS
    if(isset($_POST['breed_name'])){
        foreach($_POST['breed_name'] as $key => $name){
            $price = $_POST['breed_price'][$key] ?: NULL;

            if($name != ''){
                mysqli_query($conn,"INSERT INTO service_card_items 
                (service_id,type,name,price)
                VALUES ('$service_id','breed','$name',".($price ? "'$price'" : "NULL").")");
            }
        }
    }

    header("Location: services.php");
    exit;
}

// FETCH
$services = mysqli_query($conn,"SELECT * FROM service_cards ORDER BY id ASC");

$page_title = "Services";
require_once 'includes/header.php';
?>

<!-- ================= FORM ================= -->
<div class="card admin-card">

<form method="POST" class="admin-form">

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