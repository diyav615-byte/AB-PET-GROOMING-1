<?php
include "../config/db.php";
$id=$_GET['id'];

mysqli_query($conn,"UPDATE reviews SET status='approved' WHERE id=$id");
header("Location: reviews.php");
?>