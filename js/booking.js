document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    
    // Set minimum date as today
    const today = new Date().toISOString().split('T')[0];
    checkIn.min = today;
    
    // Update checkout minimum date when checkin changes
    checkIn.addEventListener('change', function() {
        checkOut.min = checkIn.value;
        if (checkOut.value && checkOut.value < checkIn.value) {
            checkOut.value = checkIn.value;
        }
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // Add form validation and submission logic here
        const formData = new FormData(form);
        fetch('index.php?option=com_whiteleafbooking&task=booking.checkAvailability', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to confirmation page
                window.location.href = 'index.php?option=com_whiteleafbooking&view=confirmation';
            } else {
                alert('No rooms available for the selected dates.');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});