document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const termsCheckbox = document.getElementById('terms');
    const termsError = document.getElementById('termsError');

    
    function showError(inputElement, message) {
        console.error(`${inputElement.id}: ${message}`);
        inputElement.style.border = '2px solid red';
        inputElement.focus();
        alert(message);
    }

    function clearError(inputElement) {
        inputElement.style.border = '';
    }

    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    form.addEventListener('submit', function(event) {
        let isValid = true;
        if (nameInput.value.trim() === '') {
            showError(nameInput, 'Full Name is required.');
            isValid = false;
        } else {
            clearError(nameInput);
        }

        if (emailInput.value.trim() === '') {
            showError(emailInput, 'Email is required.');
            isValid = false;
        } else if (!emailRegex.test(emailInput.value.trim())) {
            showError(emailInput, 'Please enter a valid email address.');
            isValid = false;
        } else {
            clearError(emailInput);
        }

        if (passwordInput.value.length < 8) {
            showError(passwordInput, 'Password must be at least 8 characters long.');
            isValid = false;
        } else {
            clearError(passwordInput);
        }

        
        if (confirmPasswordInput.value === '') {
            showError(confirmPasswordInput, 'Please confirm your password.');
            isValid = false;
        } else if (passwordInput.value !== confirmPasswordInput.value) {
            showError(confirmPasswordInput, 'Passwords do not match.');
            isValid = false;
        } else {
            clearError(confirmPasswordInput);
        }
        
        
        if (!termsCheckbox.checked) {
            termsError.textContent = 'You must agree to the Terms & Conditions.';
            termsError.style.color = 'red';
            isValid = false;
        } else {
            termsError.textContent = ''; 
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});