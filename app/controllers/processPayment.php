<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/asset_helper.php';
require_once __DIR__ . '/../models/paymentModel.php';
require_once __DIR__ . '/../models/courseModel.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: " . view_url('auth/login.php'));
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $courseId = (int)($_POST['course_id'] ?? 0);
    $amount = (float)($_POST['amount'] ?? 0);
    $paymentType = $_POST['payment_type'] ?? 'full';
    $paymentMethod = $_POST['payment_method'] ?? 'card';
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrfToken)) {
        header("Location: " . controller_url('studentController/dashboard.php?error=invalid_request'));
        exit();
    }
    if ($courseId <= 0 || $amount <= 0) {
        header("Location: " . view_url('student/dashboard.php?error=invalid_payment_data'));
        exit();
    }
    $existingEnrollment = getEnrollmentByUserAndCourse($userId, $courseId);
    if ($existingEnrollment) {
        header("Location: " . view_url('student/dashboard.php?error=already_enrolled'));
        exit();
    }
    $course = getCourseById($courseId);
    if (!$course) {
        header("Location: " . view_url('student/dashboard.php?error=course_not_found'));
        exit();
    }
    $paymentData = [
        'user_id' => $userId,
        'course_id' => $courseId,
        'amount' => $amount,
        'payment_method' => $paymentMethod,
        'payment_status' => 'success'
    ];
    $paymentResult = addPayment($paymentData);
    if ($paymentResult) {
        $enrollmentResult = enrollInCourse($userId, $courseId);
        if ($enrollmentResult) {
            $_SESSION['receipt'] = [
                'course' => $course['title'],
                'amount' => $amount,
                'date' => date('Y-m-d H:i:s')
            ];
            header("Location: " . controller_url('studentController/enrollmentConfirmation.php'));
            exit();
        } else {
            header("Location: " . view_url('student/dashboard.php?error=enrollment_failed'));
            exit();
        }
    } else {
        header("Location: " . view_url('student/dashboard.php?error=payment_failed'));
        exit();
    }
} else {
    header("Location: " . view_url('student/dashboard.php?error=invalid_request'));
    exit();
}
function getEnrollmentByUserAndCourse($userId, $courseId) {
    $con = getConnection();
    $userId = (int)$userId;
    $courseId = (int)$courseId;
    $sql = "SELECT id FROM enrollments WHERE user_id = $userId AND course_id = $courseId LIMIT 1";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}
?>