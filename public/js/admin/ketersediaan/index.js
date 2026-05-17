// Initialize tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

// Confirm delete function
function confirmDelete() {
    return confirm('Apakah Anda yakin ingin menghapus data ini?');
}

// Auto-hide alerts after 5 seconds
setTimeout(function () {
    $('.alert').alert('close');
}, 5000);

// Format number inputs on blur
$(document).on('blur', 'input[name="min_harga"], input[name="max_harga"]', function () {
    let value = $(this).val();
    if (value) {
        $(this).val(parseInt(value).toLocaleString('id-ID'));
    }
});

// Remove formatting on focus
$(document).on('focus', 'input[name="min_harga"], input[name="max_harga"]', function () {
    let value = $(this).val();
    $(this).val(value.replace(/[^\d]/g, ''));
});

// Photo thumbnail click handler
$(document).on('click', '.photo-thumbnail', function () {
    let targetModal = $(this).data('target');
    $(targetModal).modal('show');
});

// Fade in animation for new elements
function animateElements() {
    $('.fade-in').each(function (i) {
        $(this).delay(i * 100).queue(function (next) {
            $(this).css({
                'opacity': '1',
                'transform': 'translateY(0)'
            });
            next();
        });
    });
}

// Initialize animations on page load
$(document).ready(function () {
    animateElements();

    // Add loading animation to buttons
    $('.btn-custom, .action-btn').on('click', function () {
        $(this).addClass('disabled');
        setTimeout(() => {
            $(this).removeClass('disabled');
        }, 1000);
    });
});