
// Confirm delete function
function confirmDelete() {
    return confirm('Apakah Anda yakin ingin menghapus data?');
}

// Initialize tooltips
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover'
    });

    // Mobile optimization for modals
    if ($(window).width() < 768) {
        $('.modal-dialog').addClass('m-0');
    }

    // Adjust modal for mobile
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.modal-dialog').addClass('m-0');
        } else {
            $('.modal-dialog').removeClass('m-0');
        }
    });

    // Touch optimization for mobile
    if ('ontouchstart' in window) {
        // Make buttons more touch-friendly
        $('.btn, .action-btn').css({
            'min-height': '44px',
            'min-width': '44px'
        });

        // Increase touch target for table rows
        $('.table tbody tr').css('padding', '12px 0');
    }

    // Close dropdowns when clicking outside on mobile
    $(document).on('click', function (event) {
        if (!$(event.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });

    // Smooth scroll for mobile
    $('a[href^="#"]').on('click', function (e) {
        if ($(window).width() < 768) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $($(this).attr('href')).offset().top - 20
            }, 300);
        }
    });

    // Initialize carousels when modal opens
    $('.modal').on('shown.bs.modal', function () {
        var carouselId = $(this).find('.carousel').attr('id');
        if (carouselId) {
            $('#' + carouselId).carousel();
        }

        // Add dark theme class to modal
        $(this).find('.modal-content').addClass('bg-dark text-light');
    });

    // Pause carousel on hover (desktop only)
    if ($(window).width() > 768) {
        $('.carousel').hover(
            function () {
                $(this).carousel('pause');
            },
            function () {
                $(this).carousel('cycle');
            }
        );
    }

    // Keyboard navigation for carousel
    $(document).keydown(function (e) {
        if ($('.modal.show').length > 0) {
            var activeModal = $('.modal.show');
            var carousel = activeModal.find('.carousel');

            if (carousel.length > 0) {
                if (e.keyCode == 37) { // left arrow
                    carousel.carousel('prev');
                } else if (e.keyCode == 39) { // right arrow
                    carousel.carousel('next');
                }
            }
        }
    });

    // Custom carousel interval
    $('.carousel').carousel({
        interval: 5000, // 5 seconds
        wrap: true,
        keyboard: true
    });
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