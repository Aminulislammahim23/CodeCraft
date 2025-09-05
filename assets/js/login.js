function validateForm() {
    var usernameInput = document.getElementById('username');
    var username = usernameInput.value.trim();
    if (username === "") {
        alert("Username cannot be empty");
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

    return true;
}

document.querySelector('form').addEventListener('submit', function(event) {
    if (!validateForm()) {
        event.preventDefault();
    }
});
