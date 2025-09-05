<?php
session_start();
require_once '../model/mydb.php';

if (!isset($_SESSION['status']) && !isset($_COOKIE['status'])) {
    echo "Unauthorized access!!!!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseCode = $_POST['course'] ?? '';
    $answersJson = $_POST['answers'] ?? '{}';
    $answers = json_decode($answersJson, true);

    $questions = getQuestionsForCourse($courseCode);
    $score = 0;
    $total = count($questions);

    foreach ($questions as $i => $q) {
        if (isset($answers[$i]) && (int)$answers[$i] === $q['answer']) {
            $score++;
        } else {
            error_log("Mismatch at $i: Submitted " . ($answers[$i] ?? 'null') . ", Expected " . $q['answer']);
        }
    }

    recordQuizAttempt($_SESSION['username'], $courseCode, $score, $total);

    echo "Your Score: $score / $total";
}
?>