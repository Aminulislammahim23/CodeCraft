<?php
session_start(); 
if (isset($_COOKIE['status'])) {
    header("Location: ../view/home.php");
    exit();
}
///// login
$err1 = "";
$err2 = "";
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    if ($error == "invalid_user") {
        $err1 = "Please type valid username/password!";
    } elseif ($error == "badrequest") {
        $err2 = "Please login first!";
    }
}


$signup_success_message = "";
if (isset($_SESSION['signup_success_message'])) {
    $signup_success_message = $_SESSION['signup_success_message'];
    unset($_SESSION['signup_success_message']); 
}


$pre_fill_username = $_SESSION['signup_credentials']['username'] ?? '';
$pre_fill_password = $_SESSION['signup_credentials']['password'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Login Page</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <style>
        .success-message {
            color: green;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post" action="../controllers/validation.php" enctype="multipart/form-data">
            <?php if (!empty($signup_success_message)): ?>
                <p class="success-message"><?php echo $signup_success_message; ?></p>
            <?php endif; ?>
            <div class="input-group">
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($pre_fill_username); ?>" required>
                <label>Username</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($pre_fill_password); ?>" required>
                <label>Password</label>
            </div>
            <div>
                <p class="error-message"><?php if (isset($err1)) echo $err1; ?></p>
                <p class="error-message"><?php if (isset($err2)) echo $err2; ?></p>
            </div>
            <button type="submit" class="login-btn">Login</button>
            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>
            <div class="signup-link">
                <a href="signup.php">Don't have an account? Sign Up</a>
            </div>
        </form>
    </div>
</body>
</html>
