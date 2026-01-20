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
if ($courseId <= 0) {
    header("Location: ../dashboard.php?error=invalid_course");
    exit();
}
$course = getCourseById($courseId);
if (!$course) {
    header("Location: ../dashboard.php?error=course_not_found");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in <?= htmlspecialchars($course['title']); ?> | Student Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/student.css">
</head>
<body>
    <div class="admin-container">
        
        <aside class="sidebar">
            <img src="../../assets/img/logo.png" class="brand-logo">
            <h2 class="logo">Welcome to CodeCraft</h2>
            <ul class="menu">
                <li>
                    <a href="../../views/student/dashboard.php">📊 Dashboard</a>
                </li>
                <li class="active">
                    <a href="../../views/student/dashboard.php#courses" onclick="showSection('courses')">📚 Courses</a>
                </li>
                <li>
                    <a href="../../views/student/dashboard.php#enrollments" onclick="showSection('enrollments')">📦 Enrollments</a>
                </li>
                <li>
                    <a href="../../views/student/dashboard.php">👤 Profile</a>
                </li>
                <li>
                    <a href="../../controllers/logout.php">🚪 Logout</a>
                </li>
            </ul>
        </aside>
        <main class="main">
            
            <header class="topbar">
                <h1>Enrollment</h1>
                <div class="student-info">
                    <img src="<?= getAvatarPath($_SESSION['avatar'] ?? null); ?>" 
                         alt="<?= htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?> Avatar" 
                         class="user-avatar"
                         onerror="this.onerror=null; this.src='<?= getAvatarPath('default.png'); ?>';">
                    <span><?= htmlspecialchars($_SESSION['full_name'] ?? 'student'); ?></span>
                </div>
            </header>
            <div class="enrollment-container">
                <div class="enrollment-header">
                    <h1>Enroll in: <?= htmlspecialchars($course['title']); ?></h1>
                    <p><?= htmlspecialchars($course['description']); ?></p>
                </div>
                <div class="course-details">
                    <img src="../../assets/uploads/system/courses/img/<?= htmlspecialchars($course['course_image'] ?? 'default.png'); ?>" alt="<?= htmlspecialchars($course['title']); ?>">
                    <div class="course-info">
                        <h3>Course Details</h3>
                        <p><strong>Difficulty:</strong> <?= htmlspecialchars($course['difficulty']); ?></p>
                        <p><strong>Duration:</strong> <?= htmlspecialchars($course['duration']); ?></p>
                        <p><strong>Price:</strong> $<?= number_format($course['price'], 2); ?></p>
                        <p><strong>Rating:</strong> <?= $course['rating']; ?>/5</p>
                    </div>
                </div>
                <div class="payment-options">
                    <h3>Payment Options</h3>
                    <?php if ($course['price'] <= 0): ?>
                        <div class="free-course">
                            <h4>Free Course</h4>
                            <p>This course is completely free. You'll get instant access after enrollment.</p>
                            <form method="POST" action="../enrollCourse.php">
                                <input type="hidden" name="course_id" value="<?= $course['id']; ?>">
                                <button type="submit" class="enroll-btn">Enroll Now - Free</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="paid-course">
                            <div class="payment-plans">
                                <div class="plan-card active">
                                    <h4>Full Payment</h4>
                                    <p>Pay once, access forever</p>
                                    <p class="price">$<?= number_format($course['price'], 2); ?></p>
                                    <form method="POST" action="processPayment.php">
                                        <input type="hidden" name="course_id" value="<?= $course['id']; ?>">
                                        <input type="hidden" name="payment_type" value="full">
                                        <button type="submit" class="enroll-btn">Enroll Now</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="../../../assets/js/student.js"></script>
</body>
</html>