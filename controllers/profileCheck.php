<?php
    session_start();
    require_once('../model/userModel.php');

    $username = trim($_REQUEST['username']);
    $password = trim($_REQUEST['password']);
    $email = trim($_REQUEST['email']);
    $phone = trim($_REQUEST['phone']);
    $dob = trim($_REQUEST['dob']);
    $role = trim($_REQUEST['role']);
    $avatar = trim($_REQUEST['avatar']);


    if($username == "" || $password == "" || $email == "" || $phone == "" || $dob == "" || $role == "" || $avatar == ""){
        header('location: ../view/quiz.php?error=null');

    }else{
        $user = ['username'=> $username, 'password'=>$password, 'email'=>$email, 'phone'=>$phone, 'dob'=>$dob, 'role'=>$role, 'avatar'=>$avatar];
        $status = addUser($user);
        if($status){
            header('location: ../view/quiz.php');
        }else{
            header('location: ../view/profile.php?error=dberror');
        }
    }
?>