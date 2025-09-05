<?php
session_start();
require_once '../model/mydb.php';


if (!isset($_SESSION['status']) && !isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
    exit();
}


$courseCode = $_GET['course'] ?? '';
if (empty($courseCode)) {
    echo "No course specified! Please select a course from the progress page.";
    exit();
}


if (!isEnrolled($_SESSION['username'], $courseCode)) {
    echo "You are not enrolled in this course! Please enroll first.";
    exit();
}


$questions = getQuestionsForCourse($courseCode);

$processedQuestions = []; 
if (!empty($questions)) {
    $processedQuestions = array_map(function($q) {
      
        $q['options'] = json_decode($q['options'], true);
        return $q;
    }, $questions);
}

if (empty($processedQuestions)) {
    echo "No questions found for course: $courseCode. Please check the database.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CodeCraft -- Quiz for <?php echo htmlspecialchars($courseCode); ?></title>
    <link rel="stylesheet" href="../assets/css/quizs.css">
</head>
<body class="bg">
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

<div class="quiz-container">
    <div class="quiz-header">
        <h2>📝 Quiz for <?php echo htmlspecialchars($courseCode); ?></h2>
    </div>

    <div id="startScreen">
        <button onclick="startQuiz()">Start Quiz</button>
    </div>

    <div class="timer" id="timer">Time Left: 30s</div>
    <form id="quizForm" action="../controllers/quizCheck.php" method="post">
        <div id="quiz" style="display:none;"></div>
        <input type="hidden" name="course" value="<?php echo htmlspecialchars($courseCode); ?>">
        <div style="text-align:center;">
            <button type="button" onclick="submitQuiz()" id="submitBtn" style="display:none;">Submit Quiz</button>
            <button type="button" onclick="restartQuiz()" id="restartBtn">Retake Quiz</button>
        </div>
    </form>
    <div class="score-box" id="scoreBox"></div>
</div>
<footer>
    <p>&copy; 2025 CodeCraft. All Rights Reserved.</p>
</footer>
<script>
  
    const quizData = <?php echo json_encode($processedQuestions); ?>;
    
    console.log("Quiz Data:", quizData); 
    if (!quizData || quizData.length === 0) {
        alert("No questions available for this course. Contact support.");
    }
</script>
<script src="../assets/js/quiz.js"></script>
</body>
</html>