<?php
session_start();
if (!isset($_COOKIE['status'])) {
    header('location: ../view/login.php?error=badrequest');
}
?>

<?php
          require_once('../controller/auth.php');
          checkRole(['student']);   // only student can access
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/studentDash.css">
</head>
<body>
    <header>
        <h2>🎓 Student Dashboard</h2>
        <nav>
            <ul>
                <li><a href="student.php" class="active">Dashboard</a></li>
                <li><a href="profile.php">Manage Profile</a></li>
                <li><a href="../view/progress.html">Progress</a></li>
                <li><a href="../view/enrollment.php">Enrollment</a></li>
                <li><a href="../view/forum.html">Forum</a></li>
                <li><a href="../view/faq.html">FAQ</a></li>
                <li><a href="../controller/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard">
        <h3>Welcome Back, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Student'; ?>!</h3>

        <div class="cards">
            <div class="card">
                <h4>📊 Last Quiz Score</h4>
                <p id="lastScore">Loading...</p>
            </div>

            <div class="card">
                <h4>✅ Completed Quizzes</h4>
                <ul id="quizHistory">
                    <li>No quizzes completed</li>
                </ul>
            </div>

            <div class="card">
                <h4>🚀 Quick Actions</h4>
                <button onclick="window.location.href='../view/courseQZ.php'">Start a New Quiz</button>
                <button onclick="window.location.href='../view/progress.html'">View Progress</button>
                <button onclick="window.location.href='../view/enrollment.php'">Enroll in a Course</button>
                <button onclick="window.location.href='../view/forum.html'">Visit Forum</button>
                <button onclick="window.location.href='../view/faq.html'">View FAQs</button>
            </div>
        </div>
    </main>

    <footer>
        <p>© 2025 CodeCraft Student Dashboard</p>
    </footer>

    <script src="../assets/js/dashboard.js"></script>
</body>
</html>