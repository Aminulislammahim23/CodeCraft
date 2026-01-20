<?php
require_once 'db.php';   
function addPayment($paymentData) {
    $con = getConnection();
    $user_id = (int)$paymentData['user_id'];
    $course_id = (int)$paymentData['course_id'];
    $amount = (float)$paymentData['amount'];
    $payment_method = mysqli_real_escape_string($con, $paymentData['payment_method']);
    $payment_status = mysqli_real_escape_string($con, $paymentData['payment_status']);
    $sql = "INSERT INTO payments (user_id, course_id, amount, payment_method, payment_status) 
            VALUES ($user_id, $course_id, $amount, '$payment_method', '$payment_status')";
    return mysqli_query($con, $sql);
}
function getPaymentByUserAndCourse($userId, $courseId) {
    $con = getConnection();
    $userId = (int)$userId;
    $courseId = (int)$courseId;
    $sql = "SELECT * FROM payments WHERE user_id = $userId AND course_id = $courseId ORDER BY paid_at DESC LIMIT 1";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
function getPaymentsByUser($userId) {
    $con = getConnection();
    $userId = (int)$userId;
    $sql = "SELECT p.*, c.title as course_title FROM payments p 
            JOIN courses c ON p.course_id = c.id
            WHERE p.user_id = $userId ORDER BY p.paid_at DESC";
    $result = mysqli_query($con, $sql);
    $payments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }
    return $payments;
}
function generateInvoice($paymentId) {
    $con = getConnection();
    $paymentId = (int)$paymentId;
    $sql = "SELECT p.*, c.title as course_title, c.description as course_description, 
                 u.full_name as user_name, u.email as user_email
            FROM payments p 
            JOIN courses c ON p.course_id = c.id
            JOIN users u ON p.user_id = u.id
            WHERE p.id = $paymentId";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
function getMonthlyRevenue() {
    $con = getConnection();
    if (!$con) {
        return 0;
    }
    $sql = "
        SELECT IFNULL(SUM(amount),0) AS monthly_total
        FROM payments
        WHERE payment_status = 'success'
        AND MONTH(paid_at) = MONTH(CURRENT_DATE())
        AND YEAR(paid_at) = YEAR(CURRENT_DATE())
    ";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['monthly_total'];
}