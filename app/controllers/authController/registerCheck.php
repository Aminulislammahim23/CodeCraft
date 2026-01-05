<?php
session_start();
require_once('../../models/userModel.php');

$name = trim($_REQUEST['name'] ?? '');
$email = trim($_REQUEST['email'] ?? '');
$password = trim($_REQUEST['password'] ?? '');
$role = $_REQUEST['role'] ?? 'student';
$avatar = $_FILES['avatar'] ?? null;

if ($name == "" || $email == "" || $password == "" || $role == "") {
    header("Location: ../views/auth/register.php?error=empty_fields");
    exit;
}

$user = [
    'full_name' => $name,
    'email' => $email,
    'password' => $password,
    'role' => $role
];

$result = addUser($user, $avatar ? ['avatar' => $avatar] : null);

if ($result === "EMAIL_EXISTS") {
    header("Location: ../views/auth/register.php?error=email_exists");
    exit;
} elseif ($result) {
    header("Location: ../views/auth/login.php?success=user_created");
    exit;
} else {
    header("Location: ../views/auth/register.php?error=registration_failed");
    exit;
}
?>