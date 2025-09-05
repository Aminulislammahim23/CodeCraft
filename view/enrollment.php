<?php
session_start();
require_once '../model/mydb.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user = getUserByEmail($_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false];

    $currentUserEmail = $_SESSION['username'];
    $fullName = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = $_POST['courseSelect'] ?? '';
    $paymentMethod = $_POST['paymentMethod'] ?? '';
    $cardNumber = trim($_POST['cardNumber'] ?? '');
    $expiryDate = trim($_POST['expiryDate'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');
    $errors = [];


    if (empty($fullName)) $errors['fullName'] = 'Full Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';
    if (empty($course)) $errors['courseSelect'] = 'Please select a course.';
    if (empty($paymentMethod)) $errors['paymentMethod'] = 'Please select a payment method.';
    if ($paymentMethod === 'creditCard' && (empty($cardNumber) || !preg_match('/^[0-9]{13,16}$/', str_replace('-', '', $cardNumber)))) {
        $errors['cardNumber'] = 'Please enter a valid card number.';
    }
    if ($paymentMethod === 'creditCard' && (empty($expiryDate) || !preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiryDate))) {
        $errors['expiryDate'] = 'Please enter a valid expiry date (MM/YY).';
    }
    if ($paymentMethod === 'creditCard' && (empty($cvv) || !preg_match('/^[0-9]{3,4}$/', $cvv))) {
        $errors['cvv'] = 'Please enter a valid CVV.';
    }

    if (empty($errors)) {
        $enrollResult = enrollUser($currentUserEmail, $course, $paymentMethod);
        if ($enrollResult['success']) {
            $response['success'] = true;
            $response['course'] = $course;
        } else {
            $response['error'] = $enrollResult['error'];
        }
    } else {
        $response['errors'] = $errors;
    }

    echo json_encode($response);
    exit();
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

            <form id="enrollmentForm" method="post" novalidate>
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    <div class="error-message" id="fullNameError"></div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <div class="error-message" id="emailError"></div>
                </div>

                <div class="form-group">
                    <label for="courseSelect">Select Course</label>
                    <select id="courseSelect" name="courseSelect" required>
                        <option value="">--Please choose a course--</option>
                        <option value="html">HTML for Beginners</option>
                        <option value="css">CSS for Beginners</option>
                        <option value="js">JavaScript for Beginners</option>
                        <option value="cplusplus">C++ for Beginners</option>
                       
                    </select>
                    <div class="error-message" id="courseSelectError"></div>
                </div>

                <div class="form-group">
                    <label for="paymentMethod">Payment Method</label>
                    <select id="paymentMethod" name="paymentMethod" required>
                        <option value="">--Select a payment method--</option>
                        <option value="creditCard">Credit Card</option>
                        <option value="paypal">PayPal</option>
                    </select>
                    <div class="error-message" id="paymentMethodError"></div>
                </div>

                <div id="creditCardInfo" class="hidden">
                    <div class="form-group">
                        <label for="cardNumber">Card Number</label>
                        <input type="text" id="cardNumber" name="cardNumber" placeholder="xxxx-xxxx-xxxx-xxxx">
                        <div class="error-message" id="cardNumberError"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiryDate">Expiry Date</label>
                            <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY">
                            <div class="error-message" id="expiryDateError"></div>
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123">
                            <div class="error-message" id="cvvError"></div>
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