<?php
session_start();
require_once('../models/userModel.php');
$email = trim($_REQUEST['email']);
$password = trim($_REQUEST['password']);
if ($email == "" || $password == "") {
    echo "Please type username/password first!";
} else {
    $user = ['email' => $email, 'password' => $password];
    $dbUser = login($user);  
    if ($dbUser) {
        setcookie('status', true, time() + 3000, '/');
        $_SESSION['user_id']   = $dbUser['id'];
        $_SESSION['full_name']  = $dbUser['full_name'];
        $_SESSION['email']     = $dbUser['email'];
        $_SESSION['role']      = $dbUser['role'];
        $_SESSION['avatar']    = $dbUser['avatar'];
        if ($dbUser['role'] === 'admin') {
            header('location: ../views/admin/dashboard.php');
        } elseif ($dbUser['role'] === 'instructor') {
            header('location: ../views/instructor/dashboard.php');
        } else {
            header('location: ../views/student/dashboard.php');
        }
        exit();
    } else {
        header('location: ../views/auth/login.php?error=invalid_user');
        exit();
    }
}
?>