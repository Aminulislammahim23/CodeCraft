<?php
session_start();

$username = trim($_REQUEST['username'] ?? '');
$password = trim($_REQUEST['password'] ?? '');

if($username === "" || $password === ""){
    echo "Please type username/password first!";
    exit();
}

if($username === $password){
    $_SESSION['status'] = true;
    $_SESSION['username'] = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    header('Location: view/admin.php');
    exit();
} else {
    header('Location: view/login.php?error=invalid_user');
    exit();
}
?>
