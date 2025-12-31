function validateLogin() {
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value.trim();

    let emailError = document.getElementById('emailError');
    let passwordError = document.getElementById('passwordError');

    emailError.innerText = '';
    passwordError.innerText = '';

    let isValid = true;

    if (email === '') {
        emailError.innerText = 'Email is required.';
        isValid = false;
    } else {
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            emailError.innerText = 'Please enter a valid email address.';
            isValid = false;
        }
    }

    if (password === '') {
        passwordError.innerText = 'Password is required.';
        isValid = false;
    } else if (password.length < 8) {
        passwordError.innerText = 'Password must be at least 8 characters long.';
        isValid = false;
    }
    return isValid;
}