<?php
include "../config/db.php";
$id=$_GET['id'];

mysqli_query($conn,"DELETE FROM reviews WHERE id=$id");
header("Location: reviews.php");
?>