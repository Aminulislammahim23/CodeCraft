<?php
session_start();
require_once('../model/userMod.php');

if (!isset($_COOKIE['status']) || !isset($_SESSION['user_id'])) {
    header('location: ../view/login.php?error=badrequest');
    exit();
}

if (isset($_POST['update'])) {
    $oldUser = getUserById($_POST['id']);
    if (!$oldUser) {
        header("Location: ../view/profile.php?error=user_not_found");
        exit();
    }

    $password = trim($_POST['password']);
    if ($password === '') {
    $password = $oldUser['password']; // keep old password
    }

    // Default avatar = old one
    $avatar = $oldUser['avatar'];

    // Avatar upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../assets/uploads/imgP/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES['avatar']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
            // ✅ Save only relative path (without ../)
            $avatar = "assets/uploads/imgP/" . $fileName;
        }
    }

    

    // Build updated user data
    $user = [
        'id'       => $_POST['id'],
        'name'     => $_POST['name'],
        'username' => $_POST['username'],
        'email'    => $_POST['email'],
        'password' => $password,
        'dob'      => $_POST['dob'],
        'role'     => $oldUser['role'], // keep role unchanged
        'avatar'   => $avatar
    ];

    // Update DB
    $status = updateUser($user);

    if ($status) {
        $_SESSION['username'] = $user['username'];
        header("Location: ../view/profile.php?success=1");
    } else {
        header("Location: ../view/profile.php?error=update_failed");
    }
}
?>
