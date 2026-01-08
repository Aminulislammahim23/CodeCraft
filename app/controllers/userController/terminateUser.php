<?php
session_start();
require_once('../../models/userModel.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $userId = intval($_POST['user_id'] ?? 0);
    
    if ($userId === 0) {
        header("Location: ../../views/admin/dashboard.php?error=invalid_user_id");
        exit;
    }
    
    // Prevent admin from deleting themselves
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
        header("Location: ../../views/admin/dashboard.php?error=cannot_delete_self");
        exit;
    }
    
    $result = deleteUser($userId);
    
    if ($result === "NOT_FOUND") {
        header("Location: ../../views/admin/dashboard.php?error=user_not_found");
        exit;
    } elseif ($result) {
        header("Location: ../../views/admin/dashboard.php?success=user_terminated");
        exit;
    } else {
        header("Location: ../../views/admin/dashboard.php?error=terminate_failed");
        exit;
    }
}
?>