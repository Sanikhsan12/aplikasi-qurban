
// DOM Elements
const uploadForm = document.getElementById('uploadForm');
const submitBtn = document.getElementById('submitBtn');
const fileInput = document.getElementById('dokumentasi_penyembelihan');
const imagePreview = document.getElementById('imagePreview');
const cameraModal = document.getElementById('cameraModal');
const cameraStream = document.getElementById('cameraStream');
const cameraCanvas = document.getElementById('cameraCanvas');
const captureBtn = document.getElementById('captureBtn');
const closeCameraBtn = document.getElementById('closeCameraBtn');
let stream = null;

// File input change handler
fileInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimum 5MB.');
            this.value = '';
            return;
        }

        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Hanya file gambar yang diizinkan.');
            this.value = '';
            return;
        }

        previewImage(file);
    }
});

// Preview image function
function previewImage(file) {
    const reader = new FileReader();
    reader.onload = function (e) {
        imagePreview.src = e.target.result;
        imagePreview.classList.add('show');
    };
    reader.readAsDataURL(file);
}

// Open camera modal
function openCameraModal() {
    cameraModal.classList.add('show');
    startCamera();
}

// Start camera
async function startCamera() {
    try {
        // Stop any existing stream
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        // Get camera stream
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        });

        cameraStream.srcObject = stream;
    } catch (error) {
        console.error('Error accessing camera:', error);
        alert('Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.');
        closeCameraModal();
    }
}

// Capture photo
captureBtn.addEventListener('click', function () {
    const context = cameraCanvas.getContext('2d');

    // Set canvas dimensions to match video
    cameraCanvas.width = cameraStream.videoWidth;
    cameraCanvas.height = cameraStream.videoHeight;

    // Draw video frame to canvas
    context.drawImage(cameraStream, 0, 0, cameraCanvas.width, cameraCanvas.height);

    // Convert canvas to blob
    cameraCanvas.toBlob(function (blob) {
        // Create file from blob
        const file = new File([blob], `camera_${Date.now()}.jpg`, {
            type: 'image/jpeg',
            lastModified: Date.now()
        });

        // Create DataTransfer and add file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        // Set file input files
        fileInput.files = dataTransfer.files;

        // Trigger change event
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);

        // Close camera modal
        closeCameraModal();

    }, 'image/jpeg', 0.9); // 0.9 = 90% quality
});

// Close camera modal
function closeCameraModal() {
    cameraModal.classList.remove('show');

    // Stop camera stream
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }

    if (cameraStream.srcObject) {
        cameraStream.srcObject = null;
    }
}

// Close camera button click
closeCameraBtn.addEventListener('click', closeCameraModal);

// Close modal when clicking outside
cameraModal.addEventListener('click', function (e) {
    if (e.target === cameraModal) {
        closeCameraModal();
    }
});

// Form validation
uploadForm.addEventListener('submit', function (e) {
    let isValid = true;
    const requiredFields = uploadForm.querySelectorAll('[required]');

    // Reset validation styles
    requiredFields.forEach(field => {
        field.classList.remove('is-invalid', 'is-valid');

        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.add('is-valid');
        }
    });

    // Validate file input
    if (fileInput.files.length === 0) {
        fileInput.classList.add('is-invalid');
        isValid = false;
    } else {
        fileInput.classList.remove('is-invalid');
    }

    if (!isValid) {
        e.preventDefault();
        // Scroll to first error
        const firstError = uploadForm.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            firstError.focus();
        }
    } else {
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
    }
});

// Real-time validation
const formInputs = uploadForm.querySelectorAll('.mobile-form-input, .mobile-form-select');
formInputs.forEach(input => {
    input.addEventListener('blur', function () {
        if (this.hasAttribute('required') && !this.value.trim()) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (this.value.trim()) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    input.addEventListener('input', function () {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
});

// Touch device optimizations
if ('ontouchstart' in window) {
    // Increase touch target size
    const touchElements = document.querySelectorAll('.mobile-btn, .camera-btn, .mobile-form-input');
    touchElements.forEach(el => {
        el.style.minHeight = '44px';
    });
}

// Prevent accidental navigation
let formChanged = false;
uploadForm.querySelectorAll('input, select, textarea').forEach(input => {
    const initialValue = input.value;
    input.addEventListener('input', () => {
        formChanged = true;
    });
});

window.addEventListener('beforeunload', (e) => {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = 'Perubahan yang belum disimpan akan hilang. Yakin ingin keluar?';
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function (e) {
    // Escape to close camera modal
    if (e.key === 'Escape' && cameraModal.classList.contains('show')) {
        closeCameraModal();
    }

    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        submitBtn.click();
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function () {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
});