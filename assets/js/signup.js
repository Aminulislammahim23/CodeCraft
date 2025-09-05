function validateForm() {
    var errors = {};
    var isValid = true;
// signup
 
    var nameInput = document.getElementById('name');
    var name = nameInput.value.trim();
    if (name === "") {
        errors.name = "Name cannot be empty.";
        isValid = false;
    } else {
        var words = name.split(" ");
        var count = 0;
        for (var i = 0; i < words.length; i++) {
            if (words[i] !== "") {
                count++;
            }
        }
        if (count < 2) {
            errors.name = "Name must contain at least two words.";
            isValid = false;
        }
        const first = name.charAt(0);
        const isLetter = (first >= 'a' && first <= 'z') || (first >= 'A' && first <= 'Z');
        if (!isLetter) {
            errors.name = "Name must start with a letter.";
            isValid = false;
        }
        const allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ .-';
        for (const char of name) {
            if (!allowedChars.includes(char)) {
                errors.name = "Only letters, dot (.), dash (-), and space are allowed in name.";
                isValid = false;
                break;
            }
        }
    }

    // Email validation
    var emailInput = document.getElementById('email');
    var email = emailInput.value.trim();
    if (email === "") {
        errors.email = "Email cannot be empty.";
        isValid = false;
    } else if (!email.includes("@")) {
        errors.email = "Email must contain @.";
        isValid = false;
    } else {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.email = "Invalid email format.";
            isValid = false;
        }
    }

    // Password validation
    var passwordInput = document.getElementById('password');
    var password = passwordInput.value;
    if (password === "") {
        errors.password = "Password cannot be empty.";
        isValid = false;
    } else if (password.length < 8) {
        errors.password = "Password must be at least 8 characters long.";
        isValid = false;
    }

    // Confirm Password validation
    var confirmPasswordInput = document.getElementById('confirmPassword');
    var confirmPassword = confirmPasswordInput.value;
    if (confirmPassword === "") {
        errors.confirmPassword = "Confirm Password cannot be empty.";
        isValid = false;
    } else if (confirmPassword !== password) {
        errors.confirmPassword = "Passwords do not match.";
        isValid = false;
    }

    // Date of Birth validation
    var dobInput = document.getElementById('dob');
    var dob = dobInput.value;
    if (dob === "") {
        errors.dob = "Date of Birth cannot be empty.";
        isValid = false;
    } else {
        var birthDate = new Date(dob);
        var currentDate = new Date();
        if (birthDate > currentDate) {
            errors.dob = "Date of Birth cannot be in the future.";
            isValid = false;
        }
    }

    // Display errors
    clearErrors();
    for (var field in errors) {
        var errorElement = document.createElement('p');
        errorElement.className = 'error-message';
        errorElement.style.color = 'red';
        errorElement.textContent = errors[field];
        var inputElement = document.getElementById(field);
        if (inputElement) {
            inputElement.parentNode.appendChild(errorElement);
        }
    }

    return isValid;
}

function clearErrors() {
    var errorMessages = document.getElementsByClassName('error-message');
    while (errorMessages.length > 0) {
        errorMessages[0].parentNode.removeChild(errorMessages[0]);
    }
}

document.getElementById("signupForm").onsubmit = function (e) {
    if (!validateForm()) {
        e.preventDefault();
    } else {
        // Allow form to submit to signupCheck.php
    }
};