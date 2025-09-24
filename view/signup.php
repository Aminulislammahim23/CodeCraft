<?php
session_start();
$errors = [];
if (isset($_SESSION['signup_errors'])) {
    $errors = $_SESSION['signup_errors'];
    unset($_SESSION['signup_errors']); 
}

// $old_name = $_SESSION['old_signup_data']['name'] ?? '';
// $old_username = $_SESSION['old_signup_data']['username'] ?? '';
// $old_email = $_SESSION['old_signup_data']['email'] ?? '';
// $old_dob = $_SESSION['old_signup_data']['dob'] ?? '';
// unset($_SESSION['old_signup_data']); 


$role = $_GET['role'] ?? 'student';
$_SESSION['signup_role'] = $role;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Signup with CodeCraft</title>
    <link rel="stylesheet" href="../assets/css/signup.css" />
</head>
<body>
    <div class="signup-container">
        <h2>Signup as <?php echo isset($_SESSION['signup_role']) ? htmlspecialchars($_SESSION['signup_role']) : ''; ?></h2>
        <form id="signupForm" method="POST" action="../controller/signupCheck.php">
            <div class="input-group">
                <input type="text" id="name" name="name" value="" />
                <label for="name">Name</label>
                <?php if (isset($errors['name'])): ?>
                    <p class="error-message"><?php echo $errors['name']; ?></p>
                <?php endif; ?>
            </div>
            <div class="input-group">
                <input type="text" id="username" name="username" value="" />
                <label for="username">Username</label>
                <?php if (isset($errors['username'])): ?>
                    <p class="error-message"><?php echo $errors['username']; ?></p>
                <?php endif; ?>
            </div>
            <div class="input-group">
                <input type="email" id="email" name="email" value=""/>
                <label for="email">Email</label>
                <?php if (isset($errors['email'])): ?>
                    <p class="error-message"><?php echo $errors['email']; ?></p>
                <?php endif; ?>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" required />
                <label for="password">Password</label>
                <?php if (isset($errors['password'])): ?>
                    <p class="error-message"><?php echo $errors['password']; ?></p>
                <?php endif; ?>
            </div>
            <div class="input-group">
                <input type="password" id="confirmPassword" name="confirmPassword" required />
                <label for="confirmPassword">Confirm Password</label>
                <?php if (isset($errors['confirmPassword'])): ?>
                    <p class="error-message"><?php echo $errors['confirmPassword']; ?></p>
                <?php endif; ?>
            </div>
            <div class="input-group">
                <input type="date" id="dob" name="dob" value=""  />
                <label for="dob">Date of Birth</label>
                <?php if (isset($errors['dob'])): ?>
                    <p class="error-message"><?php echo $errors['dob']; ?></p>
                <?php endif; ?>
            </div>
            <input type="hidden" name="role" value="<?php echo htmlspecialchars($_SESSION['signup_role']); ?>">
            <?php if (isset($errors['general'])): ?>
                <p class="error-message"><?php echo $errors['general']; ?></p>
            <?php endif; ?>
            <button type="submit" class="signup-btn">Sign Up</button>
            <div class="login-link">
                <a href="login.php">Already have an account? Login</a>
            </div>
        </form>
    </div>
</body>
</html>