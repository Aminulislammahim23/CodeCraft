<?php
    if(isset($_GET['error'])){
        $error = $_GET['error'];
        if($error == "invalid_user"){
            $err1= "please type valid username/password!";
        }elseif($error == "badrequest"){
            $err2= "please login first!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Login Page</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post" action="controller/validation.php" enctype="multipart/form-data">
            <div class="input-group">
                <input type="text" id="username" name="username" required>
                <label>Username</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" required>
                <label>Password</label>
            </div>
            <div>
                <p><?php if(isset($err1)){echo $err1;} ?> </p>
                <p><?php if(isset($err2)){echo $err2;} ?> </p>
            </div>
            <button type="submit" class="login-btn">Login</button>
            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>
        </form>
    </div>
    
 
    <script src="../js/login.js"></script>
</body>
</html>
