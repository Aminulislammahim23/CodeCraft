<?php
session_start();
require_once '../model/mydb.php';

header('Content-Type: application/json');

$response = ['success' => false];

if (!isset($_SESSION['username'])) {
    $response['error'] = 'User not logged in.';
    echo json_encode($response);
    exit();
}

$currentUserEmail = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        if (enrollUser($currentUserEmail, $course, $paymentMethod)) {
            $response['success'] = true;
            $response['course'] = $course;
        } else {
            $response['error'] = 'You are already enrolled in this course or an error occurred.';
        }
    } else {
        $response['errors'] = $errors;
    }

    echo json_encode($response);
    exit();
}
?>
