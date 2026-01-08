<?php
session_start();
require_once '../../models/courseModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 1);
    $difficulty = trim($_POST['difficulty'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $price = trim($_POST['price'] ?? '0');
    $rating = trim($_POST['rating'] ?? '0');

    if ($id === null || $title === '' || $difficulty === '' || $duration === '') {
        // Check if it's from admin or instructor
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../../views/admin/dashboard.php?error=empty_course_fields");
        } else {
            header("Location: ../../views/instructor/dashboard.php?error=empty_course_fields");
        }
        exit;
    }

    $course = [
        'id' => $id,
        'title' => $title,
        'description' => $description,
        'category_id' => $category_id,
        'difficulty' => $difficulty,
        'duration' => $duration,
        'price' => $price,
        'rating' => $rating
    ];

    $result = updateCourse($course);

    if ($result) {
        // Redirect based on user role
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../../views/admin/dashboard.php?success=course_updated");
        } else {
            header("Location: ../../views/instructor/dashboard.php?success=course_updated");
        }
        exit;
    } else {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../../views/admin/dashboard.php?error=course_update_failed");
        } else {
            header("Location: ../../views/instructor/dashboard.php?error=course_update_failed");
        }
        exit;
    }
} else {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: ../../views/admin/dashboard.php?error=invalid_request");
    } else {
        header("Location: ../../views/instructor/dashboard.php?error=invalid_request");
    }
    exit;
}
?>
