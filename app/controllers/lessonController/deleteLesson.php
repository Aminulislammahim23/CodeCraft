<?php
session_start();
require_once '../../../models/courseModel.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit();
    }
    $lesson_id = $_POST['id'] ?? '';
    if (empty($lesson_id)) {
        echo json_encode(['success' => false, 'message' => 'Lesson ID is required']);
        exit();
    }
    $instructorId = $_SESSION['user_id'];
    $courses = getCoursesByInstructor($instructorId);
    $validLesson = false;
    foreach ($courses as $course) {
        $courseLessons = getLessonsByCourseId($course['id']);
        foreach ($courseLessons as $lesson) {
            if ($lesson['id'] == $lesson_id) {
                $validLesson = true;
                break 2;
            }
        }
    }
    if (!$validLesson) {
        echo json_encode(['success' => false, 'message' => 'Invalid lesson ID for this instructor']);
        exit();
    }
    $result = deleteLesson($lesson_id);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Lesson deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete lesson']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>