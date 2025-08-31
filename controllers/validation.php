<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

  
    if (isset($_SESSION['signup_credentials']) && 
        $username === $_SESSION['signup_credentials']['username'] && 
        $password === $_SESSION['signup_credentials']['password']) {
    
        $_SESSION['username'] = $username;
        setcookie('status', 'true', time() + (86400 * 30), "/"); 
        unset($_SESSION['signup_credentials']); 
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
