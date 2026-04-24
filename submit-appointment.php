<?php
include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $owner_name = mysqli_real_escape_string($conn, $_POST['owner_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $pet_name = mysqli_real_escape_string($conn, $_POST['pet_name']);
    $pet_category = mysqli_real_escape_string($conn, $_POST['pet_category']);
    $breed = mysqli_real_escape_string($conn, $_POST['breed']);
    $pet_size = mysqli_real_escape_string($conn, $_POST['pet_size']);
    $pet_count = mysqli_real_escape_string($conn, $_POST['pet_count']);
    $multi_pet_note = mysqli_real_escape_string($conn, $_POST['multi_pet_note']);
    $main_service = mysqli_real_escape_string($conn, $_POST['main_service']);

    $addons = isset($_POST['addons']) ? implode(", ", $_POST['addons']) : "";

    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    // LIMIT: 10 bookings per day
    $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE appointment_date='$appointment_date'");
    $row = mysqli_fetch_assoc($check);

    if ($row['total'] >= 10) {
        header("Location: book-appointment.php?full=1");
        exit();
    }

    $sql = "INSERT INTO appointments 
    (owner_name, email, phone, pet_name, pet_category, breed, pet_size, pet_count, multi_pet_note, main_service, addons, appointment_date, appointment_time, notes)
    VALUES 
    ('$owner_name','$email','$phone','$pet_name','$pet_category','$breed','$pet_size','$pet_count','$multi_pet_note','$main_service','$addons','$appointment_date','$appointment_time','$notes')";

    if (mysqli_query($conn, $sql)) {
        header("Location: book-appointment.php?success=1");
        exit();
    } else {
        echo "Database Error: " . mysqli_error($conn);
    }
}
?>