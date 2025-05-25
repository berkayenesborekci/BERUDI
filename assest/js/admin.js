$(document).ready(function() {
    $('.manage-reservation').on('click', function() {
        var reservationId = $(this).data('id');
        var action = $(this).data('action');
        var actionText = action === 'confirm' ? 'confirm' : 'cancel';
        
        if(confirm('Are you sure you want to ' + actionText + ' this reservation?')) {
            $.post('manage_reservation.php', {
                id: reservationId,
                action: action
            }, function(response) {
                if(response.success) {
                    showAlert('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('danger', 'Error: ' + response.message);
                }
            }, 'json').fail(function() {
                showAlert('danger', 'Connection error. Please try again.');
            });
        }
    });

    $('.edit-vehicle').click(function() {
        var id = $(this).data('id');
        
        $.get('edit_vehicle.php', {id: id}, function(response) {
            if(response.error) {
                showAlert('danger', response.error);
                return;
            }
            
            $('#edit_id').val(response.id);
            $('#edit_model').val(response.model);
            $('#edit_year').val(response.year);
            $('#edit_price').val(response.price);
            $('#edit_horsepower').val(response.horsepower);
            $('#edit_description').val(response.description);
            $('#edit_image_url').val(response.image_url);
            $('#edit_available').prop('checked', response.available == 1);
            // Show modal
            $('#editVehicleModal').modal('show');
        }, 'json').fail(function() {
            showAlert('danger', 'Failed to load vehicle data.');
        });
    });

    $('.view-reservations').click(function() {
        var userId = $(this).data('id');
        $('#reservationsContent').html('<div class="text-center"><div class="loading"></div> Loading...</div>');
        $('#reservationsModal').modal('show');
        
        $.get('get_user_reservations.php', {user_id: userId}, function(data) {
            $('#reservationsContent').html(data);
        }).fail(function() {
            $('#reservationsContent').html('<div class="alert alert-danger">Failed to load reservations.</div>');
        });
    });

    if($('.table').length) {
        $('.table').addClass('table-hover');
        
        if($('#dataTable').length) {
            $('#dataTable').DataTable({
                "pageLength": 10,
                "order": [[ 0, "desc" ]],
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
        }
    }

    $('.admin-form').on('submit', function(e) {
        var isValid = true;
        
        $(this).find('[required]').each(function() {
            if($(this).val().trim() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if(!isValid) {
            e.preventDefault();
            showAlert('warning', 'Please fill in all required fields.');
        }
    });

    $('#image_url, #edit_image_url').on('change', function() {
        var url = $(this).val();
        var previewId = $(this).attr('id') === 'image_url' ? '#imagePreview' : '#editImagePreview';
        
        if(url) {
            $(previewId).html('<img src="' + url + '" class="img-thumbnail" style="max-width: 200px;">');
        } else {
            $(previewId).html('');
        }
    });
});

function showAlert(type, message) {
    var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>';
    
    $('.container').first().prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

window.showAlert = showAlert;
window.confirmDelete = confirmDelete; 
