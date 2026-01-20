<?php
session_start();
$errors = [];
if (isset($_SESSION['signup_errors'])) {
    $errors = $_SESSION['signup_errors'];
    unset($_SESSION['signup_errors']); 
}
$role = $_GET['role'] ?? 'student';
$_SESSION['register_role'] = $role;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeCraft – Create Account</title>
    <link rel="stylesheet" href="../../assets/css/register.css">
</head>
<body>
<div class="signup-container">
    <div class="left-panel">
        <div class="brand">
            <img src="../../assets/img/default.png" alt="CodeCraft Logo" class="brand-logo">
        </div>
        <h1>Create Your Account</h1>
        <p class="subtitle">Start learning and track your coding journey.</p>
        <form class="register-form" id="registerForm" method="post" action="../../controllers/registerCheck.php" enctype="multipart/form-data">
            <label for="name">Full Name</label>
            <input type="text" class="register-form input" id="name" name="name" value="" />
            <?php if (isset($errors['name'])): ?>
                <p class="error-message"><?php echo $errors['name']; ?></p>
            <?php endif; ?>
            <label for="email">Email</label>
            <input type="email" class="register-form input" id="email" name="email" value=""/>
                <?php if (isset($errors['email'])): ?>
                    <p class="error-message"><?php echo $errors['email']; ?></p>
                <?php endif; ?>
            <label for="password">Password</label>
            <input type="password" class="register-form input" id="password" name="password"/>
                <?php if (isset($errors['password'])): ?>
                    <p class="error-message"><?php echo $errors['password']; ?></p>
                <?php endif; ?>
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" class="register-form input" id="confirmPassword" name="confirmPassword"/>
                <?php if (isset($errors['confirmPassword'])): ?>
                    <p class="error-message"><?php echo $errors['confirmPassword']; ?></p>
                <?php endif; ?>
            <input type="hidden" class="register-form input" name="role" value="<?php echo htmlspecialchars($_SESSION['register_role']); ?>">
            <?php if (isset($errors['general'])): ?>
                <p class="error-message"><?php echo $errors['general']; ?></p>
            <?php endif; ?>
            <label>
                <input type="checkbox" class="register-form input" id="terms" name="terms"> I agree to Terms & Conditions
            </label>
            <p class="error" id="termsError"></p>
            <button type="submit" class="btn">Sign Up</button>
            <div class="links">
                <a href="login.php">Already have an account?</a>
                <a href="login.php" class="btn">Login</a>
            </div>
        </form>
    </div>
    <div class="right-panel">
        <div class="overlay-text">
            <h2>Join Thousands of Learners</h2>
            <p>Start learning Python, Java, C++, Kotlin, Web Dev and more.</p>
        </div>
    </div>
</div>
<script src="../../assets/js/register.js"></script>
</body>
</html>