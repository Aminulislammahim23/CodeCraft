<?php
session_start();

//
//
//
//
//
//
$courseNames = [
    'html' => 'HTML for Beginners',
    'css' => 'CSS for Beginners',
    'js' => 'JavaScript for Beginners',
    'cplusplus' => 'C++ for Beginners',
    'sql' => 'SQL for Beginners',
    'csharp' => 'C# for Beginners'
];

$enrolledCourse = $_SESSION['enrolled_course'] ?? null;
$courseName = $enrolledCourse && isset($courseNames[$enrolledCourse]) ? $courseNames[$enrolledCourse] : null;
$progress = 0; 


$courseData = [];
if ($enrolledCourse && $courseName) {
    $courseData[] = [
        'id' => $enrolledCourse,
        'title' => $courseName,
        'progress' => $progress,
        'resumeLink' => '#' 
    ];
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
