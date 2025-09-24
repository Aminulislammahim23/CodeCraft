<?php
session_start();
require_once('../model/userMod.php');
    $name     = trim($_REQUEST['name'] ?? '');
    $username = trim($_REQUEST['username'] ?? '');
    $email    = trim($_REQUEST['email'] ?? '');
    $password = trim($_REQUEST['password'] ?? '');
    $dob      = $_REQUEST['dob'] ?? '';
    $role     = $_REQUEST['role'] ?? 'student';

    if($username == "" || $password == ""){
        echo "please type username/password first!";
    }else{
         $user = [
        'name'     => $name,
        'username' => $username,
        'email'    => $email,
        'password' => $password,
        'dob'      => $dob,
        'role'     => $role
    ];
        $status = addUser($user);
        if($status){
           header('location: ../view/login.php?success=1');
        }else{
            header('location: ../view/signup.php?error=regerror');
        }
    }
?>
