<?php
session_start();

//Auth check
if (!isset($_SESSION['status']) && !isset($_COOKIE['status'])) {
    header('Location: ../view/login.php?error=badrequest');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/payment.php');
    exit();
}

//Collect POST data
$paymentMethod = $_POST['paymentMethod'] ?? '';
$coupon = $_POST['coupon'] ?? '';
$cardNumber = $_POST['cardNumber'] ?? '';
$expiry = $_POST['expiry'] ?? '';
$cvv = $_POST['cvv'] ?? '';

$finalPrice = 49; 

//Validate coupon
if ($coupon === 'DISCOUNT10') {
    $finalPrice -= 10;
} elseif ($coupon !== '') {
    die("Invalid coupon code.");
}

//Validate payment method
if ($paymentMethod === 'card') {
    if (empty($cardNumber) || empty($expiry) || empty($cvv)) {
        die("Card details cannot be empty.");
    }
    if (!preg_match('/^\d{16}$/', $cardNumber)) die("Invalid card number.");
    if (!preg_match('/^\d{2}\/\d{2}$/', $expiry)) die("Invalid expiry.");
    if (!preg_match('/^\d{3,4}$/', $cvv)) die("Invalid CVV.");
} elseif ($paymentMethod === 'paypal') {
//Paypal
} else {
    die("Invalid payment method.");
}

//Show invoice
$payerName = $_SESSION['username'] ?? 'Guest';
$date = date('Y-m-d');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Invoice</title>
    <link rel="stylesheet" href="../assets/css/payment.css">
</head>
<body>
    <div class="container">
        <h2>!!! Payment Successful !!!</h2>
        <p>Course: Advanced JavaScript</p>
        <p>Paid By: <?php echo htmlspecialchars($payerName); ?></p>
        <p>Amount Paid: $<?php echo $finalPrice; ?></p>
        <p>Date: <?php echo $date; ?></p>
        <p>Payment Method: <?php echo ucfirst($paymentMethod); ?></p>
        <a href="../view/home.php">Go Home</a>
    </div>
</body>
</html>
