<?php
include "config/db.php";

$name = trim($_POST['name']);
$rating = $_POST['rating'];
$review = trim($_POST['review']);

if (!preg_match("/^[A-Za-z ]+$/", $name) || strlen($name) > 20) {
die("Invalid Name");
}

if (strlen($review) > 40) {
die("Review too long");
}

$sql = "INSERT INTO reviews (name,rating,review)
VALUES ('$name','$rating','$review')";

mysqli_query($conn,$sql);

echo "Review Submitted";
?>