<?php
session_start();
include "config/db.php";
require_once 'includes/CsrfProtection.php';
require_once 'includes/RateLimiter.php';

// Check form submission rate limit
checkFormRateLimit();

// Verify CSRF token (no regen on failure)
if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
    header("Location: book-appointment.php?error=csrf");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Capture user inputs cleanly (Prepared statements handle safety automatically)
    $owner_name       = trim($_POST['owner_name'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $phone            = trim($_POST['phone'] ?? '');
    $pet_name         = trim($_POST['pet_name'] ?? '');
    $pet_category     = trim($_POST['pet_category'] ?? '');
    $breed            = trim($_POST['breed'] ?? '');
    $pet_size         = trim($_POST['pet_size'] ?? '');
    $pet_count        = trim($_POST['pet_count'] ?? '');
    $multi_pet_note   = trim($_POST['multi_pet_note'] ?? '');
    $main_service     = trim($_POST['main_service'] ?? '');
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $notes            = trim($_POST['notes'] ?? '');
    $payment_method   = $_POST['payment_method'] ?? 'cash';
    $payment_status   = ($payment_method == 'online') ? 'paid' : 'pending';

    $addons = isset($_POST['addons']) ? implode(", ", $_POST['addons']) : "";

    // Time validation: 10:30 AM to 6:30 PM, 30-min intervals
    $timeParts = explode(':', $appointment_time);
    $hours = (int)($timeParts[0] ?? 0);
    $minutes = (int)($timeParts[1] ?? 0);
    $totalMinutes = $hours * 60 + $minutes;

    $minAllowed = 10 * 60 + 30; // 10:30 AM = 630
    $maxAllowed = 18 * 60 + 30; // 6:30 PM = 1110

    if ($totalMinutes < $minAllowed || $totalMinutes > $maxAllowed || ($minutes !== 0 && $minutes !== 30)) {
        header("Location: book-appointment.php?error=time");
        exit();
    }

    // 1. LIMIT CHECK: Prepared statement to count safely
    $check_query = "SELECT COUNT(*) as total FROM appointments WHERE appointment_date = ?";
    $check_stmt  = mysqli_prepare($conn, $check_query);

    if ($check_stmt) {
        mysqli_stmt_bind_param($check_stmt, "s", $appointment_date);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        $row = mysqli_fetch_assoc($check_result);
        mysqli_stmt_close($check_stmt);

        if ($row && $row['total'] >= 10) {
            header("Location: book-appointment.php?full=1");
            exit();
        }
    }

    // 2. INSERT BOOKING: Prepared statement mapping all 16 variables
    $sql = "INSERT INTO appointments 
    (owner_name, email, phone, pet_name, pet_category, breed, pet_size, pet_count, multi_pet_note, main_service, addons, appointment_date, appointment_time, notes, payment_method, payment_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // "ssssssssssssssss" marks 16 sequential string parameters
        mysqli_stmt_bind_param(
            $stmt, 
            "ssssssssssssssss", 
            $owner_name, $email, $phone, $pet_name, $pet_category, 
            $breed, $pet_size, $pet_count, $multi_pet_note, $main_service, 
            $addons, $appointment_date, $appointment_time, $notes, 
            $payment_method, $payment_status
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: book-appointment.php?success=1");
            exit();
        } else {
            echo "Execution Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Database Query Preparation Error: " . mysqli_error($conn);
    }
}
?>