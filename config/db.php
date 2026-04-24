<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "ab_pet_grooming";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>