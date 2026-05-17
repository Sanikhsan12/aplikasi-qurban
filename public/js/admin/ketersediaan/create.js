
// Tambahkan di script section

// Tombol browse untuk drag & drop area
$('#browseButton').click(function () {
    $('#foto_input').click();
});

// Handle traditional file input
function previewTraditionalFile(input) {
    const fileLabel = $('#fileLabel');

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Update label
        fileLabel.html('<i class="fas fa-file-image mr-2"></i>' + file.name);

        // Juga update drag & drop area
        handleFile(file);

        // Juga set ke hidden input untuk drag & drop
        $('#foto_input').prop('files', input.files);
    } else {
        fileLabel.html('<i class="fas fa-upload mr-2"></i>Pilih file foto...');
    }
}

// Function untuk handle file (sama untuk kedua metode)
function handleFile(file) {
    // Check file size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file maksimal 2MB');
        return false;
    }

    // Check file type
    if (!file.type.match('image.*')) {
        alert('Hanya file gambar yang diperbolehkan');
        return false;
    }

    // Create preview
    const reader = new FileReader();
    reader.onload = function (e) {
        $('#photoPreview').attr('src', e.target.result);
        $('#uploadPlaceholder').hide();
        $('#photoPreview').addClass('active');
        $('#photoPreviewContainer').show();
    }
    reader.readAsDataURL(file);

    return true;
}

// Juga perbaiki event handler untuk drag & drop area
$('#foto_input').change(function (e) {
    if (this.files && this.files[0]) {
        handleFile(this.files[0]);
    }
});

// selesai
$(document).ready(function () {
    // Animal type selection
    $('.animal-type-badge').click(function () {
        const value = $(this).data('value');

        // Update active state
        $('.animal-type-badge').removeClass('active');
        $(this).addClass('active');

        // Update hidden input and clear custom input
        $('#jenis_hewan').val(value);
        $('#jenis_hewan_custom').val('');
    });

    // Custom jenis hewan input
    $('#jenis_hewan_custom').on('input', function () {
        const value = $(this).val().trim();
        if (value) {
            $('.animal-type-badge').removeClass('active');
            $('#jenis_hewan').val(value);
        }
    });

    // Format harga input
    $('#harga_input').on('input', function () {
        const harga = $(this).val();
        if (harga) {
            const formatted = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
            $('#harga_formatted').text(formatted);
            calculateTotals();
        } else {
            $('#harga_formatted').text('Rp 0');
            calculateTotals();
        }
    });

    // Calculate totals
    function calculateTotals() {
        const harga = $('#harga_input').val() || 0;
        const jumlah = $('input[name="jumlah"]').val() || 0;
        const bobot = $('input[name="bobot"]').val() || 0;

        // Total value
        const totalValue = harga * jumlah;
        $('#total_value').text('Rp ' + totalValue.toLocaleString('id-ID'));

        // Estimated meat (40% of weight)
        const estimatedMeat = (bobot * jumlah * 0.4).toFixed(1);
        $('#estimated_meat').text(estimatedMeat + ' kg');
    }

    // Update totals when inputs change
    $('input[name="jumlah"], input[name="bobot"]').on('input', calculateTotals);

    // Photo upload functionality
    const photoUploadArea = $('#photoUploadArea');
    const fotoInput = $('#foto_input');
    const photoPreview = $('#photoPreview');
    const uploadPlaceholder = $('#uploadPlaceholder');
    const photoPreviewContainer = $('#photoPreviewContainer');

    // Click to upload
    photoUploadArea.click(function () {
        fotoInput.click();
    });

    // Drag and drop
    photoUploadArea.on('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });

    photoUploadArea.on('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });

    photoUploadArea.on('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');

        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    // File input change
    fotoInput.change(function (e) {
        if (this.files && this.files[0]) {
            handleFile(this.files[0]);
        }
    });

    // Handle file selection
    function handleFile(file) {
        // Check file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            return;
        }

        // Check file type
        if (!file.type.match('image.*')) {
            alert('Hanya file gambar yang diperbolehkan');
            return;
        }

        // Create preview
        const reader = new FileReader();
        reader.onload = function (e) {
            photoPreview.attr('src', e.target.result);
            uploadPlaceholder.hide();
            photoPreview.addClass('active');
            photoPreviewContainer.show();
        }
        reader.readAsDataURL(file);
    }

    // Remove photo
    $('#photoRemoveBtn').click(function (e) {
        e.stopPropagation();
        fotoInput.val('');
        photoPreview.attr('src', '');
        photoPreview.removeClass('active');
        photoPreviewContainer.hide();
        uploadPlaceholder.show();
    });

    // Form validation and submission
    $('#animalForm').submit(function (e) {
        // Basic validation
        const jenisHewan = $('#jenis_hewan').val();
        if (!jenisHewan) {
            alert('Pilih jenis hewan terlebih dahulu');
            e.preventDefault();
            return;
        }

        // Show loading overlay
        $('#loadingOverlay').show();

        // Disable submit button
        $('#submitBtn').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
    });

    // Reset form
    $('button[type="reset"]').click(function () {
        // Reset active badges
        $('.animal-type-badge').removeClass('active');

        // Reset photo preview
        fotoInput.val('');
        photoPreview.attr('src', '');
        photoPreview.removeClass('active');
        photoPreviewContainer.hide();
        uploadPlaceholder.show();

        // Reset calculations
        $('#harga_formatted').text('Rp 0');
        $('#total_value').text('Rp 0');
        $('#estimated_meat').text('0 kg');

        // Enable submit button if it was disabled
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Simpan Data');
    });

    $(document).ready(function () {

        // Set initial values from old input
        if (window.formOldData && window.formOldData.jenis_hewan) {
            const oldJenis = window.formOldData.jenis_hewan;

            $('.animal-type-badge').each(function () {
                if ($(this).data('value') === oldJenis) {
                    $(this).addClass('active');
                    $('#jenis_hewan').val(oldJenis);
                }
            });
        }

        if (window.formOldData && window.formOldData.harga) {
            $('#harga_input').trigger('input');
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function () {
            $('.alert').alert('close');
        }, 5000);

    });


    // Input validation
    $('input[type="number"]').on('input', function () {
        const min = $(this).attr('min');
        const max = $(this).attr('max');
        const value = parseFloat($(this).val());

        if (min && value < min) {
            $(this).val(min);
        }

        if (max && value > max) {
            $(this).val(max);
        }
    });

    // Add animation to form sections on scroll
    function animateOnScroll() {
        $('.form-section').each(function () {
            const elementTop = $(this).offset().top;
            const elementBottom = elementTop + $(this).outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();

            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).css({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
            }
        });
    }

    // Initial animation
    animateOnScroll();
    $(window).scroll(animateOnScroll);
});