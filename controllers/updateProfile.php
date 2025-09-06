<?php
session_start();
require_once('../model/userModel.php');

if(!isset($_COOKIE['status'])){
    header('location: ../view/login.php?error=badrequest');
    exit();
}

if(isset($_POST['update'])){
    // প্রথমে ডাটাবেজ থেকে পুরনো user ডাটা নিয়ে আসি
    $oldUser = getUserById($_POST['id']);
    if(!$oldUser){
        header("Location: ../view/profile.php?error=user_not_found");
        exit();
    }

    // Password logic
    if (!empty($_POST['password'])) {
        // নতুন পাসওয়ার্ড এন্ট্রি করা হলে hash করা হবে
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        // ফাঁকা থাকলে পুরনো password রাখা হবে
        $password = $oldUser['password'];
    }

    // Avatar upload
    $avatar = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES['avatar']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
            $avatar = "uploads/" . $fileName; // relative path save
        }
    }

    $user = [
        'id'       => $_POST['id'],
        'username' => $_POST['username'],
        'email'    => $_POST['email'],
        'password' => $password,
        'phone'    => $_POST['phone'],
        'dob'      => $_POST['dob'],
        'role'     => $_POST['role'] ?? $oldUser['role'], // যদি ফর্মে না থাকে তাহলে পুরোনো role রাখবো
        'avatar'   => $avatar
    ];

    $status = updateUser($user);

    if($status){
        $_SESSION['username'] = $user['username']; // session আপডেট
        header("Location: ../view/profile.php?success=1");
    }else{
        header("Location: ../view/profile.php?error=update_failed");
    }
}
?>
