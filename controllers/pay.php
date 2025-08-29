<?php
    session_start();
    $username = trim($_REQUEST['username']);
    $password = trim($_REQUEST['password']);

    if($username == "" || $password == ""){
        echo "please type username/password first!";
    }else{
        if($username == $password){
            
            setcookie('status', true, time()+3000, '/');
            $_SESSION['username'] = $username; 
            header('location: home.php');
        }else{
            header('location: login.php?error=invalid_user');
        }
    }

?>