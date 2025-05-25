// BERUDI - Main JavaScript File

$(document).ready(function() {
    // Reservation cancellation
    function cancelReservation(reservationId) {
        if(confirm('Are you sure you want to cancel this reservation?')) {
            $.post('cancel_reservation.php', {
                reservation_id: reservationId
            }, function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert('Error cancelling reservation: ' + (response.error || 'Unknown error'));
                }
            }, 'json');
        }
    }

    // Vehicle search functionality
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'search_vehicles.php',
            type: 'GET',
            data: $(this).serialize(),
            success: function(response) {
                $('#vehicleList').html(response);
            },
            error: function() {
                alert('Search failed. Please try again.');
            }
        });
    });

    // Loading animation for buttons
    $('.btn').on('click', function() {
        var $btn = $(this);
        if(!$btn.hasClass('no-loading')) {
            $btn.html('<span class="loading"></span> Loading...');
        }
    });

    // Auto-hide alerts
    $('.alert').delay(5000).fadeOut();
    
    // Smooth scrolling
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if(target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });

    // Form validation enhancement
    $('.form-control').on('blur', function() {
        if($(this).val().trim() === '' && $(this).attr('required')) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
});

// Global functions
window.cancelReservation = function(reservationId) {
    if(confirm('Are you sure you want to cancel this reservation?')) {
        $.post('cancel_reservation.php', {
            reservation_id: reservationId
        }, function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Error cancelling reservation: ' + (response.error || 'Unknown error'));
            }
        }, 'json');
    }
}; 