<?php
$emailErr = "";
$passwordErr = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] === "invalid_user") {
        $passwordErr = "Invalid email or password!";
    } elseif ($_GET['error'] === "badrequest") {
        $emailErr = "Please login first!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeCraft – Login</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>
<div class="login-container">
    <div class="left-panel">
        <div class="brand">
            <img src="../assets/img/logo.png" alt="CodeCraft Logo" class="brand-logo">
        </div>
        <h1 id="demo" >Welcome Back</h1>
        <p class="subtitle">Login to continue learning and tracking your progress.</p>
        <form class="login-form" action="../../controllers/loginCheck.php" method="POST" onsubmit="return validateLogin()">
            <label>Email</label>
            <input type="email" id="email" class="email" name="email">
            <small id="emailError" style="color:red;"> <?= $emailErr ?></small>
            <label>Password</label>
            <input type="password" id="password" class="password" name="password">
            <small id="passwordError" style="color:red;"> <?= $passwordErr ?></small>
            <input type="submit" class="btn" id="loginBtn" name="submit" value="Login"/><br><br>
            <div class="links">
                <a href="forget_password.html">Forgot Password?</a>
                <a href="chooseRole.php">Create Account</a>
            </div>
        </form>
    </div>
    <div class="right-panel">
        <div class="overlay-text">
            <h2>Start Your Coding Journey</h2>
            <p>Learn Python, Java, C++, Web Development and more.</p>
        </div>
    </div>
</div>
<script src="../../assets/js/loginVal.js"></script>
</body>
</html>