<?php
include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO reviews (name, rating, message, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("sis", $name, $rating, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Review submitted! Waiting for approval'); window.location.href='contact.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>