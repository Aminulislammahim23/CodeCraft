<?php
session_start();
require_once('../model/userMod.php');

$email = trim($_REQUEST['email']);
$password = trim($_REQUEST['password']);

if ($email == "" || $password == "") {
    echo "Please type username/password first!";
} else {
    $user = ['email' => $email, 'password' => $password];
    $dbUser = login($user);  

    if ($dbUser) {
        setcookie('status', true, time() + 3000, '/');

        // Save user info in session
        $_SESSION['user_id']   = $dbUser['id'];
        $_SESSION['username']  = $dbUser['username'];
        $_SESSION['role']      = $dbUser['role'];
        $_SESSION['avatar']    = $dbUser['avatar'];

        
        if ($dbUser['role'] === 'admin') {
            header('location: ../view/adminDash.php');
        } elseif ($dbUser['role'] === 'instructor') {
            header('location: ../view/instructorDash.php');
        } else {
            header('location: ../view/studentDash.php');
        }
        exit();

    } else {
        header('location: ../view/login.php?error=invalid_user');
        exit();
    }
}
?>
