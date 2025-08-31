<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
$username = trim($_REQUEST['username'] ?? '');
$password = trim($_REQUEST['password'] ?? '');

if($username === "" || $password === ""){
    echo "Please type username/password first!";
    exit();
}

$validUser = "admin";
    $validPass = "12345";

    if ($username === $validUser && $password === $validPass) {
        $_SESSION['username'] = $username;
        setcookie("status", "true", time() + 3600, "/"); // valid for 1 hour
        header("Location: ../views/home.php");
    } else {
        header("Location: ../views/login.php?error=invalid_user");
    }
} else {
    header("Location: ../views/login.php?error=badrequest");
}
?>
