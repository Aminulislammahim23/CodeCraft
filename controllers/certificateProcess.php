<?php
session_start();


$studentName = "";
$completionDate = "";
$errors = [];


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $studentName = trim($_POST['studentName'] ?? '');
    $completionDate = trim($_POST['completionDate'] ?? '');


    if (!$studentName) {
        $errors[] = "Please enter your name.";
    }

    if (!$completionDate) {
        $errors[] = "Please enter the completion date.";
    }

 
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['studentName'] = $studentName;
        $_SESSION['completionDate'] = $completionDate;
        header("Location: certificate.php");
        exit();
    }

  
    $_SESSION['success'] = "Validation passed! You can now generate the certificate.";
    $_SESSION['studentName'] = $studentName;
    $_SESSION['completionDate'] = $completionDate;


    $query = "?name=" . urlencode($studentName) . "&date=" . urlencode($completionDate);
    header("Location: certificate.php" . $query);
    exit();
} else {

    header("Location: certificate.php");
    exit();
}
?>
