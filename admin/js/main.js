// ===== MODAL FUNCTIONS =====
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
    }
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.modal.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }
});

// ===== FORM FUNCTIONS =====
function resetForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
    }
}

// ===== ALERT FUNCTIONS =====
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    const container = document.querySelector('.main-content');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }
}

// ===== TABLE FUNCTIONS =====
function deleteRow(id, type) {
    if (confirm('Are you sure you want to delete this item?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete.php';
        form.innerHTML = `<input type="hidden" name="delete_id" value="${id}">
                         <input type="hidden" name="delete_type" value="${type}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

// ===== SEARCH FUNCTIONS =====
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if (!input || !table) return;
    
    const filter = input.value.toUpperCase();
    const rows = table.getElementsByTagName('tbody')[0]?.rows;

    if (!rows) return;

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
            if (cells[j]) {
                const text = cells[j].textContent || cells[j].innerText;
                if (text.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }

        rows[i].style.display = found ? '' : 'none';
    }
}

// ===== STATUS UPDATE FUNCTIONS =====
function updateStatus(id, newStatus, type) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'update_status.php';
    form.innerHTML = `<input type="hidden" name="update_id" value="${id}">
                     <input type="hidden" name="new_status" value="${newStatus}">
                     <input type="hidden" name="update_type" value="${type}">`;
    document.body.appendChild(form);
    form.submit();
}

// ===== FILE UPLOAD FUNCTIONS =====
function handleFileUpload(event, maxSize = 5242880) {
    const files = event.target.files;
    const validFiles = [];

    for (let file of files) {
        if (file.size > maxSize) {
            showAlert(`File ${file.name} is too large. Max size: 5MB`, 'warning');
            continue;
        }

        if (!file.type.startsWith('image/')) {
            showAlert(`File ${file.name} is not an image`, 'warning');
            continue;
        }

        validFiles.push(file);
    }

    return validFiles;
}

// Drag and drop functionality
function setupDragDrop(dropZoneId, inputId) {
    const dropZone = document.getElementById(dropZoneId);
    const fileInput = document.getElementById(inputId);

    if (!dropZone) return;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.backgroundColor = 'rgba(212, 197, 232, 0.1)';
            dropZone.style.borderColor = '#7158a6';
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.backgroundColor = '';
            dropZone.style.borderColor = '';
        });
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;

        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    });
}

// ===== CHART FUNCTIONS =====
function createChart(canvasId, type, labels, data, label, secondLabel = null, secondData = null) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    const datasets = [{
        label: label,
        data: data,
        backgroundColor: 'rgba(113, 88, 166, 0.8)',
        borderColor: 'rgba(113, 88, 166, 1)',
        borderWidth: 2,
        tension: 0.4,
        fill: type === 'line'
    }];

    if (secondLabel && secondData) {
        datasets.push({
            label: secondLabel,
            data: secondData,
            backgroundColor: 'rgba(157, 138, 199, 0.6)',
            borderColor: 'rgba(157, 138, 199, 1)',
            borderWidth: 2,
            tension: 0.4,
            fill: type === 'line'
        });
    }

    new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#4A4A4A',
                        font: {
                            size: 12,
                            family: "'Poppins', sans-serif"
                        },
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#7A7A7A',
                        font: {
                            family: "'Poppins', sans-serif"
                        }
                    },
                    grid: {
                        color: 'rgba(224, 213, 240, 0.3)'
                    }
                },
                x: {
                    ticks: {
                        color: '#7A7A7A',
                        font: {
                            family: "'Poppins', sans-serif"
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Create bar chart with custom colors
function createBarChart(canvasId, labels, data, label) {
    return createChart(canvasId, 'bar', labels, data, label);
}

// Create line chart
function createLineChart(canvasId, labels, data, label) {
    return createChart(canvasId, 'line', labels, data, label);
}

// Create doughnut chart
function createDoughnutChart(canvasId, labels, data, colors) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors || [
                    'rgba(113, 88, 166, 0.8)',
                    'rgba(157, 138, 199, 0.8)',
                    'rgba(184, 168, 216, 0.8)',
                    'rgba(212, 197, 232, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
}

// ===== DATE FUNCTIONS =====
function formatDate(date) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(date).toLocaleDateString('en-US', options);
}

function formatTime(time) {
    if (!time) return '';
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

// ===== UTILITY FUNCTIONS =====
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('Copied to clipboard!', 'success');
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ===== TABS FUNCTIONS =====
function switchTab(tabId) {
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    // Add stagger animation to cards
    const cards = document.querySelectorAll('.card, .stat-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 + (index * 80));
    });
});

// Confirm delete with custom message
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    });
}, 5000);