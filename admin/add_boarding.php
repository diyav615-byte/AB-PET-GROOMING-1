<?php
include '../config/db.php';

/* ================= ADD ================= */

if(isset($_POST['add_boarding'])){

    $name  = mysqli_real_escape_string($conn,$_POST['name']);
    $price = mysqli_real_escape_string($conn,$_POST['price']);
    $type  = mysqli_real_escape_string($conn,$_POST['type']);

    mysqli_query($conn,"
        INSERT INTO pet_boarding(name,price,type)
        VALUES('$name','$price','$type')
    ");

   echo "<script>window.location='add_boarding.php';</script>";
exit;
}


/* ================= UPDATE ================= */

if(isset($_POST['update_boarding'])){

    $id = (int)$_GET['edit'];

    $name  = mysqli_real_escape_string($conn,$_POST['name']);
    $price = mysqli_real_escape_string($conn,$_POST['price']);
    $type  = mysqli_real_escape_string($conn,$_POST['type']);

    mysqli_query($conn,"
        UPDATE pet_boarding
        SET
        name='$name',
        price='$price',
        type='$type'
        WHERE id=$id
    ");

    header("Location: add_boarding.php");
    exit;
}


/* ================= DELETE ================= */

if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    mysqli_query($conn,"
        DELETE FROM pet_boarding
        WHERE id=$id
    ");

    echo "<script>window.location='add_boarding.php';</script>";
    exit;
}


/* ================= EDIT FETCH ================= */

$editData = null;

if(isset($_GET['edit'])){

    $id = (int)$_GET['edit'];

    $editData = mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT * FROM pet_boarding
            WHERE id=$id
        ")
    );
}




/* ================= FETCH ================= */

$services = mysqli_query($conn,"
    SELECT * FROM pet_boarding
    ORDER BY id ASC
");

$page_title = "Pet Boarding";
require_once 'includes/header.php';
?>


<style>

/* ================= PAGE ================= */

.boarding-card{
    background:#fff;
    border-radius:24px;
    padding:32px;
    margin-bottom:30px;
    box-shadow:0 10px 35px rgba(0,0,0,0.06);
    border:1px solid #ece8ff;
}

.boarding-title{
    font-size:34px;
    font-weight:800;
    color:#17123b;
    margin-bottom:28px;
}

/* ================= FORM ================= */

.boarding-form label{
    display:block;
    margin-bottom:10px;
    margin-top:20px;
    font-size:15px;
    font-weight:700;
    color:#40385e;
}

.boarding-form input,
.boarding-form select{
    width:100%;
    height:55px;
    border-radius:14px;
    border:1px solid #ddd;
    padding:0 18px;
    font-size:15px;
    background:#fff;
    transition:0.25s ease;
}

.boarding-form input:focus,
.boarding-form select:focus{
    border-color:#7158a6;
    outline:none;
    box-shadow:0 0 0 4px rgba(113,88,166,0.12);
}

.save-btn{
    background:linear-gradient(135deg,#7158a6,#5c4691);
    color:#fff;
    border:none;
    height:55px;
    padding:0 34px;
    border-radius:16px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    margin-top:28px;
    transition:0.25s;
}

.save-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 24px rgba(113,88,166,0.24);
}

/* ================= TABLE ================= */

.boarding-table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:22px;
    background:#fff;
}

.boarding-table thead{
    background:#7158a6;
}

.boarding-table th{
    color:#fff;
    padding:20px;
    text-align:left;
    font-size:14px;
    text-transform:uppercase;
    letter-spacing:1px;
}

.boarding-table td{
    padding:22px 20px;
    border-bottom:1px solid #eee;
    vertical-align:middle;
    color:#40385e;
    font-size:15px;
}

.boarding-table tr:hover{
    background: transparent ! important;
}

/* ================= TYPE BADGE ================= */

.type-badge{
    display:inline-block;
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
    color:#fff;
}

.type-dog{
    background:#5b7cff;
}

.type-cat{
    background:#ff6ba6;
}

/* ================= ACTION ================= */

.action-btn{
    text-decoration:none;
    font-weight:700;
    margin-right:12px;
    transition:0.2s;
}

.edit-btn{
    background:#7158a6;
    color:#fff;
}

.delete-btn{
    background:#ff4d4d;
    color: white;
}

.action-btn:hover{
    opacity:0.7;
}

/* ================= MOBILE ================= */

@media(max-width:768px){

    .boarding-table{
        display:block;
        overflow-x:auto;
    }

    .boarding-card{
        padding:22px;
    }

    .boarding-title{
        font-size:28px;
    }
}

</style>



<!-- ================= FORM ================= -->

<div class="boarding-card">

<form method="POST" class="boarding-form">

<h2 class="boarding-title">

<?php
echo $editData
? 'Edit Boarding Service'
: 'Add Boarding Service';
?>

</h2>


<label>Service Name</label>

<input
type="text"
name="name"
required
placeholder="Enter Service Name"
value="<?php echo $editData['name'] ?? ''; ?>"
>


<label>Price</label>

<input
type="number"
name="price"
required
placeholder="Enter Price"
value="<?php echo $editData['price'] ?? ''; ?>"
>


<label>Type</label>

<select name="type" required>

<option value="dog"
<?php if(($editData['type'] ?? '') == 'dog') echo 'selected'; ?>>
Dog
</option>

<option value="cat"
<?php if(($editData['type'] ?? '') == 'cat') echo 'selected'; ?>>
Cat
</option>

</select>


<?php if($editData){ ?>

<button
type="submit"
name="update_boarding"
class="save-btn">
Update Service
</button>

<?php } else { ?>

<button
type="submit"
name="add_boarding"
class="save-btn">
Add Service
</button>

<?php } ?>

</form>

</div>



<!-- ================= TABLE ================= -->

<div class="boarding-card">

<h2 class="boarding-title">
All Boarding Services
</h2>

<table class="boarding-table">

<thead>

<tr>
<th>Name</th>
<th>Price</th>
<th>Type</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($services)): ?>

<tr>

<td>
<?php echo $row['name']; ?>
</td>

<td>
₹<?php echo $row['price']; ?>
</td>

<td>

<?php if($row['type'] == 'dog'){ ?>

<span class="type-badge type-dog">
Dog
</span>

<?php } else { ?>

<span class="type-badge type-cat">
Cat
</span>

<?php } ?>

</td>

<td>

<a
href="?edit=<?php echo $row['id']; ?>"
class="action-btn edit-btn">
Edit
</a>

<a
href="?delete=<?php echo $row['id']; ?>"
class="action-btn delete-btn"
onclick="return confirm('Delete this service?')">
Delete
</a>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<?php require_once 'includes/footer.php'; ?>