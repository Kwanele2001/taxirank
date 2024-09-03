document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const errorMessageContainer = document.getElementById('error-message');
    
    form.addEventListener('submit', async (event) => {
        event.preventDefault(); // Prevent the default form submission
        
        // Retrieve form data
        const formData = new FormData(form);
        const username = formData.get('username');
        const pin = formData.get('pin');
        
        // Send form data to server via Fetch API
        try {
            const response = await fetch('login.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Redirect on successful login
                window.location.href = result.redirect;
            } else {
                // Display error message
                errorMessageContainer.textContent = result.message;
            }
        } catch (error) {
            errorMessageContainer.textContent = 'An error occurred. Please try again.';
        }
    });
});
