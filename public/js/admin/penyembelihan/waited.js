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