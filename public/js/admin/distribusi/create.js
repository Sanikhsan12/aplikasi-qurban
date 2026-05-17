
document.addEventListener('DOMContentLoaded', function () {
    // Elements
    const fileInput = document.getElementById('dokumentasi');
    const previewContainer = document.getElementById('previewContainer');
    const cameraModal = document.getElementById('cameraModal');
    const cameraStream = document.getElementById('cameraStream');
    const cameraCanvas = document.getElementById('cameraCanvas');
    const capturedImage = document.getElementById('capturedImage');
    const capturedPreview = document.getElementById('capturedPreview');
    const openCameraBtn = document.getElementById('openCameraBtn');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const usePhotoBtn = document.getElementById('usePhotoBtn');
    const closeCameraBtn = document.getElementById('closeCameraBtn');
    const cameraImagesInput = document.getElementById('cameraImagesInput');

    let stream = null;
    let capturedPhoto = null;
    let imageCounter = 0;

    // Store for all images (file + camera)
    const allImages = {
        files: [],
        camera: []
    };

    // Update file input value
    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        allImages.files.forEach(file => {
            dataTransfer.items.add(file);
        });
        fileInput.files = dataTransfer.files;
    }

    // Update camera images input
    function updateCameraInput() {
        cameraImagesInput.value = JSON.stringify(allImages.camera);
    }

    // Create preview item
    function createPreviewItem(imageData, source, index) {
        const previewItem = document.createElement('div');
        previewItem.className = 'image-preview-item';

        const img = document.createElement('img');
        img.src = imageData;
        img.alt = `Preview ${source} ${index + 1}`;

        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-image-btn';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.onclick = function () {
            removeImage(source, index);
        };

        const sourceBadge = document.createElement('div');
        sourceBadge.className = 'image-source-badge';
        sourceBadge.textContent = source === 'camera' ? 'Kamera' : 'File';

        previewItem.appendChild(img);
        previewItem.appendChild(removeBtn);
        previewItem.appendChild(sourceBadge);

        return previewItem;
    }

    // Remove image
    function removeImage(source, index) {
        if (source === 'file') {
            allImages.files.splice(index, 1);
            updateFileInput();
        } else {
            allImages.camera.splice(index, 1);
            updateCameraInput();
        }
        renderPreviews();
    }

    // Render all previews
    function renderPreviews() {
        previewContainer.innerHTML = '';

        // Render file images
        allImages.files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const previewItem = createPreviewItem(e.target.result, 'file', index);
                previewContainer.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        });

        // Render camera images
        allImages.camera.forEach((imageData, index) => {
            const previewItem = createPreviewItem(imageData, 'camera', index);
            previewContainer.appendChild(previewItem);
        });
    }

    // Handle file selection
    fileInput.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        files.forEach(file => {
            if (file.type.startsWith('image/')) {
                allImages.files.push(file);
            }
        });
        updateFileInput();
        renderPreviews();
    });

    // Open camera modal
    openCameraBtn.addEventListener('click', function () {
        $('#cameraModal').modal('show');
        startCamera();
    });

    // Start camera
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment', // Use back camera on mobile
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            });
            cameraStream.srcObject = stream;

            // Reset UI
            capturedPreview.style.display = 'none';
            retakeBtn.style.display = 'none';
            usePhotoBtn.style.display = 'none';
            captureBtn.style.display = 'inline-block';
        } catch (error) {
            console.error('Error accessing camera:', error);
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan.');
        }
    }

    // Capture photo
    captureBtn.addEventListener('click', function () {
        const context = cameraCanvas.getContext('2d');
        cameraCanvas.width = cameraStream.videoWidth;
        cameraCanvas.height = cameraStream.videoHeight;

        context.drawImage(cameraStream, 0, 0, cameraCanvas.width, cameraCanvas.height);

        capturedPhoto = cameraCanvas.toDataURL('image/jpeg', 0.8);
        capturedImage.src = capturedPhoto;
        capturedPreview.style.display = 'block';

        // Update UI
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'inline-block';
        usePhotoBtn.style.display = 'inline-block';
    });

    // Retake photo
    retakeBtn.addEventListener('click', function () {
        capturedPreview.style.display = 'none';
        retakeBtn.style.display = 'none';
        usePhotoBtn.style.display = 'none';
        captureBtn.style.display = 'inline-block';
        capturedPhoto = null;
    });

    // Use captured photo
    usePhotoBtn.addEventListener('click', function () {
        if (capturedPhoto) {
            allImages.camera.push(capturedPhoto);
            updateCameraInput();
            renderPreviews();

            // Reset camera
            capturedPreview.style.display = 'none';
            retakeBtn.style.display = 'none';
            usePhotoBtn.style.display = 'none';
            captureBtn.style.display = 'inline-block';
            capturedPhoto = null;

            // Close modal if needed
            $('#cameraModal').modal('hide');
        }
    });

    // Close camera
    closeCameraBtn.addEventListener('click', function () {
        stopCamera();
        $('#cameraModal').modal('hide');
    });

    // Stop camera when modal is closed
    $('#cameraModal').on('hidden.bs.modal', function () {
        stopCamera();
    });

    // Stop camera
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
            cameraStream.srcObject = null;
        }
    }

    // Form validation
    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        const totalImages = allImages.files.length + allImages.camera.length;
        if (totalImages === 0) {
            e.preventDefault();
            alert('Harap tambahkan minimal satu gambar dokumentasi.');
            return false;
        }

        // Disable submit button to prevent double submission
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mobile-btn-icon"></i> Menyimpan...';
    });

    // Initial render
    renderPreviews();
});