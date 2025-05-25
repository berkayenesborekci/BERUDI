// BERUDI - Form Validation JavaScript

$(document).ready(function() {
    // Real-time validation
    $('.form-control').on('input blur', function() {
        validateField($(this));
    });

    // Form submission validation
    $('form').on('submit', function(e) {
        var isValid = true;
        var $form = $(this);
        
        $form.find('.form-control[required]').each(function() {
            if(!validateField($(this))) {
                isValid = false;
            }
        });
        
        if(!isValid) {
            e.preventDefault();
            showValidationError('Please fix the errors below and try again.');
        }
    });

    // Password confirmation
    $('#confirm_password').on('input', function() {
        var password = $('#password').val();
        var confirmPassword = $(this).val();
        
        if(password !== confirmPassword) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Passwords do not match.');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Email validation
    $('input[type="email"]').on('blur', function() {
        var email = $(this).val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if(email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
            addValidationMessage($(this), 'Please enter a valid email address.');
        }
    });

    // Phone number validation
    $('input[type="tel"]').on('input', function() {
        var phone = $(this).val().replace(/\D/g, '');
        $(this).val(phone);
        
        if(phone.length > 0 && phone.length < 10) {
            $(this).addClass('is-invalid');
            addValidationMessage($(this), 'Phone number must be at least 10 digits.');
        }
    });

    // Date validation
    $('input[type="date"]').on('change', function() {
        var selectedDate = new Date($(this).val());
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if(selectedDate < today) {
            $(this).addClass('is-invalid');
            addValidationMessage($(this), 'Date cannot be in the past.');
        }
    });

    // Reservation date validation
    $('#start_date, #end_date').on('change', function() {
        var startDate = new Date($('#start_date').val());
        var endDate = new Date($('#end_date').val());
        
        if(startDate && endDate && endDate <= startDate) {
            $('#end_date').addClass('is-invalid');
            addValidationMessage($('#end_date'), 'End date must be after start date.');
        } else {
            $('#end_date').removeClass('is-invalid');
        }
    });
});

function validateField($field) {
    var value = $field.val().trim();
    var isValid = true;
    
    // Remove previous validation state
    $field.removeClass('is-valid is-invalid');
    removeValidationMessage($field);
    
    // Required field validation
    if($field.attr('required') && value === '') {
        $field.addClass('is-invalid');
        addValidationMessage($field, 'This field is required.');
        return false;
    }
    
    // Specific field validations
    var fieldType = $field.attr('type') || 'text';
    var fieldName = $field.attr('name') || '';
    
    switch(fieldType) {
        case 'email':
            if(value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                isValid = false;
                addValidationMessage($field, 'Please enter a valid email address.');
            }
            break;
            
        case 'password':
            if(value && value.length < 6) {
                isValid = false;
                addValidationMessage($field, 'Password must be at least 6 characters long.');
            }
            break;
            
        case 'number':
            if(value && isNaN(value)) {
                isValid = false;
                addValidationMessage($field, 'Please enter a valid number.');
            }
            break;
    }
    
    // Field-specific validations
    switch(fieldName) {
        case 'username':
            if(value && (value.length < 3 || !/^[a-zA-Z0-9_]+$/.test(value))) {
                isValid = false;
                addValidationMessage($field, 'Username must be at least 3 characters and contain only letters, numbers, and underscores.');
            }
            break;
            
        case 'year':
            var currentYear = new Date().getFullYear();
            if(value && (value < 1900 || value > currentYear + 1)) {
                isValid = false;
                addValidationMessage($field, 'Please enter a valid year.');
            }
            break;
            
        case 'price':
            if(value && (parseFloat(value) <= 0)) {
                isValid = false;
                addValidationMessage($field, 'Price must be greater than 0.');
            }
            break;
            
        case 'horsepower':
            if(value && (parseInt(value) <= 0 || parseInt(value) > 2000)) {
                isValid = false;
                addValidationMessage($field, 'Horsepower must be between 1 and 2000.');
            }
            break;
    }
    
    if(isValid && value !== '') {
        $field.addClass('is-valid');
    } else if(!isValid) {
        $field.addClass('is-invalid');
    }
    
    return isValid;
}

function addValidationMessage($field, message) {
    removeValidationMessage($field);
    
    var feedback = $('<div class="invalid-feedback">' + message + '</div>');
    $field.after(feedback);
}

function removeValidationMessage($field) {
    $field.siblings('.invalid-feedback').remove();
}

function showValidationError(message) {
    var alert = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                  message +
                  '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                  '</div>');
    
    $('form').first().before(alert);
    
    // Scroll to top
    $('html, body').animate({scrollTop: 0}, 500);
}

// Export functions
window.validateField = validateField;
window.showValidationError = showValidationError; 