<?php
session_start();
require_once('../model/db.php'); // database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title        = trim($_POST['title']);
    $description  = trim($_POST['description']);
    $instructor_id= $_SESSION['user_id']; // ধরে নিচ্ছি instructor login করা আছে
    $category     = trim($_POST['category']);
    $level        = trim($_POST['level']);
    $price        = trim($_POST['price']);
    $duration     = trim($_POST['duration']);

    if ($title == "" || $description == "" || $category == "" || $level == "" || $price == "" || $duration == "") {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../view/home.php");
        exit();
    }

    $conn = getConnection();

    $sql = "INSERT INTO courses 
            (title, description, instructor_id, category, level, price, duration, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssissds", 
        $title, 
        $description, 
        $instructor_id, 
        $category, 
        $level, 
        $price, 
        $duration
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "✅ Course added successfully!";
    } else {
        $_SESSION['error'] = "❌ Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: ../view/home.php");
    exit();
} else {
    header("Location: ../view/home.php");
    exit();
}
?>
