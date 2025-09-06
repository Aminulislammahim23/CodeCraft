<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quiz</title>
<link rel="stylesheet" href="../assets/css/quiz.css">
</head>
<body>

<div class="app" id="app">
  <header>
    <h1>Quiz</h1>
    <div>⏱ <span id="time">--:--</span></div>
  </header>

  <div class="content">
    <div class="left">
      <div id="question-container"></div>
      <div class="nav">
        <button id="prevBtn" class="ghost">◀ Previous</button>
        <button id="nextBtn" class="ghost">Next ▶</button>
        <button id="submitBtn" class="primary">Submit</button>
      </div>
    </div>

    <div class="right">
      <div id="summary"></div>
      <div class="review" id="review"></div>
      <button id="retakeBtn" class="primary" style="margin-top:12px;">Retake</button>
    </div>
  </div>
</div>

<script>
const QUESTIONS = <?php echo json_encode($questions); ?>;
</script>

<script src="../assets/js/quiz.js"></script>
</body>
</html>