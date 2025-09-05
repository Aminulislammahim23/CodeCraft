<?php
session_start();
require_once '../model/mydb.php';

if (!isset($_SESSION['status']) && !isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
    exit();
}


ini_set('display_errors', 1);
error_reporting(E_ALL);

$enrolledCourses = getEnrolledCourses($_SESSION['username']);
$courseData = [];

if ($enrolledCourses) {
    foreach ($enrolledCourses as $course) {
        $highestScore = getHighestQuizScore($_SESSION['username'], $course['code']);
        $progress = min(($highestScore / 5) * 100, 100); // 5 questions, cap at 100%
        $courseData[] = [
            'id' => $course['code'],
            'title' => $course['name'],
            'progress' => $progress,
            'resumeLink' => "quiz.php?course=" . urlencode($course['code'])
        ];
    }
} else {
   
    error_log("No enrolled courses found for user: " . $_SESSION['username']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeCraft - My Progress</title>
    <link rel="stylesheet" href="../assets/css/progress.css">
</head>
<body>
    <header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="../view/enrollment.php">Enroll</a></li>
                <li><a href="../view/progress.php">Progress</a></li>
                <li><a href="../view/forum.php">Forums</a></li>
                <li><a href="../view/faq.html">FAQ</a></li>
            </ul>
        </nav>  
    </header>

    <main>
        <div class="progress-dashboard">
            <h1>My Learning Progress</h1>
            <div id="course-progress-list" class="course-list">
                
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 CodeCraft. All rights reserved.</p>
    </footer>

    <script id="course-data" type="application/json">
        <?php echo json_encode($courseData); ?>
    </script>
    <script src="../assets/js/progress.js"></script>
</body>
</html>