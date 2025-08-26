<?php
    session_start();
    $username = trim($_REQUEST['username']);
    $password = trim($_REQUEST['password']);

    if($username == "" || $password == ""){
        echo "please type username/password first!";
    }else{
        if($username == $password){
            $_SESSION['status'] = true; 
            $_SESSION['username'] = $username; 
            header('location: instructorDashBoard.php');
        }else{
            header('location: login.php?error=invalid_user');
        }
    }

?>