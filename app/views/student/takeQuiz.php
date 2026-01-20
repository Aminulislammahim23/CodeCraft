<?php
session_start();
require_once '../../models/courseModel.php';
require_once '../../models/userModel.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}
$courseId = $_GET['course_id'] ?? 0;
$userId = $_SESSION['user_id'];
if ($courseId <= 0) {
    header("Location: dashboard.php?error=invalid_course");
    exit();
}
if (!isUserEnrolled($userId, $courseId)) {
    header("Location: dashboard.php?error=not_enrolled");
    exit();
}
$course = getCourseById($courseId);
$quizzes = getQuizzesByCourseId($courseId);
if (!$course) {
    header("Location: dashboard.php?error=course_not_found");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz: <?= htmlspecialchars($course['title']); ?> | CodeCraft</title>
    <link rel="stylesheet" href="../../assets/css/student.css">
    <style>
        .quiz-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: 20px auto;
        }
        .quiz-header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .question-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid #4f46e5;
        }
        .question-text {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1e293b;
        }
        .options-list {
            list-style: none;
            padding: 0;
        }
        .option-item {
            margin-bottom: 12px;
        }
        .option-item label {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        .option-item label:hover {
            border-color: #4f46e5;
            background: #eff6ff;
        }
        .option-item input[type="radio"] {
            margin-right: 15px;
            width: 18px;
            height: 18px;
        }
        .submit-quiz-btn {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            display: block;
            margin: 40px auto 0;
            transition: 0.3s;
        }
        .submit-quiz-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
        }
        .no-quiz {
            text-align: center;
            padding: 60px;
            color: #64748b;
        }
        .timer-container {
            position: sticky;
            top: 20px;
            z-index: 100;
            background: #fff;
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            margin: 0 auto 30px;
            border: 2px solid #4f46e5;
        }
        .timer-icon {
            color: #4f46e5;
            font-size: 1.2rem;
        }
        .timer-text {
            font-weight: bold;
            font-size: 1.1rem;
            color: #1e293b;
        }
        #timer {
            color: #e11d48;
            font-size: 1.3rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <img src="../../assets/img/logo.png" class="brand-logo">
            <h2 class="logo">Welcome to CodeCraft</h2>
            <ul class="menu">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="dashboard.php#courses">📚 Courses</a></li>
                <li class="active"><a href="dashboard.php#enrollments">📦 Enrollments</a></li>
                <li><a href="../../controllers/studentController/profile.php">👤 Profile</a></li>
                <li><a href="../../controllers/logout.php">🚪 Logout</a></li>
            </ul>
        </aside>
        <main class="main">
            <header class="topbar">
                <h1>Course Quiz</h1>
                <div class="student-info">
                    <span><?= htmlspecialchars($_SESSION['full_name'] ?? 'student'); ?></span>
                </div>
            </header>
            <div class="quiz-container">
                <div class="quiz-header">
                    <h2><?= htmlspecialchars($course['title']); ?> - Final Quiz</h2>
                    <p>Test your knowledge and earn your certificate!</p>
                </div>
                <?php if (!empty($quizzes)): ?>
                    <div class="timer-container">
                        <i class="fas fa-clock timer-icon"></i>
                        <span class="timer-text">Time Remaining: <span id="timer">01:00</span></span>
                    </div>
                    <form id="quizForm" action="submitQuiz.php" method="POST">
                        <input type="hidden" name="course_id" value="<?= $courseId; ?>">
                        <?php foreach ($quizzes as $index => $quiz): ?>
                            <div class="question-card">
                                <p class="question-text"><?= ($index + 1) . ". " . htmlspecialchars($quiz['question']); ?></p>
                                <div class="options-list">
                                    <div class="option-item">
                                        <label>
                                            <input type="radio" name="question_<?= $quiz['id']; ?>" value="A">
                                            <?= htmlspecialchars($quiz['option_a']); ?>
                                        </label>
                                    </div>
                                    <div class="option-item">
                                        <label>
                                            <input type="radio" name="question_<?= $quiz['id']; ?>" value="B">
                                            <?= htmlspecialchars($quiz['option_b']); ?>
                                        </label>
                                    </div>
                                    <div class="option-item">
                                        <label>
                                            <input type="radio" name="question_<?= $quiz['id']; ?>" value="C">
                                            <?= htmlspecialchars($quiz['option_c']); ?>
                                        </label>
                                    </div>
                                    <div class="option-item">
                                        <label>
                                            <input type="radio" name="question_<?= $quiz['id']; ?>" value="D">
                                            <?= htmlspecialchars($quiz['option_d']); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <button type="submit" class="submit-quiz-btn">Submit Quiz</button>
                    </form>
                <?php else: ?>
                    <div class="no-quiz">
                        <i class="fas fa-exclamation-circle" style="font-size: 3rem; margin-bottom: 20px; color: #cbd5e1;"></i>
                        <h3>No quiz available yet</h3>
                        <p>The instructor hasn't added a quiz for this course. Please check back later!</p>
                        <a href="courseView.php?course_id=<?= $courseId; ?>" class="back-to-dashboard" style="display: inline-block; margin-top: 20px;">← Back to Course</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <script>
        let timeLeft = 60;
        const timerDisplay = document.getElementById('timer');
        const quizForm = document.getElementById('quizForm');
        if (quizForm) {
            const countdown = setInterval(function() {
                timeLeft--;
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                timerDisplay.textContent = 
                    (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                    (seconds < 10 ? "0" + seconds : seconds);
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    quizForm.submit();
                }
            }, 1000);
        }
    </script>
</body>
</html>