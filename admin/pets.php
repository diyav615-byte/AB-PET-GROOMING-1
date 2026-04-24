<?php
$page_title = "Manage Pets";
$css_path = "css/style.css";
require_once 'includes/header.php';

// Sample pets data
$pets = [
    ['id' => 1, 'name' => 'Max', 'breed' => 'Golden Retriever', 'age' => 3, 'weight' => '25.5 kg', 'owner' => 'John Doe', 'vaccinated' => true],
    ['id' => 2, 'name' => 'Bella', 'breed' => 'Labrador', 'age' => 2, 'weight' => '28.0 kg', 'owner' => 'Jane Smith', 'vaccinated' => true],
    ['id' => 3, 'name' => 'Charlie', 'breed' => 'Poodle', 'age' => 4, 'weight' => '15.0 kg', 'owner' => 'Mike Johnson', 'vaccinated' => false],
];
?>

<!-- PAGE HEADER -->
<div class="page-header flex-between">
    <div>
        <h1>Manage Pets</h1>
        <p>Register and manage pet profiles</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addPetModal')">
        <i class="fas fa-plus"></i> Add Pet
    </button>
</div>

<!-- ADD PET MODAL -->
<div id="addPetModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2>Register New Pet</h2>
            <button class="modal-close" onclick="closeModal('addPetModal')">&times;</button>
        </div>
        <form method="POST" onsubmit="return handlePetSubmit(event)">
            <div class="form-row">
                <div class="form-group">
                    <label for="petCustomerId">Customer ID *</label>
                    <input type="number" id="petCustomerId" name="customer_id" required placeholder="1">
                </div>

                <div class="form-group">
                    <label for="petName">Pet Name *</label>
                    <input type="text" id="petName" name="name" required placeholder="e.g., Buddy">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="petBreed">Breed</label>
                    <input type="text" id="petBreed" name="breed" placeholder="e.g., Golden Retriever">
                </div>

                <div class="form-group">
                    <label for="petAge">Age (years)</label>
                    <input type="number" id="petAge" name="age" placeholder="3">
                </div>

                <div class="form-group">
                    <label for="petWeight">Weight (kg)</label>
                    <input type="number" id="petWeight" name="weight" placeholder="25.5" step="0.1">
                </div>
            </div>

            <div class="form-group">
                <label for="petColor">Color/Markings</label>
                <input type="text" id="petColor" name="color" placeholder="e.g., Golden with white chest">
            </div>

            <div class="form-group">
                <label for="petMedical">Medical Notes</label>
                <textarea id="petMedical" name="medical_notes" placeholder="Any medical conditions or special needs..."></textarea>
            </div>

            <div class="form-group">
                <label for="petAllergies">Allergies</label>
                <textarea id="petAllergies" name="allergies" placeholder="List any known allergies..."></textarea>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" id="petVaccinated" name="vaccinated">
                    <span>Vaccinated</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Register Pet</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('addPetModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- PETS TABLE -->
<div class="card">
    <div class="card-header">
        <h2>Registered Pets</h2>
        <span style="color: #7A7A7A; font-size: 14px;">Total: <?php echo count($pets); ?> pets</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Breed</th>
                        <th>Age</th>
                        <th>Weight</th>
                        <th>Owner</th>
                        <th>Vaccinated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($pet['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($pet['breed'] ?? '-'); ?></td>
                        <td><?php echo $pet['age'] ? $pet['age'] . ' years' : '-'; ?></td>
                        <td><?php echo $pet['weight'] ?? '-'; ?></td>
                        <td><?php echo htmlspecialchars($pet['owner']); ?></td>
                        <td>
                            <span style="<?php echo $pet['vaccinated'] ? 'color: #6BBF59; font-weight: bold;' : 'color: #7A7A7A;'; ?>">
                                <?php echo $pet['vaccinated'] ? '✓ Yes' : 'No'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm" onclick="editPet(<?php echo $pet['id']; ?>)" style="background: #D4C5E8; color: #4A4A4A; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteRow(<?php echo $pet['id']; ?>, 'pet')" style="padding: 6px 12px; border-radius: 4px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
function handlePetSubmit(event) {
    event.preventDefault();
    showAlert('Pet registered successfully!', 'success');
    closeModal('addPetModal');
    document.querySelector('form').reset();
    return false;
}

function editPet(id) {
    showAlert('Edit functionality coming soon!', 'info');
}
</script>
