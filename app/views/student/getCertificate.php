<?php
session_start();
require_once '../../models/courseModel.php';
require_once '../../models/userModel.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}
$courseId = $_GET['course_id'] ?? 0;
$userId = $_SESSION['user_id'];
if ($courseId <= 0) {
    header("Location: dashboard.php?error=invalid_course");
    exit();
}
$progress = getCourseProgress($userId, $courseId);
if ($progress < 100) {
    header("Location: courseView.php?course_id=$courseId&error=requirements_not_met");
    exit();
}
$course = getCourseById($courseId);
$user = getUserById($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Certificate | <?= htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="../../assets/css/student.css">
    <style>
        .certificate-container {
            background: #fff;
            padding: 60px;
            border: 15px solid #4f46e5;
            border-radius: 10px;
            max-width: 900px;
            margin: 40px auto;
            text-align: center;
            position: relative;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .certificate-header {
            color: #4f46e5;
            font-size: 3rem;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 30px;
        }
        .cert-body {
            font-size: 1.2rem;
            color: #334155;
            line-height: 1.6;
        }
        .student-name {
            font-size: 2.5rem;
            color: #1e293b;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
        }
        .course-title {
            font-size: 1.8rem;
            color: #4f46e5;
            font-weight: 600;
            margin: 10px 0;
        }
        .cert-date {
            margin-top: 40px;
            font-style: italic;
            color: #64748b;
        }
        .seal {
            margin-top: 30px;
            font-size: 4rem;
            color: #fbbf24;
        }
        .print-btn {
            background: #4f46e5;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            margin-top: 30px;
            transition: 0.3s;
        }
        .print-btn:hover {
            background: #3a35c5;
        }
        @media print {
            .sidebar, .topbar, .print-btn, .back-link {
                display: none;
            }
            .certificate-container {
                margin: 0;
                border: 20px solid #4f46e5;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-header">Certificate of Completion</div>
        <div class="cert-body">
            This is to certify that
            <div class="student-name"><?= htmlspecialchars($user['full_name']); ?></div>
            has successfully completed the course
            <div class="course-title">"<?= htmlspecialchars($course['title']); ?>"</div>
            with a perfect score on the final assessment.
        </div>
        <div class="seal">
            <i class="fas fa-award"></i>
        </div>
        <div class="cert-date">
            Issued on: <?= date('F j, Y'); ?>
        </div>
        <button onclick="window.print()" class="print-btn">Download / Print Certificate</button>
        <div style="margin-top: 20px;">
            <a href="courseView.php?course_id=<?= $courseId; ?>" class="back-link" style="color: #4f46e5; text-decoration: none;">← Back to Course</a>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>