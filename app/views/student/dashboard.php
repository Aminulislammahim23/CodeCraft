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

$successMsg = "";
$errorMsg = "";
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'enrolled') {
        $successMsg = "✅ Successfully enrolled in the course!";
    }
}
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid_course') {
        $errorMsg = "❌ Invalid course selected!";
    } elseif ($_GET['error'] === 'course_not_found') {
        $errorMsg = "❌ Course not found!";
    } elseif ($_GET['error'] === 'enrollment_failed') {
        $errorMsg = "❌ Enrollment failed. You may already be enrolled or there was an error.";
    } elseif ($_GET['error'] === 'invalid_request') {
        $errorMsg = "❌ Invalid request!";
    } elseif ($_GET['error'] === 'not_enrolled') {
        $errorMsg = "❌ You must be enrolled in this course to view its content!";
    }
}

$totalCourses = countCourses();
$allCourses = getAllCourses();
$enrolledCourses = getEnrolledCourses($_SESSION['user_id'] ?? 0);
$totalEnrollments = countEnrollments($_SESSION['user_id'] ?? 0);
$completedCoursesCount = getCompletedCourses($_SESSION['user_id'] ?? 0);
$completedCoursesData = getUserCompletedCoursesData($_SESSION['user_id'] ?? 0);
$totalCertificates = countCertificates($_SESSION['user_id'] ?? 0);
$userCertificates = getUserCertificates($_SESSION['user_id'] ?? 0);
$overallProgress = getOverallProgress($_SESSION['user_id'] ?? 0);
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
        
        <aside class="sidebar">
            <img src="../../assets/img/logo.png" class="brand-logo">
            <h2 class="logo">Welcome to CodeCraft</h2>
            <ul class="menu">
                <li class="active">
                    <a href="#dashboard" onclick="showSection('dashboard', event)">📊 Dashboard</a>
                </li>
                <li>
                    <a href="#courses" onclick="showSection('courses', event)">📚 Courses</a>
                </li>
                <li>
                    <a href="#enrollments" onclick="showSection('enrollments', event)">📦 Enrollments</a>
                </li>
                <li>
                    <a href="#completed" onclick="showSection('completed', event)">✅ Completed</a>
                </li>
                <li>
                    <a href="#certificates" onclick="showSection('certificates', event)">🎓 Certificates</a>
                </li>
                <li>
                    <a href="../../controllers/studentController/profile.php">👤 Profile</a>
                </li>
                <li>
                    <a href="../../controllers/studentController/invoices.php">📄 Invoices</a>
                </li>
                <li>
                    <a href="../../controllers/logout.php">🚪 Logout</a>
                </li>
            </ul>
        </aside>
        <main class="main">
            
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
            
            <?php if ($successMsg): ?>
                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #c3e6cb;">
                    <?= $successMsg; ?>
                </div>
            <?php endif; ?>
            <?php if ($errorMsg): ?>
                <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #f5c6cb;">
                    <?= $errorMsg; ?>
                </div>
            <?php endif; ?>
            <section id="dashboard" class="section active">
    
    <div class="welcome-card">
        <div>
            <h2>Welcome back, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Student'); ?> 👋</h2>
            <p>Continue your learning journey with CodeCraft.</p>
        </div>
        <img src="../../assets/img/dashboard-illustration.png" alt="Learning">
    </div>
    
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
            <h3>Completed Courses</h3>
            <p><?= $completedCoursesCount ?></p>
            <?php if ($completedCoursesCount > 0): ?>
                <button onclick="showSection('completed')" style="background: white; color: #f59e0b; border: none; padding: 5px 15px; border-radius: 20px; cursor: pointer; font-weight: 600; margin-top: 10px;">View</button>
            <?php endif; ?>
        </div>
        <div class="stat-card orange">
            <h3>Certificates</h3>
            <p><?= $totalCertificates ?></p>
            <?php if ($totalCertificates > 0): ?>
                <button onclick="showSection('certificates')" style="background: white; color: #f59e0b; border: none; padding: 5px 15px; border-radius: 20px; cursor: pointer; font-weight: 600; margin-top: 10px;">View</button>
            <?php endif; ?>
        </div>
        <div class="stat-card purple">
            <h3>Progress</h3>
            <p><?= round($overallProgress) ?>%</p>
        </div>
    </div>
    
    <div class="progress-card">
        <h3>Your Learning Progress</h3>
        <div class="progress-bar">
            <div class="progress-fill" style="width: <?= $overallProgress ?>%"></div>
        </div>
        <span><?= round($overallProgress) ?>% completed</span>
    </div>
    
    <div class="multi-progress-card" style="margin-top: 30px; background: #fff; padding: 25px; border-radius: 14px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 20px;">Individual Course Progress</h3>
        <?php if (!empty($enrolledCourses)): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <?php foreach ($enrolledCourses as $course): ?>
                    <div class="course-mini-progress" style="padding: 15px; border: 1px solid #f0f0f0; border-radius: 10px;">
                        <h4 style="font-size: 14px; margin-bottom: 10px; color: #1e293b;"><?= htmlspecialchars($course['title']) ?></h4>
                        <div class="progress-bar" style="height: 10px; background: #e9ecef; border-radius: 20px; overflow: hidden; margin-bottom: 8px;">
                            <div class="progress-fill" style="width: <?= $course['completed_percentage'] ?? 0 ?>%; height: 100%; background: linear-gradient(90deg, #4f46e5, #22c55e); transition: width 0.6s ease;"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 12px; color: #64748b;"><?= $course['completed_percentage'] ?? 0 ?>% completed</span>
                            <a href="courseView.php?course_id=<?= $course['id'] ?>" style="font-size: 12px; color: #4f46e5; text-decoration: none; font-weight: 600;">Continue →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: #64748b; font-style: italic;">No active enrollments to show progress.</p>
        <?php endif; ?>
    </div>
</section>
<section id="courses" class="section">
        <h1>Courses</h1>
        <div class="course-grid">
            <?php if (!empty($allCourses)): ?>
                <?php foreach ($allCourses as $course): ?>
                <div class="course-card">
                    <img src="../../assets/uploads/system/courses/img/<?= htmlspecialchars($course['course_image'] ?? 'default.png'); ?>" alt="<?= htmlspecialchars($course['title']); ?>">
                    <h3><?= htmlspecialchars($course['title']); ?></h3>
                    <p><?= htmlspecialchars($course['description']); ?></p>
                    <a href="../../controllers/studentController/enrollment.php?course_id=<?= $course['id']; ?>" class="enroll-btn">Enroll Now</a>
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
                    <img src="../../assets/uploads/system/courses/img/<?= htmlspecialchars($course['course_image'] ?? 'default.png'); ?>" alt="<?= htmlspecialchars($course['title']); ?>">
                    <h3><?= htmlspecialchars($course['title']); ?></h3>
                    <p><?= htmlspecialchars($course['description']); ?></p>
                    
                    <div class="course-progress" style="margin: 15px; margin-top: 0;">
                        <div class="progress-bar" style="height: 8px; background: #eee; border-radius: 10px; overflow: hidden; margin-bottom: 5px;">
                            <div class="progress-fill" style="width: <?= $course['completed_percentage'] ?? 0 ?>%; height: 100%; background: linear-gradient(90deg, #4f46e5, #22c55e);"></div>
                        </div>
                        <span style="font-size: 12px; color: #666;"><?= $course['completed_percentage'] ?? 0 ?>% completed</span>
                    </div>
                    <a href="courseView.php?course_id=<?= $course['id']; ?>" class="proceed-btn">Proceed</a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-courses">You haven't enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>
    </section>
    <section id="completed" class="section">
        <h1>Completed Courses</h1>
        <div class="enrollment-grid">
            <?php if (!empty($completedCoursesData)): ?>
                <?php foreach ($completedCoursesData as $course): ?>
                <div class="enrollment-card">
                    <img src="../../assets/uploads/system/courses/img/<?= htmlspecialchars($course['course_image'] ?? 'default.png'); ?>" alt="<?= htmlspecialchars($course['title']); ?>">
                    <h3><?= htmlspecialchars($course['title']); ?></h3>
                    <div class="course-progress" style="margin: 15px; margin-top: 0;">
                        <div class="progress-bar" style="height: 8px; background: #eee; border-radius: 10px; overflow: hidden; margin-bottom: 5px;">
                            <div class="progress-fill" style="width: 100%; height: 100%; background: #22c55e;"></div>
                        </div>
                        <span style="font-size: 12px; color: #166534; font-weight: 600;">Completed 100%</span>
                    </div>
                    <a href="courseView.php?course_id=<?= $course['id']; ?>" class="proceed-btn" style="background: #22c55e;">Review Course</a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 50px; background: #f8fafc; border-radius: 12px; grid-column: 1/-1;">
                    <p style="color: #64748b;">You haven't completed any courses yet. Keep learning!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <section id="certificates" class="section">
        <h1>My Certificates</h1>
        <div class="certificates-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <?php if (!empty($userCertificates)): ?>
                <?php foreach ($userCertificates as $cert): ?>
                <div class="certificate-card" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 2px solid #e2e8f0; text-align: center;">
                    <div style="font-size: 3rem; color: #f59e0b; margin-bottom: 15px;">🏆</div>
                    <h3 style="margin-bottom: 10px;"><?= htmlspecialchars($cert['course_title']); ?></h3>
                    <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">Issued on: <?= date('M d, Y', strtotime($cert['issue_date'])); ?></p>
                    <a href="getCertificate.php?course_id=<?= $cert['course_id']; ?>" target="_blank" class="view-cert-btn" style="background: #4f46e5; color: white; text-decoration: none; padding: 10px 25px; border-radius: 8px; display: inline-block; font-weight: 600;">View Certificate</a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: #f8fafc; border-radius: 12px;">
                    <i class="fas fa-award" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 15px;"></i>
                    <p style="color: #64748b;">You haven't earned any certificates yet. Complete a course with 100% progress to get one!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <script src="../../assets/js/student.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            if (alerts.length > 0) {
                setTimeout(function() {
                    alerts.forEach(function(alert) {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            alert.style.display = 'none';
                        }, 500);
                    });
                }, 4000);
            }
        });
    </script>
</body>
</html>