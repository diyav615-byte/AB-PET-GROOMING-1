<?php
include "config/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

  $owner = $_POST['owner_name'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $city = $_POST['city'];

  $pet = $_POST['pet_name'];
  $type = $_POST['pet_type'];
  $plan = $_POST['plan'];
  $breed = $_POST['breed'];
  $age = $_POST['age'];
  $gender = $_POST['gender'];
  $notes = $_POST['notes'];

  $boarding_type = $_POST['boarding_type'];
  $emergency = $_POST['emergency_contact'];
  $checkin = $_POST['checkin_date'];
  $checkout = $_POST['checkout_date'];

  $vaccinated = isset($_POST['vaccinated_confirm']) ? "Yes" : "No";

  // INSERT QUERY
  $sql = "INSERT INTO boarding 
  (owner_name, phone, email, city, pet_name, pet_type, plan, breed, age, gender, notes, boarding_type, emergency_contact, checkin_date, checkout_date, vaccinated_confirm)
  VALUES
  ('$owner','$phone','$email','$city','$pet','$type','$plan','$breed','$age','$gender','$notes','$boarding_type','$emergency','$checkin','$checkout','$vaccinated')";

  mysqli_query($conn, $sql);

  // WHATSAPP MESSAGE
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