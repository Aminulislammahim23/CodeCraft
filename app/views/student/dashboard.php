<?php
session_start();
require_once '../../models/courseModel.php';
require_once '../../models/userModel.php';

/* ---------- HELPER FUNCTIONS ---------- */
function getAvatarPath($avatarFilename)
{
    $avatar = $avatarFilename ?? 'default.png';
    return "../../assets/uploads/users/avatars/" . htmlspecialchars($avatar);
}

/* ---------- SECURITY CHECK ---------- */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}


/* ---------- DASHBOARD STATS ---------- */
$totalCourses = countCourses();
$allCourses = getAllCourses();
$enrolledCourses = getEnrolledCourses($_SESSION['user_id'] ?? 0);
// $totalEnrollments = countEnrollments();
// $totalStudents = countStudents();
// $totalRevenue = countRevenue();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/student.css">
</head>
<body>
    <div class="admin-container">

        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar">
            <img src="../../assets/img/logo.png" class="brand-logo">
            <h2 class="logo">Welcome to CodeCraft</h2>
    
            <ul class="menu">
                <li class="active">
                    <a href="#" onclick="showSection('dashboard')">📊 Dashboard</a>
                </li>
                <li>
                    <a href="#" onclick="showSection('courses')">📚 Courses</a>
                </li>
                <li>
                    <a href="#" onclick="showSection('enrollments')">📦 Enrollments</a>
                </li>
                <li>
                    <a href="#" onclick="showSection('settings')">⚙️ Settings</a>
                </li>
                <li>
                    <a href="process/invoices.php">📄 Invoices</a>
                </li>
                <li>
                    <a href="../../controllers/logout.php" onclick="showSection('Logout')">🚪 Logout</a>
                </li>
            </ul>
        </aside>

        <main class="main">

            <!-- TOPBAR -->
            <header class="topbar">
                <h1>Dashboard</h1>
                <div class="student-info">
                    <img src="<?= getAvatarPath($_SESSION['avatar'] ?? null); ?>" 
                         alt="<?= htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?> Avatar" 
                         class="user-avatar"
                         onerror="this.onerror=null; this.src='<?= getAvatarPath('default.png'); ?>';">
                    <span><?= htmlspecialchars($_SESSION['full_name'] ?? 'student'); ?></span>
                </div>
            </header>

            <section id="dashboard" class="section active">

    <!-- Welcome Banner -->
    <div class="welcome-card">
        <div>
            <h2>Welcome back, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Student'); ?> 👋</h2>
            <p>Continue your learning journey with CodeCraft.</p>
        </div>
        <img src="../../assets/img/dashboard-illustration.png" alt="Learning">
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">

        <div class="stat-card blue">
            <h3>Total Courses</h3>
            <p><?= $totalCourses ?></p>
        </div>

        <div class="stat-card green">
            <h3>Enrolled Courses</h3>
            <p><?= count($enrolledCourses) ?></p>
        </div>

        <div class="stat-card orange">
            <h3>Certificates</h3>
            <p>0</p>
        </div>

        <div class="stat-card purple">
            <h3>Progress</h3>
            <p>0%</p>
        </div>

    </div>

    <!-- Progress Section -->
    <div class="progress-card">
        <h3>Your Learning Progress</h3>
        <div class="progress-bar">
            <div class="progress-fill" style="width: 0%"></div>
        </div>
        <span>0% completed</span>
    </div>

</section>
<section id="courses" class="section">
        <h1>Courses</h1>
        <div class="course-grid">
            <?php if (!empty($allCourses)): ?>
                <?php foreach ($allCourses as $course): ?>
                <div class="course-card">
                    <img src="../../assets/uploads/courses/<?= htmlspecialchars($course['course_image'] ?? 'default.png'); ?>" alt="<?= htmlspecialchars($course['title']); ?>">
                    <h3><?= htmlspecialchars($course['title']); ?></h3>
                    <p><?= htmlspecialchars($course['description']); ?></p>
                    <a href="process/enrollment.php?course_id=<?= $course['id']; ?>" class="enroll-btn">Enroll Now</a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-courses">No courses available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>
    
    <section id="enrollments" class="section">
        <h1>Enrollments</h1>
        <div class="enrollment-grid">
            <?php if (!empty($enrolledCourses)): ?>
                <?php foreach ($enrolledCourses as $course): ?>
                <div class="enrollment-card">
                    <img src="../../assets/uploads/courses/<?= htmlspecialchars($course['course_image'] ?? 'default.png'); ?>" alt="<?= htmlspecialchars($course['title']); ?>">
                    <h3><?= htmlspecialchars($course['title']); ?></h3>
                    <p><?= htmlspecialchars($course['description']); ?></p>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-courses">You haven't enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>
    </section>
    <script src="../../assets/js/student.js"></script>
</body>
</html>


