<?php
$page_title = "Gallery";
$css_path = "css/style.css";
require_once 'includes/header.php';

// Sample gallery data
$gallery = [
    ['id' => 1, 'title' => 'Pet Grooming Session', 'image' => 'https://via.placeholder.com/300x200?text=Grooming+1', 'date' => '2026-02-28'],
    ['id' => 2, 'title' => 'Happy Pet', 'image' => 'https://via.placeholder.com/300x200?text=Happy+Pet', 'date' => '2026-02-27'],
    ['id' => 3, 'title' => 'Spa Day', 'image' => 'https://via.placeholder.com/300x200?text=Spa+Day', 'date' => '2026-02-26'],
    ['id' => 4, 'title' => 'Before & After', 'image' => 'https://via.placeholder.com/300x200?text=Before+After', 'date' => '2026-02-25'],
];
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Gallery</h1>
    <p>Upload and manage gallery images</p>
</div>

<!-- UPLOAD AREA -->
<div class="card mb-20">
    <div class="card-header">
        <h2>Upload Images</h2>
    </div>
    <div class="card-body">
        <div id="dropZone" style="border: 2px dashed #E0D5F0; border-radius: 10px; padding: 40px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
            <div style="font-size: 48px; margin-bottom: 15px;">📤</div>
            <h3 style="color: #4A4A4A; margin-bottom: 10px;">Drag and drop images here</h3>
            <p style="color: #7A7A7A; margin-bottom: 20px; font-size: 14px;">or click to select files (max 5MB each)</p>
            <input type="file" id="fileInput" multiple accept="image/*" style="display: none;">
            <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click();">
                <i class="fas fa-folder-open"></i> Select Images
            </button>
        </div>
    </div>
</div>

<!-- GALLERY GRID -->
<div>
    <h2 style="color: #B8A8D8; margin-bottom: 20px; font-size: 20px;">Gallery Images</h2>
    
    <?php if (empty($gallery)): ?>
    <div class="card">
        <div class="card-body" style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;">🖼️</div>
            <p style="color: #7A7A7A;">No images in gallery yet. Upload some to get started!</p>
        </div>
    </div>
    <?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
        <?php foreach ($gallery as $image): ?>
        <div class="card" style="overflow: hidden; padding: 0;">
            <div style="position: relative; width: 100%; padding-bottom: 66.67%; overflow: hidden; background: #F8F6FC;">
                <img src="<?php echo htmlspecialchars($image['image']); ?>" alt="<?php echo htmlspecialchars($image['title']); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0); transition: background 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 10px; opacity: 0; hover-opacity: 1;" class="image-overlay" onmouseenter="this.style.background='rgba(0, 0, 0, 0.5)'; this.style.opacity='1';" onmouseleave="this.style.background='rgba(0, 0, 0, 0)'; this.style.opacity='0';">
                    <button class="btn btn-sm" onclick="downloadImage('<?php echo htmlspecialchars($image['image']); ?>')" style="background: white; color: #4A4A4A; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteRow(<?php echo $image['id']; ?>, 'gallery')" style="padding: 8px 12px; border-radius: 4px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div style="padding: 15px;">
                <h3 style="font-size: 14px; font-weight: 600; color: #4A4A4A; margin-bottom: 5px;"><?php echo htmlspecialchars($image['title']); ?></h3>
                <p style="font-size: 12px; color: #7A7A7A;"><?php echo date('M d, Y', strtotime($image['date'])); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
// Setup drag and drop
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');

// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Highlight drop zone when item is dragged over it
['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.style.backgroundColor = 'rgba(212, 197, 232, 0.1)';
        dropZone.style.borderColor = '#B8A8D8';
    });
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.style.backgroundColor = '';
        dropZone.style.borderColor = '';
    });
});

// Handle dropped files
dropZone.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    fileInput.files = files;
    handleFiles(files);
});

// Handle file input change
fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

// Handle selected files
function handleFiles(files) {
    let validFiles = 0;
    for (let file of files) {
        if (file.size > 5242880) { // 5MB
            showAlert(`File ${file.name} is too large. Max size: 5MB`, 'warning');
            continue;
        }
        if (!file.type.startsWith('image/')) {
            showAlert(`File ${file.name} is not an image`, 'warning');
            continue;
        }
        validFiles++;
    }
    
    if (validFiles > 0) {
        showAlert(`${validFiles} image(s) uploaded successfully!`, 'success');
        fileInput.value = '';
    }
}

// Click on drop zone to select files
dropZone.addEventListener('click', () => {
    fileInput.click();
});

function downloadImage(url) {
    showAlert('Download functionality coming soon!', 'info');
}
</script>

<style>
.image-overlay {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.card:hover .image-overlay {
    opacity: 1;
}
</style>
