<?php
    session_start();
    if(!isset($_SESSION['status']) && !isset($_COOKIE['status'])){
    header('location: login.php?error=badrequest');
    exit();
}
?>

<html>
<head>
  <title>CodeCraft -- HTML Quiz</title>
  <link rel="stylesheet" href="../assets/css/quizs.css">
</head>
<body class="bg">
<header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="../view/enrollment.php">Enroll</a></li>
                <li><a href="../view/progress.php">Progress</a></li>
                <li><a href="../view/forum.html">Forums</a></li>
                <li><a href="../view/faq.html">FAQ</a></li>
            </ul>
        </nav>  
    </header>

<div class="quiz-container">
    <div class="quiz-header">
      <h2>📝 Web Basics Quiz</h2>
    </div>

    <!-- Start Button -->
    <div id="startScreen">
      <button onclick="startQuiz()">Start Quiz</button>
    </div>

    <div class="timer" id="timer">Time Left: 30s</div>
    <div id="quiz" style="display:none;"></div>

    <div style="text-align:center;">
      <button onclick="submitQuiz()" id="submitBtn" style="display:none;">Submit Quiz</button>
      <button onclick="retakeQuiz()" id="restartBtn">Retake Quiz</button>
    </div>
    <div class="score-box" id="scoreBox"></div>
  </div>
<footer>
    <p>&copy; 2025 CodeCraft. All Rights Reserved.</p>
  </footer>
  <script src="../assets/js/quiz.js"></script>
</body>
</html>