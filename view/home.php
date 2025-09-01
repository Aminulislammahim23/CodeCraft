<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_COOKIE['status']) || $_COOKIE['status'] !== 'true') {
    header("Location: login.php");
    exit();
    /////////// home ok 

      /////////// home ok 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; text-align: center;}
        .container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: inline-block; margin-top: 50px;}
        h1 { color: #333; }
        p { color: #555; }
         .enroll-btn { background-color: #5cdc35ff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 20px;}
        .logout-btn { background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 20px;}
        .logout-btn:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>You have successfully logged in.</p>
        <a href="enrollment.php" class="enroll-btn">Enroll Course</a>
        <a href="../controllers/logout.php" class="logout-btn">Logout</a>
         
    </div>
    
</body>
</html>
