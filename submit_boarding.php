<?php
session_start();
include "config/db.php";
require_once 'includes/CsrfProtection.php';
require_once 'includes/RateLimiter.php';

// Check form submission rate limit
checkFormRateLimit();

// Verify CSRF token (no regen on failure)
if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
    header("Location: boarding.php?error=csrf");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Clean data variables mapping directly out of POST arrays
    $owner          = trim($_POST['owner_name'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $city           = trim($_POST['city'] ?? '');
    $pet            = trim($_POST['pet_name'] ?? '');
    $type           = trim($_POST['pet_type'] ?? '');
    $plan           = trim($_POST['plan'] ?? '');
    $breed          = trim($_POST['breed'] ?? '');
    $age            = trim($_POST['age'] ?? '');
    $gender         = trim($_POST['gender'] ?? '');
    $notes          = trim($_POST['notes'] ?? '');
    $boarding_type  = trim($_POST['boarding_type'] ?? '');
    $emergency      = trim($_POST['emergency_contact'] ?? '');
    $checkin        = $_POST['checkin_date'] ?? '';
    $checkout       = $_POST['checkout_date'] ?? '';
  
    $vaccinated     = isset($_POST['vaccinated_confirm']) ? "Yes" : "No";
    $payment_method = $_POST['payment_method'] ?? 'cash';
    $payment_status = ($payment_method == 'online') ? 'paid' : 'pending';

    // INSERT QUERY: Using 18 placeholders ('?') instead of direct interpolation
    $sql = "INSERT INTO boarding 
    (owner_name, phone, email, city, pet_name, pet_type, plan, breed, age, gender, notes, boarding_type, emergency_contact, checkin_date, checkout_date, vaccinated_confirm, payment_method, payment_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // "ssssssssssssssssss" tracks 18 sequential string params bound dynamically
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssssssss",
            $owner, $phone, $email, $city, $pet, $type, $plan, $breed, $age, 
            $gender, $notes, $boarding_type, $emergency, $checkin, $checkout, 
            $vaccinated, $payment_method, $payment_status
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "Database Query Preparation Error: " . mysqli_error($conn);
        exit();
    }

    // WHATSAPP REDIRECT REDIRECTION
    $message = urlencode("New Boarding Booking:
Owner: $owner
Phone: $phone
Pet: $pet ($type)
Plan: $plan
Check-in: $checkin
Check-out: $checkout");

    $admin_number = "918828719786";

    header("Location: https://wa.me/$admin_number?text=$message");
    exit();
}
?>