<?php
session_start();
require_once '../model/mydb.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newName = trim($_POST['name'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');
    $newPassword = $_POST['password'] ?? '';

    $errors = [];
    $currentEmail = $_SESSION['username'];

    if (empty($newName)) {
        $errors[] = "Name cannot be empty.";
    }
    if (empty($newEmail) || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (!empty($newPassword) && strlen($newPassword) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (empty($errors)) {
        $user = getUserByEmail($currentEmail);
        if ($user && $currentEmail !== $newEmail) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$newEmail]);
            if ($stmt->fetch()) {
                $errors[] = "Email already exists.";
            }
        }

        if (empty($errors) && updateUserProfile($currentEmail, $newName, $newEmail, $newPassword)) {
            $_SESSION['username'] = $newEmail; 
            echo 'success';
            exit();
        }
    }
    echo implode("\n", $errors);
    exit();
}
?>