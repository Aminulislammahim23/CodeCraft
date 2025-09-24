<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_COOKIE['status']) || !isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php?error=unauthorized");
}

// Function to check role
function checkRole($roles = []) {
    if (!in_array($_SESSION['role'], $roles)) {
        header("Location: ../view/errors/404.php");
    }
}
?>