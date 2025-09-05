<?php
session_start();
require_once '../model/mydb.php';  

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header("Location: ../view/login.php?error=invalid_user");
        exit();
    }

 
    $user = getUserByEmail($username);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        setcookie('status', 'true', time() + (86400 * 30), "/");
        header("Location: ../view/home.php");
        exit();
    } else {
        header("Location: ../view/login.php?error=invalid_user");
        exit();
    }
} else {
    header("Location: ../view/login.php?error=badrequest");
    exit();
}
?>