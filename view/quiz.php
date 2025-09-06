<?php
session_start();
if(!isset($_SESSION['status']) && !isset($_COOKIE['status'])){
    header('location: login.php?error=badrequest');
    exit();
}

require_once('../model/quizModel.php');
$quizModel = new QuizModel();

// Get course from query string (default HTML)
$course = $_GET['course'] ?? 'HTML';
$questions = $quizModel->getQuestionsByCourse($course);
?>

<html>
<head>
  <title>CodeCraft -- <?php echo $course; ?> Quiz</title>
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
      <h2>📝 <?php echo $course; ?> Quiz</h2>
    </div>

    <?php if (empty($questions)): ?>
        <p>No questions found for this course.</p>
    <?php else: ?>
        <div id="startScreen">
          <button onclick="startQuiz()">Start Quiz</button>
        </div>

        <div class="timer" id="timer" style="display:none;">Time Left: 30s</div>
        <form id="quizForm" method="POST" action="../controller/quizcheck.php">
            <input type="hidden" name="course" value="<?php echo $course; ?>">
            <div id="quiz" style="display:none;"></div>
            <div style="text-align:center;">
              <button type="button" id="nextBtn" onclick="nextQuestion()" style="display:none;">Next</button>
              <button type="button" onclick="submitQuiz()" id="submitBtn" style="display:none;">Submit Quiz</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2025 CodeCraft. All Rights Reserved.</p>
</footer>

<script>
let quizData = [
<?php
foreach($questions as $i => $q){
    echo "{";
    echo "question: `" . addslashes($q['question']) . "`,"; 
    echo "q1: `" . addslashes($q['q1']) . "`,"; 
    echo "q2: `" . addslashes($q['q2']) . "`,"; 
    echo "q3: `" . addslashes($q['q3']) . "`,"; 
    echo "q4: `" . addslashes($q['q4']) . "`,"; 
    echo "answer: `" . addslashes($q['answer']) . "`";
    echo "}";
    if ($i < count($questions) - 1) echo ",";
}
?>
];
</script>

<script>
let timeLeft = 30;
let timer;
let currentIndex = 0;

function startQuiz() {
  document.getElementById("startScreen").style.display = "none";
  document.getElementById("quiz").style.display = "block";
  document.getElementById("timer").style.display = "block";
  loadQuestion();
  startTimer();
}

function loadQuestion() {
  const quizDiv = document.getElementById("quiz");
  const q = quizData[currentIndex];
  quizDiv.innerHTML = `
    <p><strong>${currentIndex + 1}. ${q.question}</strong></p>
    <label><input type="radio" name="answers[${currentIndex}]" value="${q.q1}"> ${q.q1}</label><br>
    <label><input type="radio" name="answers[${currentIndex}]" value="${q.q2}"> ${q.q2}</label><br>
    <label><input type="radio" name="answers[${currentIndex}]" value="${q.q3}"> ${q.q3}</label><br>
    <label><input type="radio" name="answers[${currentIndex}]" value="${q.q4}"> ${q.q4}</label><br>
    <hr>
  `;

  // Show Next or Submit
  if (currentIndex < quizData.length - 1) {
    document.getElementById("nextBtn").style.display = "inline-block";
    document.getElementById("submitBtn").style.display = "none";
  } else {
    document.getElementById("nextBtn").style.display = "none";
    document.getElementById("submitBtn").style.display = "inline-block";
  }
}

function nextQuestion() {
  if (!document.querySelector(`input[name="answers[${currentIndex}]"]:checked`)) {
    alert("⚠ Please select an answer before moving on!");
    return;
  }
  currentIndex++;
  loadQuestion();
}

function startTimer() {
  timeLeft = 60;
  document.getElementById("timer").textContent = `Time Left: ${timeLeft}s`;
  timer = setInterval(() => {
    timeLeft--;
    document.getElementById("timer").textContent = `Time Left: ${timeLeft}s`;
    if (timeLeft <= 0) {
      clearInterval(timer);
      submitQuiz();
    }
  }, 1000);
}

function submitQuiz() {
  clearInterval(timer);
  document.getElementById("quizForm").submit();
}
</script>

</body>
</html>
