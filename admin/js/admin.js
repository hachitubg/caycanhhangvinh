// Admin Panel JavaScript

// Remove alert after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }, 5000);
    });

    // Mobile sidebar toggle
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', (e) => {
        if (sidebar && !sidebar.contains(e.target) && !e.target.classList.contains('sidebar-toggle')) {
            sidebar.classList.remove('show');
        }
    });
});

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Show loading spinner
function showSpinner() {
    const spinner = document.createElement('div');
    spinner.className = 'spinner';
    spinner.id = 'loading-spinner';
    document.body.appendChild(spinner);
}

// Hide loading spinner
function hideSpinner() {
    const spinner = document.getElementById('loading-spinner');
    if (spinner) {
        spinner.remove();
    }
}

// Confirm dialog
function confirmDelete(message = 'Bạn chắc chắn muốn xóa?') {
    return confirm(message);
}

// Local storage helpers
const AdminStorage = {
    set: (key, value) => {
        localStorage.setItem('admin_' + key, JSON.stringify(value));
    },
    get: (key) => {
        const item = localStorage.getItem('admin_' + key);
        return item ? JSON.parse(item) : null;
    },
    remove: (key) => {
        localStorage.removeItem('admin_' + key);
    },
    clear: () => {
        Object.keys(localStorage).forEach(key => {
            if (key.startsWith('admin_')) {
                localStorage.removeItem(key);
            }
        });
    }
};

// API call helper
async function apiCall(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    };

    const config = { ...defaultOptions, ...options };

    try {
        const response = await fetch(url, config);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

// Validate form inputs
function validateForm(form) {
    const formData = new FormData(form);
    const errors = [];

    for (let [key, value] of formData.entries()) {
        if (key.includes('required') && !value) {
            errors.push(`${key} không được để trống`);
        }
    }

    return {
        isValid: errors.length === 0,
        errors: errors
    };
}

// Auto-save feature
function enableAutoSave(form, storageKey) {
    form.addEventListener('input', function() {
        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            if (data[key]) {
                if (!Array.isArray(data[key])) {
                    data[key] = [data[key]];
                }
                data[key].push(value);
            } else {
                data[key] = value;
            }
        }

        AdminStorage.set(storageKey, data);
    });

    // Restore saved data
    const saved = AdminStorage.get(storageKey);
    if (saved) {
        for (let [key, value] of Object.entries(saved)) {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = value;
            }
        }
    }

    // Clear saved data on successful submit
    form.addEventListener('submit', function() {
        AdminStorage.remove(storageKey);
    });
}

// Image upload preview with drag and drop
function initImageUpload(inputSelector, containerSelector) {
    const input = document.querySelector(inputSelector);
    const container = document.querySelector(containerSelector);

    if (!input || !container) return;

    // Drag and drop
    const dropZone = input.parentElement;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.borderColor = 'var(--primary-color)';
            dropZone.style.backgroundColor = 'rgba(25, 135, 84, 0.08)';
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.borderColor = 'var(--primary-color)';
            dropZone.style.backgroundColor = '';
        });
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        input.files = files;

        // Trigger change event
        const event = new Event('change', { bubbles: true });
        input.dispatchEvent(event);
    });
}

// Table row selection
function initTableSelection() {
    const selectAll = document.querySelector('input[name="select-all"]');
    const checkboxes = document.querySelectorAll('input[name="selected[]"]');

    if (selectAll) {
        selectAll.addEventListener('change', (e) => {
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            if (selectAll) {
                selectAll.checked = allChecked;
            }
        });
    });
}

// Export table to CSV
function exportTableToCSV(tableSelector, filename = 'export.csv') {
    const table = document.querySelector(tableSelector);
    let csv = [];
    let rows = table.querySelectorAll('tr');

    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll('td, th');

        for (let j = 0; j < cols.length; j++) {
            let text = cols[j].innerText.replace(/"/g, '""');
            row.push('"' + text + '"');
        }

        csv.push(row.join(','));
    }

    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.href = URL.createObjectURL(csvFile);
    downloadLink.download = filename;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Initialize tooltips (if using Bootstrap or similar)
function initTooltips() {
    const elements = document.querySelectorAll('[title]');
    elements.forEach(el => {
        el.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip-popup';
            tooltip.textContent = this.getAttribute('title');
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.position = 'fixed';
            tooltip.style.top = (rect.bottom + 10) + 'px';
            tooltip.style.left = (rect.left + rect.width / 2) + 'px';
            tooltip.style.transform = 'translateX(-50%)';
            tooltip.style.background = 'var(--dark-color)';
            tooltip.style.color = 'white';
            tooltip.style.padding = '5px 10px';
            tooltip.style.borderRadius = '4px';
            tooltip.style.fontSize = '12px';
            tooltip.style.zIndex = '1000';
            tooltip.style.whiteSpace = 'nowrap';

            this.tooltipElement = tooltip;
        });

        el.addEventListener('mouseleave', function() {
            if (this.tooltipElement) {
                this.tooltipElement.remove();
                delete this.tooltipElement;
            }
        });
    });
}

// Initialize on load
window.addEventListener('load', function() {
    initTableSelection();
    initTooltips();
});
