<?php
include "config/db.php";

// IMPORTANT: direct access block
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Access Denied");
}

// GET DATA SAFELY
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// VALIDATION
if (!preg_match("/^[A-Za-z ]+$/", $name) || strlen($name) > 20) {
    die("Invalid Name");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid Email");
}

if (!preg_match("/^[0-9]{10}$/", $phone)) {
    die("Invalid Phone");
}

if (strlen($subject) > 40) {
    die("Subject too long");
}

if (str_word_count($message) > 40) {
    die("Message too long");
}

// INSERT
$sql = "INSERT INTO contact (name,email,phone,subject,message)
VALUES ('$name','$email','$phone','$subject','$message')";

if (mysqli_query($conn, $sql)) {
    echo "Message Sent Successfully";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>