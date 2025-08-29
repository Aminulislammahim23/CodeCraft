<?php
    session_start();
    if(!isset($_COOKIE['status'])){
        header('location: login.php?error=badrequest');
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
  <header>
    <h2>🎓 Student Dashboard</h2>
    <nav>
      <ul>
        <li><a href="student_dashboard.html" class="active">Dashboard</a></li>
        <li><a href="../view/quiz.php">Take Quiz</a></li>
        <li><a href="../view/progress.html">Progress</a></li>
        <li><a href="../controllers/logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main class="dashboard">
    <h3>Welcome Back!</h3>

    <div class="cards">
      <div class="card">
        <h4>📊 Last Quiz Score</h4>
        <p id="lastScore">Loading...</p>
      </div>

      <div class="card">
        <h4>✅ Completed Quizzes</h4>
        <ul id="quizHistory"></ul>
      </div>

      <div class="card">
        <h4>🚀 Quick Actions</h4>
        <button onclick="window.location.href='quiz.html'">Start a New Quiz</button>
        <button onclick="window.location.href='progress.html'">View Progress</button>
      </div>
    </div>
  </main>

  <footer>
    <p>© 2025 CodeCraft Student Dashboard</p>
  </footer>

  <script src="../assets/js/dashboard.js"></script>
</body>
</html>
