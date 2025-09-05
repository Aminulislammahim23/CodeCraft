<?php
session_start();
require_once '../model/cnx.php'; 
require_once '../model/mydb.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $dob = $_POST['dob'] ?? '';

    $errors = [];

   
    $_SESSION['old_signup_data'] = [
        'name' => $name,
        'email' => $email,
        'dob' => $dob,
    ];

 
    if (empty($name)) {
        $errors['name'] = "Name cannot be empty.";
    } else {
        $words = array_filter(explode(" ", $name));
        if (count($words) < 2) {
            $errors['name'] = "Name must contain at least two words.";
        }
        $firstChar = $name[0];
        if (!ctype_alpha($firstChar)) {
            $errors['name'] = "Name must start with a letter.";
        }
        if (!preg_match("/^[a-zA-Z .\-]+$/", $name)) {
            $errors['name'] = "Only letters, dot (.), dash (-), and space are allowed in name.";
        }
    }

    if (empty($email)) {
        $errors['email'] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors['password'] = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long.";
    }

    if (empty($confirmPassword)) {
        $errors['confirmPassword'] = "Confirm Password cannot be empty.";
    } elseif ($confirmPassword !== $password) {
        $errors['confirmPassword'] = "Passwords do not match.";
    }

    if (empty($dob)) {
        $errors['dob'] = "Date of Birth cannot be empty.";
    } else {
        $birthDate = new DateTime($dob);
        $currentDate = new DateTime();
        if ($birthDate > $currentDate) {
            $errors['dob'] = "Date of Birth cannot be in the future.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['signup_errors'] = $errors;
        header("Location: ../view/signup.php");
        exit();
    }


    if (insertUser($name, $email, $password, $dob)) {
        $_SESSION['signup_success_message'] = "Account created successfully! Please log in.";
        header("Location: ../view/login.php");
        exit();
    } else {
        $errors['email'] = "Email already exists.";
        $_SESSION['signup_errors'] = $errors;
        header("Location: ../view/signup.php");
        exit();
    }

} else {
    header("Location: ../view/signup.php");
    exit();
}
?>