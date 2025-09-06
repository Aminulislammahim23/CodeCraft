<?php
session_start();
if(!isset($_COOKIE['status'])){
    header('location: login.php?error=badrequest');
    exit();
}

require_once("quizModel.php");

$course = $_POST['course'] ?? null;
$answers = $_POST['answers'] ?? [];

$quizModel = new QuizModel();
$questions = $quizModel->getQuestionsByCourse($course);

$score = 0;
$total = count($questions);

foreach ($questions as $i => $q) {
    if (isset($answers[$i]) && strcasecmp(trim($answers[$i]), trim($q['answer'])) === 0) {
        $score++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz Result</title>
  <link rel="stylesheet" href="../assets/css/quiz.css">
</head>
<body>
  <header>
    <h2>📊 Quiz Results</h2>
    <nav>
      <a href="student_dashboard.php">🏠 Dashboard</a>
      <a href="logout.php">🚪 Logout</a>
    </nav>
  </header>

  <div class="container">
    <h3>Your Score: <?= $score ?> / <?= $total ?></h3>
    <p><?= round(($score / max($total, 1)) * 100, 2) ?>%</p>

    <h3>Review:</h3>
    <ul>
      <?php foreach ($questions as $i => $q): ?>
        <li>
          <strong>Q<?= $i+1 ?>: <?= htmlspecialchars($q['question']) ?></strong><br>
          ✅ Correct Answer: <?= htmlspecialchars($q['answer']) ?><br>
          <?php if (!empty($answers[$i])): ?>
            📝 Your Answer: <?= htmlspecialchars($answers[$i]) ?><br>
          <?php else: ?>
            📝 Your Answer: Not Answered<br>
          <?php endif; ?>
        </li>
        <br>
      <?php endforeach; ?>
    </ul>

    <button onclick="retakeQuiz()">Retake Quiz</button>
  </div>

  <script src="../assets/js/quiz.js"></script>
</body>
</html>
