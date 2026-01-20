<?php
session_start();
require_once '../models/courseModel.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../views/auth/login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $courseId = (int)($_POST['course_id'] ?? 0);
    error_log("Enrollment attempt - User ID: $userId, Course ID: $courseId");
    if ($courseId <= 0) {
        error_log("Invalid course ID: $courseId");
        header("Location: ../views/student/dashboard.php?error=invalid_course");
        exit();
    }
    $course = getCourseById($courseId);
    if (!$course) {
        error_log("Course not found: $courseId");
        header("Location: ../views/student/dashboard.php?error=course_not_found");
        exit();
    }
    error_log("Attempting to enroll user $userId in course $courseId");
    $result = enrollInCourse($userId, $courseId);
    error_log("Enrollment result: " . ($result ? 'success' : 'failed'));
    if ($result) {
        if ($course['price'] <= 0) {
            $_SESSION['receipt'] = [
                'course' => $course['title'],
                'amount' => 0,
                'date' => date('Y-m-d H:i:s')
            ];
            error_log("Redirecting to confirmation page");
            header("Location: ../enrollmentConfirmation.php");
        } else {
            error_log("Redirecting to dashboard with success");
            header("Location: ../views/student/dashboard.php?success=enrolled");
        }
        exit();
    } else {
        error_log("Enrollment failed for user $userId, course $courseId");
        header("Location: ../views/student/dashboard.php?error=enrollment_failed");
        exit();
    }
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    header("Location: ../views/student/dashboard.php?error=invalid_request");
    exit();
}
?>