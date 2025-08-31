<?php
session_start();
if(!isset($_SESSION['status']) && !isset($_COOKIE['status'])){
    echo "Unauthorized access!!!!";
    exit();
}

if($_SERVER['REQUEST_METHOD']=== 'POST') {
    // Process the quiz submission
    $answers = $_POST['answers'];

    $quizData = [
        ["answer" => 0],
        ["answer" => 1],
        ["answer" => 1],
    ];

    $score = 0;

    // Check answers
    foreach($quizData as $i => $q){
        if(isset($answers[$i]) && $answers[$i] == $q['answer']){
            $score++;
        }
    }
    echo "Your Score: $score / " . count($quizData);
}
?>