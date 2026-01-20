<?php
session_start();
require_once '../../models/courseModel.php';
require_once '../../models/userModel.php';

function getAvatarPath($avatarFilename)
{
    $avatar = $avatarFilename ?? 'default.png';
    return "../../assets/uploads/users/avatars/" . htmlspecialchars($avatar);
}

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
if (!isUserEnrolled($userId, $courseId)) {
    header("Location: dashboard.php?error=not_enrolled");
    exit();
}
$course = getCourseById($courseId);
$lessons = getLessonsByCourseId($courseId);
$progress = getCourseProgress($userId, $courseId);
if (!$course) {
    header("Location: dashboard.php?error=course_not_found");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['title']); ?> | Course Content</title>
    <link rel="stylesheet" href="../../assets/css/student.css">
    <style>
        .course-view-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .course-header {
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .lesson-list {
            list-style: none;
            padding: 0;
        }
        .lesson-item {
            background: #f8f9fa;
            margin-bottom: 15px;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
        }
        .lesson-item:hover {
            background: #e9ecef;
        }
        .lesson-info h4 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .lesson-info p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .watch-btn {
            background: #4f46e5;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }
        .watch-btn:hover {
            background: #3a35c5;
        }
        .quiz-btn {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        .quiz-btn:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-2px);
        }
        .cert-btn {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        .cert-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        }
        .no-lessons {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        
        <aside class="sidebar">
            <img src="../../assets/img/logo.png" class="brand-logo">
            <h2 class="logo">Welcome to CodeCraft</h2>
            <ul class="menu">
                <li>
                    <a href="dashboard.php#dashboard">📊 Dashboard</a>
                </li>
                <li>
                    <a href="dashboard.php#courses">📚 Courses</a>
                </li>
                <li class="active">
                    <a href="dashboard.php#enrollments">📦 Enrollments</a>
                </li>
                <li>
                    <a href="../../controllers/studentController/invoices.php">📄 Invoices</a>
                </li>
                <li>
                    <a href="../../controllers/studentController/profile.php">👤 Profile</a>
                </li>
                <li>
                    <a href="../../controllers/logout.php">🚪 Logout</a>
                </li>
            </ul>
        </aside>
        <main class="main">
            
            <header class="topbar">
                <h1>Course Content</h1>
                <div class="student-info">
                    <img src="<?= getAvatarPath($_SESSION['avatar'] ?? null); ?>" 
                         alt="<?= htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?> Avatar" 
                         class="user-avatar"
                         onerror="this.onerror=null; this.src='<?= getAvatarPath('default.png'); ?>';">
                    <span><?= htmlspecialchars($_SESSION['full_name'] ?? 'student'); ?></span>
                </div>
            </header>
            <div class="course-view-container">
                <div class="course-header">
                    <h2><?= htmlspecialchars($course['title']); ?></h2>
                    <p><?= htmlspecialchars($course['description']); ?></p>
                </div>
                <h3>Lessons</h3>
                <div class="lesson-grid">
                    <?php if (!empty($lessons)): ?>
                        <ul class="lesson-list">
                            <?php foreach ($lessons as $lesson): ?>
                                <li class="lesson-item">
                                    <div class="lesson-info">
                                        <h4><?= htmlspecialchars($lesson['title']); ?></h4>
                                        <p>Order: <?= $lesson['lesson_order']; ?></p>
                                    </div>
                                    <?php if ($lesson['video_url']): ?>
                                        <a href="<?= htmlspecialchars($lesson['video_url']); ?>" target="_blank" class="watch-btn">▶ Watch Lesson</a>
                                    <?php else: ?>
                                        <span class="watch-btn" style="background: #ccc; cursor: not-allowed;">No Video</span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="no-lessons">No lessons have been added to this course yet.</p>
                    <?php endif; ?>
                </div>
                <div style="margin-top: 30px; display: flex; gap: 15px; align-items: center;">
                    <a href="dashboard.php#enrollments" class="back-to-dashboard" style="margin-top: 0;">← Back to Enrollments</a>
                    <a href="takeQuiz.php?course_id=<?= $course['id']; ?>" class="quiz-btn">Get Quiz</a>
                    <?php if ($progress >= 100): ?>
                        <a href="getCertificate.php?course_id=<?= $course['id']; ?>" class="cert-btn">Get Certificate</a>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="../../assets/js/student.js"></script>
</body>
</html>