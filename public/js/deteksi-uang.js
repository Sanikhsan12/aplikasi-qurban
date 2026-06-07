let stream = null;
let capturedBlob = null;

/* ---- Drag & Drop ---- */
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');

    if (!uploadArea) return;

    uploadArea.addEventListener('click', function(e) {
        if (e.target.closest('.btn-upload-select') || e.target.closest('.preview-remove')) return;
        fileInput.click();
    });

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(evt) {
        uploadArea.addEventListener(evt, function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    ['dragenter', 'dragover'].forEach(function(evt) {
        uploadArea.addEventListener(evt, function() {
            uploadArea.classList.add('drag-over');
        });
    });

    ['dragleave', 'drop'].forEach(function(evt) {
        uploadArea.addEventListener(evt, function(e) {
            uploadArea.classList.remove('drag-over');
        });
    });

    uploadArea.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            previewFile({ target: { files: files } });
        }
    });
});

/* ---- Camera ---- */
function bukaKamera() {
    const video = document.getElementById('video');
    const btnCapture = document.getElementById('btnCapture');

    if (stream) {
        stream.getTracks().forEach(function(t) { t.stop(); });
        stream = null;
        video.srcObject = null;
        video.style.display = 'none';
        btnCapture.disabled = true;
        btnCapture.innerHTML = '<i class="fas fa-camera-retro"></i> Ambil Foto';
        document.querySelector('.btn-camera').innerHTML = '<i class="fas fa-camera"></i> Buka Kamera';
        return;
    }

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(s) {
            stream = s;
            video.srcObject = s;
            video.style.display = 'block';
            btnCapture.disabled = false;
            btnCapture.innerHTML = '<i class="fas fa-camera-retro"></i> Ambil Foto';
            document.querySelector('.btn-camera').innerHTML = '<i class="fas fa-times"></i> Tutup Kamera';
        })
        .catch(function(err) {
            alert('Tidak dapat mengakses kamera: ' + err.message);
        });
}

function ambilFoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);

    canvas.toBlob(function(blob) {
        capturedBlob = blob;
        showPreview(blob, 'capture.jpg', blob.size);

        document.getElementById('btnDetect').disabled = false;

        if (stream) {
            stream.getTracks().forEach(function(t) { t.stop(); });
            stream = null;
            video.srcObject = null;
            video.style.display = 'none';
            document.getElementById('btnCapture').disabled = true;
            document.querySelector('.btn-camera').innerHTML = '<i class="fas fa-camera"></i> Buka Kamera';
        }
    }, 'image/jpeg', 0.9);
}

/* ---- Upload ---- */
function previewFile(event) {
    const file = event.target.files[0];
    if (!file) return;
    capturedBlob = file;
    const reader = new FileReader();
    reader.onload = function(e) {
        showPreview(e.target.result, file.name, file.size);
        document.getElementById('btnDetect').disabled = false;
    };
    reader.readAsDataURL(file);
}

function showPreview(src, name, size) {
    const preview = document.getElementById('preview');
    preview.src = src instanceof Blob ? URL.createObjectURL(src) : src;

    document.getElementById('uploadContent').style.display = 'none';
    document.getElementById('uploadPreview').style.display = 'block';
    document.getElementById('uploadArea').classList.add('has-file');

    document.getElementById('fileName').textContent = name;

    const formatted = formatFileSize(size);
    document.getElementById('fileSize').textContent = formatted;
}

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

function removeFile() {
    capturedBlob = null;
    document.getElementById('fileInput').value = '';
    document.getElementById('uploadContent').style.display = '';
    document.getElementById('uploadPreview').style.display = 'none';
    document.getElementById('uploadArea').classList.remove('has-file');
    document.getElementById('btnDetect').disabled = true;
    document.getElementById('hasilDeteksi').style.display = 'none';
}

/* ---- Detection ---- */
function deteksiUang() {
    if (!capturedBlob) {
        alert('Ambil foto atau pilih gambar terlebih dahulu.');
        return;
    }

    const formData = new FormData();
    formData.append('image', capturedBlob, 'uang.jpg');

    const hasilDiv = document.getElementById('hasilDeteksi');
    const hasilIcon = document.getElementById('hasilIcon');
    const hasilLabel = document.getElementById('hasilLabel');
    const hasilConfidence = document.getElementById('hasilConfidence');
    const confidenceBar = document.getElementById('confidenceBar');

    hasilDiv.style.display = 'block';
    hasilIcon.className = 'hasil-icon warning';
    hasilIcon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    hasilLabel.textContent = 'Memproses...';
    hasilConfidence.textContent = 'Mohon tunggu sebentar';
    if (confidenceBar) { confidenceBar.style.display = 'none'; }
    document.getElementById('btnDetect').disabled = true;

    fetch('/detect-uang', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        document.getElementById('btnDetect').disabled = false;

        if (data.status === 'error') {
            hasilIcon.className = 'hasil-icon error';
            hasilIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            hasilLabel.textContent = 'Error: ' + (data.message || 'Terjadi kesalahan');
            hasilConfidence.textContent = '';
            if (confidenceBar) { confidenceBar.style.display = 'none'; }
            return;
        }

        var label = data.kesimpulan || 'tidak terdeteksi';
        var conf = data.detections && data.detections.length > 0
            ? (data.detections[0].confidence * 100).toFixed(1)
            : 0;

        var isAsli = label.toLowerCase() === 'asli';
        var cls, icon, text;

        if (isAsli) {
            cls = 'success'; icon = '<i class="fas fa-check-circle"></i>';
            text = 'Uang Asli';
        } else if (label.toLowerCase() === 'palsu') {
            cls = 'error'; icon = '<i class="fas fa-times-circle"></i>';
            text = 'Uang Palsu';
        } else {
            cls = 'warning'; icon = '<i class="fas fa-question-circle"></i>';
            text = 'Tidak Terdeteksi';
        }

        hasilIcon.className = 'hasil-icon ' + cls;
        hasilIcon.innerHTML = icon;
        hasilLabel.textContent = text;
        hasilConfidence.textContent = 'Confidence: ' + conf + '%';

        if (confidenceBar) {
            confidenceBar.style.display = 'block';
            var bar = confidenceBar.querySelector('.confidence-fill');
            if (bar) {
                bar.style.width = conf + '%';
                bar.className = 'confidence-fill';
                if (conf >= 70) bar.classList.add('high');
                else if (conf >= 40) bar.classList.add('medium');
                else bar.classList.add('low');
            }
        }
    })
    .catch(function(err) {
        document.getElementById('btnDetect').disabled = false;
        hasilIcon.className = 'hasil-icon error';
        hasilIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
        hasilLabel.textContent = 'Koneksi error: ' + err.message;
        hasilConfidence.textContent = 'Pastikan Flask server berjalan di port 5000';
        if (confidenceBar) { confidenceBar.style.display = 'none'; }
    });
}
