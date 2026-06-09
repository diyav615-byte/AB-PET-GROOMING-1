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
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (!preg_match('/^[A-Za-z\s]+$/', $name)) {
        $errors[] = "Name should contain only letters and spaces";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($phone)) {
        $errors[] = "Phone is required";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Phone must be 10 digits";
    }

    if (empty($subject)) {
        $errors[] = "Subject is required";
    } elseif (str_word_count($subject) > 20) {
        $errors[] = "Subject max 20 words";
    }

    if (empty($message)) {
        $errors[] = "Message is required";
    } elseif (str_word_count($message) > 40) {
        $errors[] = "Message max 40 words";
    }

    if (!empty($errors)) {
        echo "<script>alert('" . implode('\\n', $errors) . "'); window.location.href='contact.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Message Sent Successfully'); window.location.href='contact.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>