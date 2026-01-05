<?php
session_start();
require_once '../../models/courseModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 1);
    $difficulty = trim($_POST['difficulty'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $price = trim($_POST['price'] ?? '0');
    $rating = trim($_POST['rating'] ?? '0');
    $courseImage = $_FILES['course_image'] ?? null;

    if ($title === '' || $difficulty === '' || $duration === '') {
        // Check if it's from admin or instructor
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../../views/admin/dashboard.php?error=empty_course_fields");
        } else {
            header("Location: ../../views/instructor/dashboard.php?error=empty_course_fields");
        }
        exit;
    }

    // Handle course image upload
    $courseImageName = 'default.png';
    
    if ($courseImage && isset($courseImage['name']) && $courseImage['name'] !== '' && $courseImage['error'] === 0) {
        $ext = strtolower(pathinfo($courseImage['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($ext, $allowed)) {
            // Check file size (max 2MB)
            if ($courseImage['size'] <= 2 * 1024 * 1024) {
                $courseImageName = 'course_' . uniqid() . '.' . $ext;
                $uploadPath = "../../assets/images/courses/" . $courseImageName;
                
                // Create directory if it doesn't exist
                if (!is_dir("../../assets/images/courses/")) {
                    mkdir("../../assets/images/courses/", 0777, true);
                }
                
                if (!move_uploaded_file($courseImage['tmp_name'], $uploadPath)) {
                    $courseImageName = 'default.png'; // Fallback to default on upload failure
                    error_log("Failed to upload course image");
                }
            }
        }
    }

    $course = [
        'title' => $title,
        'description' => $description,
        'category_id' => $category_id,
        'difficulty' => $difficulty,
        'duration' => $duration,
        'price' => $price,
        'rating' => $rating,
        'course_image' => $courseImageName,
    ];

    $result = addCourse($course);

    if ($result) {
        // Redirect based on user role
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../../views/admin/dashboard.php?success=course_added");
        } else {
            header("Location: ../../views/instructor/dashboard.php?success=course_added");
        }
        exit;
    } else {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../../views/admin/dashboard.php?error=course_add_failed");
        } else {
            header("Location: ../../views/instructor/dashboard.php?error=course_add_failed");
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