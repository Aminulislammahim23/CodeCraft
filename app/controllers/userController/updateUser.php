<?php
session_start();
require_once('../../models/userModel.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = [
        'id' => $_POST['user_id'],
        'full_name' => $_POST['full_name'],
        'email' => $_POST['email'],
        'password' => $_POST['password'], 
        'role' => $_POST['role'],
        'avatar' => $_FILES['avatar']
    ];

    $result = updateUser($user);

    if ($result === "NOT_FOUND") {
        header("Location: ../../views/admin/dashboard.php?error=user_not_found");
        exit;
    } elseif ($result) {
        // If current user is updating their own profile, update session data
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id']) {
            $updatedUser = getUserById($user['id']);
            if ($updatedUser) {
                $_SESSION['full_name'] = $updatedUser['full_name'];
                $_SESSION['email'] = $updatedUser['email'];
                $_SESSION['role'] = $updatedUser['role'];
                $_SESSION['avatar'] = $updatedUser['avatar'];
            }
        }
        header("Location: ../../views/admin/dashboard.php?success=user_updated");
        exit;
    } else {
        header("Location: ../../views/admin/dashboard.php?error=update_failed");
        exit;
    }
}
