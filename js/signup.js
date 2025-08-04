function validateForm() {
    var nameInput = document.getElementById('name');
    var name = nameInput.value.trim();
    if (name === "") {
        alert("Name cannot be empty");
        return false;
    }

    var words = name.split(" ");
    var count = 0;
    for (var i = 0; i < words.length; i++) {
        if (words[i] !== "") {
            count++;
        }
    }
    if (count < 2) {
        alert("Name must contain at least two words");
        return false;
    }

    const first = name.charAt(0);
    const isLetter = (first >= 'a' && first <= 'z') || (first >= 'A' && first <= 'Z');
    if (!isLetter) {
        alert("Name must start with a letter.");
        return false;
    }

    const allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ .-';
    for (const char of name) {
        if (!allowedChars.includes(char)) {
            alert("Only letters, dot (.), dash (-), and space are allowed in name.");
            return false;
        }
    }

    var emailInput = document.getElementById('email');
    var email = emailInput.value.trim();
    if (email === "") {
        alert("Email cannot be empty");
        return false;
    }
    if (!email.includes("@")) {
        alert("Email must contain @");
        return false;
    }

    var passwordInput = document.getElementById('password');
    var password = passwordInput.value;
    if (password === "") {
        alert("Password cannot be empty");
        return false;
    }
    if (password.length < 8) {
        alert("Password must be at least 8 characters long");
        return false;
    }

    var confirmPasswordInput = document.getElementById('confirmPassword');
    var confirmPassword = confirmPasswordInput.value;
    if (confirmPassword === "") {
        alert("Confirm Password cannot be empty");
        return false;
    }
    if (confirmPassword !== password) {
        alert("Passwords do not match");
        return false;
    }

    var dobInput = document.getElementById('dob');
    var dob = dobInput.value;
    if (dob === "") {
        alert("Date of Birth cannot be empty");
        return false;
    }

    alert("Form submitted successfully!");
    return true;
}

document.getElementById("signupForm").onsubmit = function (e) {
    e.preventDefault();
    if (validateForm()) {
      
        window.location.href = "login.html";  
    }
};
