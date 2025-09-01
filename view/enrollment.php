<?php
session_start();
// enrollment


$fullName = $email = $course = $paymentMethod = $cardNumber = $expiryDate = $cvv = '';
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = $_POST['courseSelect'] ?? '';
    $paymentMethod = $_POST['paymentMethod'] ?? '';
    $cardNumber = trim($_POST['cardNumber'] ?? '');
    $expiryDate = trim($_POST['expiryDate'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');
    $isValid = true;

// enrollment
    if (empty($fullName)) {
        $errors['fullName'] = 'Full Name is required.';
        $isValid = false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
        $isValid = false;
    }
    if (empty($course)) {
        $errors['courseSelect'] = 'Please select a course.';
        $isValid = false;
    }
    if (empty($paymentMethod)) {
        $errors['paymentMethod'] = 'Please select a payment method.';
        $isValid = false;
    }
    if ($paymentMethod === 'creditCard' && (empty($cardNumber) || !preg_match('/^[0-9]{13,16}$/', str_replace('-', '', $cardNumber)))) {
        $errors['cardNumber'] = 'Please enter a valid card number.';
        $isValid = false;
    }
    if ($paymentMethod === 'creditCard' && (empty($expiryDate) || !preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiryDate))) {
        $errors['expiryDate'] = 'Please enter a valid expiry date (MM/YY).';
        $isValid = false;
    }
    if ($paymentMethod === 'creditCard' && (empty($cvv) || !preg_match('/^[0-9]{3,4}$/', $cvv))) {
        $errors['cvv'] = 'Please enter a valid CVV.';
        $isValid = false;
    }

    if ($isValid) {
      
        $_SESSION['enrolled_course'] = $course;
        $_SESSION['payment_status'] = 'completed';
        $_SESSION['payment_method'] = $paymentMethod;
        $_SESSION['payment_date'] = date('Y-m-d H:i:s');
        header('location: ../view/progress.php?success=enrolled');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeCraft - Course Enrollment</title>
    <link rel="stylesheet" href="../assets/css/enrollment.css">
</head>
<body>
    <header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="../view/enrollment.php">Enroll</a></li>
                <li><a href="../view/progress.php">Progress</a></li>
                <li><a href="../view/forum.php">Forums</a></li>
                <li><a href="../view/faq.html">FAQ</a></li>
            </ul>
        </nav>  
    </header>
    <main>
        <div class="enrollment-container">
            <h1>Enroll in Your Chosen Course</h1>
            <p>Complete the form below to finalize your enrollment and start learning!</p>

            <form id="enrollmentForm" method="post" action="" novalidate>
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($fullName); ?>" required>
                    <div class="error-message" id="fullNameError"><?php echo $errors['fullName'] ?? ''; ?></div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <div class="error-message" id="emailError"><?php echo $errors['email'] ?? ''; ?></div>
                </div>

                <div class="form-group">
                    <label for="courseSelect">Select Course</label>
                    <select id="courseSelect" name="courseSelect" required>
                        <option value="">--Please choose a course--</option>
                        <option value="html" <?php echo $course === 'html' ? 'selected' : ''; ?>>HTML for Beginners</option>
                        <option value="css" <?php echo $course === 'css' ? 'selected' : ''; ?>>CSS for Beginners</option>
                        <option value="js" <?php echo $course === 'js' ? 'selected' : ''; ?>>JavaScript for Beginners</option>
                        <option value="cplusplus" <?php echo $course === 'cplusplus' ? 'selected' : ''; ?>>C++ for Beginners</option>
                        <option value="sql" <?php echo $course === 'sql' ? 'selected' : ''; ?>>SQL for Beginners</option>
                        <option value="csharp" <?php echo $course === 'csharp' ? 'selected' : ''; ?>>C# for Beginners</option>
                    </select>
                    <div class="error-message" id="courseSelectError"><?php echo $errors['courseSelect'] ?? ''; ?></div>
                </div>

                <div class="form-group">
                    <label for="paymentMethod">Payment Method</label>
                    <select id="paymentMethod" name="paymentMethod" required>
                        <option value="">--Select a payment method--</option>
                        <option value="creditCard" <?php echo $paymentMethod === 'creditCard' ? 'selected' : ''; ?>>Credit Card</option>
                        <option value="paypal" <?php echo $paymentMethod === 'paypal' ? 'selected' : ''; ?>>PayPal</option>
                    </select>
                    <div class="error-message" id="paymentMethodError"><?php echo $errors['paymentMethod'] ?? ''; ?></div>
                </div>

                <div id="creditCardInfo" class="hidden">
                    <div class="form-group">
                        <label for="cardNumber">Card Number</label>
                        <input type="text" id="cardNumber" name="cardNumber" value="<?php echo htmlspecialchars($cardNumber); ?>" placeholder="xxxx-xxxx-xxxx-xxxx">
                        <div class="error-message" id="cardNumberError"><?php echo $errors['cardNumber'] ?? ''; ?></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiryDate">Expiry Date</label>
                            <input type="text" id="expiryDate" name="expiryDate" value="<?php echo htmlspecialchars($expiryDate); ?>" placeholder="MM/YY">
                            <div class="error-message" id="expiryDateError"><?php echo $errors['expiryDate'] ?? ''; ?></div>
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" value="<?php echo htmlspecialchars($cvv); ?>" placeholder="123">
                            <div class="error-message" id="cvvError"><?php echo $errors['cvv'] ?? ''; ?></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Enroll Now</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 CodeCraft. All rights reserved.</p>
    </footer>

    <script src="../assets/js/enrollment.js"></script>
</body>
</html>
