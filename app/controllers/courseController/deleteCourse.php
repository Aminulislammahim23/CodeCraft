<?php
session_start();
require_once '../../models/courseModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id === null) {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../../views/admin/dashboard.php?error=empty_course_id");
        } else {
            header("Location: ../../views/instructor/dashboard.php?error=empty_course_id");
        }
        exit;
    }

    $result = deleteCourse($id);

    if ($result) {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../../views/admin/dashboard.php?success=course_deleted");
        } else {
            header("Location: ../../views/instructor/dashboard.php?success=course_deleted");
        }
        exit;
    } else {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../../views/admin/dashboard.php?error=course_delete_failed");
        } else {
            header("Location: ../../views/instructor/dashboard.php?error=course_delete_failed");
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