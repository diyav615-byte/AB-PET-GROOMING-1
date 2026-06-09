<?php
session_start();
include "config/db.php";
require_once 'includes/CsrfProtection.php';
require_once 'includes/RateLimiter.php';

checkFormRateLimit();

if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
    header("Location: contact.php?error=csrf");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name'] ?? '');
    $rating = $_POST['rating'] ?? '';
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (!preg_match('/^[A-Za-z\s]+$/', $name)) {
        $errors[] = "Name should contain only letters and spaces";
    } elseif (strlen($name) > 20) {
        $errors[] = "Name max 20 characters";
    }

    if (empty($rating) || !in_array($rating, ['1','2','3','4','5'])) {
        $errors[] = "Valid rating is required";
    }

    if (empty($message)) {
        $errors[] = "Review message is required";
    } elseif (str_word_count($message) > 40) {
        $errors[] = "Review max 40 words";
    }

    if (!empty($errors)) {
        echo "<script>alert('" . implode('\\n', $errors) . "'); window.location.href='contact.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO reviews (name, rating, message, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("sis", $name, $rating, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Review submitted!'); window.location.href='contact.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>