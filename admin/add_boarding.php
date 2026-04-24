<?php
$page_title = "Add Boarding";
require_once 'includes/header.php';

include '../config/db.php';

// Handle add boarding package
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_boarding'])) {
        $owner_name = $_POST['owner_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $pet_name = $_POST['pet_name'];
        $pet_type = $_POST['pet_type'];
        $plan = $_POST['plan'];
        $breed = $_POST['breed'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $boarding_type = $_POST['boarding_type'];
        $emergency_contact = $_POST['emergency_contact'];
        $checkin_date = $_POST['checkin_date'];
        $checkout_date = $_POST['checkout_date'];
        $vaccinated_confirm = $_POST['vaccinated_confirm'];
        $notes = $_POST['notes'];
        
        $sql = "INSERT INTO boarding (owner_name, phone, email, city, pet_name, pet_type, plan, breed, age, gender, boarding_type, emergency_contact, checkin_date, checkout_date, vaccinated_confirm, notes, status) 
               VALUES ('$owner_name', '$phone', '$email', '$city', '$pet_name', '$pet_type', '$plan', '$breed', '$age', '$gender', '$boarding_type', '$emergency_contact', '$checkin_date', '$checkout_date', '$vaccinated_confirm', '$notes', 'active')";
        
        mysqli_query($conn, $sql);
        $_SESSION['toast'] = ['message' => 'Boarding package added successfully!', 'type' => 'success'];
        header('Location: boarding.php');
        exit;
    }
}

$plans = ['Basic', 'Standard', 'Premium', 'VIP'];
$pet_types = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
$boarding_types = ['Day Care', 'Night Stay', 'Full Boarding'];
$genders = ['Male', 'Female'];
$vaccinations = ['Yes', 'No'];
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Add Boarding Package</h1>
    <p>Create a new boarding reservation</p>
</div>

<!-- ADD BOARDING FORM -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-home"></i> New Boarding Reservation</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="add_boarding.php">
            <h3 style="color: var(--primary); margin-bottom: 20px; font-size: 16px;">
                <i class="fas fa-user"></i> Owner Information
            </h3>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Owner Name *</label>
                    <input type="text" name="owner_name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number *</label>
                    <input type="tel" name="phone" placeholder="(555) 123-4567" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email *</label>
                    <input type="email" name="email" placeholder="email@example.com" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-city"></i> City/Area</label>
                    <input type="text" name="city" placeholder="City name">
                </div>
            </div>
            
            <h3 style="color: var(--primary); margin: 30px 0 20px; font-size: 16px;">
                <i class="fas fa-paw"></i> Pet Information
            </h3>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-paw"></i> Pet Name *</label>
                    <input type="text" name="pet_name" placeholder="Pet name" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-heart"></i> Pet Type *</label>
                    <select name="pet_type" required>
                        <option value="">Select Pet Type</option>
                        <?php foreach($pet_types as $type): ?>
                        <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-star"></i> Plan *</label>
                    <select name="plan" required>
                        <option value="">Select Plan</option>
                        <?php foreach($plans as $plan): ?>
                        <option value="<?php echo $plan; ?>"><?php echo $plan; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-info-circle"></i> Breed</label>
                    <input type="text" name="breed" placeholder=" breed">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-birthday-cake"></i> Age (years)</label>
                    <input type="number" name="age" placeholder="Age in years" min="0" max="30">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-venus-mars"></i> Gender</label>
                    <select name="gender">
                        <option value="">Select Gender</option>
                        <?php foreach($genders as $gender): ?>
                        <option value="<?php echo $gender; ?>"><?php echo $gender; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <h3 style="color: var(--primary); margin: 30px 0 20px; font-size: 16px;">
                <i class="fas fa-hotel"></i> Boarding Details
            </h3>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-bed"></i> Boarding Type *</label>
                    <select name="boarding_type" required>
                        <option value="">Select Type</option>
                        <?php foreach($boarding_types as $type): ?>
                        <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone-square"></i> Emergency Contact</label>
                    <input type="tel" name="emergency_contact" placeholder="Emergency contact number">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-sign-in-alt"></i> Check-in Date *</label>
                    <input type="date" name="checkin_date" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-sign-out-alt"></i> Check-out Date</label>
                    <input type="date" name="checkout_date">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-syringe"></i> Vaccinated? *</label>
                    <select name="vaccinated_confirm" required>
                        <option value="">Select</option>
                        <?php foreach($vaccinations as $vax): ?>
                        <option value="<?php echo $vax; ?>"><?php echo $vax; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-sticky-note"></i> Special Notes</label>
                <textarea name="notes" placeholder="Any special care instructions, dietary requirements, medical conditions, etc."></textarea>
            </div>
            
            <div style="margin-top: 24px;">
                <button type="submit" name="add_boarding" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Boarding Reservation
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>