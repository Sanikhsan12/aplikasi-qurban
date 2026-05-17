
// Initialize tooltips
$(document).ready(function () {
    // Mobile optimization for modals
    if ($(window).width() < 768) {
        $('.modal-dialog').addClass('m-2');
    }

    // Adjust modal for mobile
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.modal-dialog').addClass('m-2');
        } else {
            $('.modal-dialog').removeClass('m-2');
        }
    });

    // Touch optimization for mobile
    if ('ontouchstart' in window) {
        // Make buttons more touch-friendly
        $('.btn, .action-btn').css({
            'min-height': '44px',
            'min-width': '44px'
        });
    }

    // Form validation for reject modals
    $('form').on('submit', function (e) {
        const rejectTextarea = $(this).find('textarea[required]');
        if (rejectTextarea.length && !rejectTextarea.val().trim()) {
            e.preventDefault();
            alert('Alasan penolakan harus diisi!');
            rejectTextarea.focus();
        }
    });

    // Prevent zoom on double-tap for iOS
    document.addEventListener('touchstart', function (event) {
        if (event.touches.length > 1) {
            event.preventDefault();
        }
    }, {
        passive: false
    });

    let lastTouchEnd = 0;
    document.addEventListener('touchend', function (event) {
        const now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);
});

// Modal show/hide events
$(document).on('show.bs.modal', '.modal', function () {
    if ($(window).width() < 768) {
        $(this).find('.modal-dialog').addClass('m-0');
    }
});

$(document).on('hidden.bs.modal', '.modal', function () {
    // Clear textareas when modal is closed
    $(this).find('textarea').val('');
});