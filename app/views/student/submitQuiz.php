<?php
session_start();
require_once '../../models/courseModel.php';
require_once '../../models/userModel.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}
$courseId = $_POST['course_id'] ?? 0;
$userId = $_SESSION['user_id'];
$quizzes = getQuizzesByCourseId($courseId);
$score = 0;
$totalQuestions = count($quizzes);
$quizReview = [];
foreach ($quizzes as $quiz) {
    $userAnswer = $_POST['question_' . $quiz['id']] ?? 'No Answer';
    $isCorrect = ($userAnswer === $quiz['correct_option']);
    if ($isCorrect) {
        $score++;
    }
    $quizReview[] = [
        'question' => $quiz['question'],
        'user_answer' => $userAnswer,
        'correct_option' => $quiz['correct_option'],
        'correct_text' => $quiz['option_' . strtolower($quiz['correct_option'])],
        'user_text' => ($userAnswer !== 'No Answer') ? $quiz['option_' . strtolower($userAnswer)] : 'No Answer',
        'is_correct' => $isCorrect
    ];
}
$percentage = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;
saveQuizResult($userId, $courseId, round($percentage));
$con = getConnection();
$roundedPercentage = round($percentage);
$sql = "UPDATE progress SET completed_percentage = $roundedPercentage WHERE user_id = $userId AND course_id = $courseId";
mysqli_query($con, $sql);
if ($roundedPercentage >= 100) {
    issueCertificate($userId, $courseId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results | CodeCraft</title>
    <link rel="stylesheet" href="../../assets/css/student.css">
    <style>
        .result-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
        }
        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 10px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0 auto 30px;
        }
        .score-value {
            font-size: 3rem;
            font-weight: bold;
            color: #4f46e5;
        }
        .status-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            display: inline-block;
        }
        .status-pass { background: #dcfce7; color: #166534; }
        .status-fail { background: #fee2e2; color: #b91c1c; }
        
        .review-section {
            margin-top: 50px;
            text-align: left;
            border-top: 2px solid #eee;
            padding-top: 30px;
        }
        .review-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 5px solid #cbd5e1;
        }
        .review-card.correct { border-left-color: #22c55e; }
        .review-card.incorrect { border-left-color: #ef4444; }
        .review-question {
            font-weight: 600;
            margin-bottom: 10px;
            color: #1e293b;
        }
        .answer-row {
            display: flex;
            gap: 20px;
            font-size: 0.95rem;
        }
        .ans-label { font-weight: 600; color: #64748b; }
        .correct-ans { color: #166534; font-weight: 600; }
        .user-ans.wrong { color: #b91c1c; font-weight: 600; text-decoration: line-through; }
        .user-ans.right { color: #166534; font-weight: 600; }
    </style>
</head>
<body>
    <div class="result-container">
        <h1>Quiz Results</h1>
        <div class="score-circle">
            <span class="score-value"><?= round($percentage); ?>%</span>
            <span>Score</span>
        </div>
        <?php if ($percentage >= 100): ?>
            <div class="status-badge status-pass">PERFECT SCORE</div>
            <h3>Excellent Work!</h3>
            <p>You've answered all questions correctly. Your course progress is now 100%!</p>
        <?php elseif ($percentage >= 80): ?>
            <div class="status-badge status-pass">PASSED</div>
            <h3>Congratulations!</h3>
            <p>You've successfully passed the quiz with <?= round($percentage) ?>%. Your progress has been updated.</p>
            <p style="margin-top: 15px; color: #64748b;">Want to reach 100%? You can <a href="takeQuiz.php?course_id=<?= $courseId; ?>" style="color: #4f46e5; font-weight: 600;">Retake the Quiz</a>.</p>
        <?php else: ?>
            <div class="status-badge status-fail">KEEP PRACTICING</div>
            <h3>Good Effort!</h3>
            <p>Your current score is <?= round($percentage) ?>%. You need 100% for full completion.</p>
            <p style="margin-top: 15px; font-weight: 600; color: #b91c1c;">We suggest you retake the quiz to achieve a perfect score!</p>
        <?php endif; ?>
        <div style="margin-top: 40px;">
            <a href="takeQuiz.php?course_id=<?= $courseId; ?>" class="btn-secondary" style="padding: 12px 25px; text-decoration: none; border-radius: 8px;">Try Again</a>
            <a href="dashboard.php" class="btn-primary" style="padding: 12px 25px; text-decoration: none; border-radius: 8px;">Go to Dashboard</a>
        </div>
        
        <div class="review-section">
            <h2>Review Your Answers</h2>
            <?php foreach ($quizReview as $index => $item): ?>
                <div class="review-card <?= $item['is_correct'] ? 'correct' : 'incorrect' ?>">
                    <p class="review-question"><?= ($index + 1) . ". " . htmlspecialchars($item['question']) ?></p>
                    <div class="answer-row">
                        <div>
                            <span class="ans-label">Your Answer:</span> 
                            <span class="user-ans <?= $item['is_correct'] ? 'right' : 'wrong' ?>">
                                <?= htmlspecialchars($item['user_text']) ?>
                            </span>
                        </div>
                        <?php if (!$item['is_correct']): ?>
                            <div>
                                <span class="ans-label">Correct Answer:</span> 
                                <span class="correct-ans"><?= htmlspecialchars($item['correct_text']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>