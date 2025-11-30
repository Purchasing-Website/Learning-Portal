// Get the "Add to Cart" button by its ID

var addInventory = document.getElementById('btnaddinventory');
 
var closeAlert = document.getElementById('close_alert');
// Variable to keep track of cart item count
            
// Add a click event listener
addInventory.addEventListener('click', function() {
    // Show the success message by removing 'd-none'
    document.getElementById('successMessage').classList.remove('d-none');
 
    // Start the fade-out process after 2 seconds
    setTimeout(function() {
        var successMessage = document.getElementById('successMessage');
        successMessage.style.transition = 'opacity 0.5s';  // Set transition for fade effect
        successMessage.style.opacity = '0';  // Start fading out
 
        // After fade-out, hide the message by adding 'd-none' again
        setTimeout(function() {
            successMessage.classList.add('d-none');  // Hide after fade-out
            successMessage.style.opacity = '1';  // Reset opacity for next time
        }, 500);  // 500ms for the fade-out effect
    }, 2000);  // 2000ms before starting the fade-out
    
    // Add a click event listener
    closeAlert.addEventListener('click', function() {
        successMessage.classList.add('d-none');
        successMessage.style.opacity = '1';  // Reset opacity for next time
    });
});
 
 
 
