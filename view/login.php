<?php
if(isset($_GET['error'])){
    $error = $_GET['error'];
    if($error == "invalid_user"){
        $err1 = "please type valid username/password!";
        echo $err1;
    }elseif($error == 'badrequest'){
        $err2 = "please login first";
        echo $err2;
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/login.css">
    </head>
    <body>
        <div class="login-container">
        <form method="post" action="../controller/loginCheck.php" enctype="multipart/form-data">

            <div class="input-group">
                
                <input type="text" name="email" value=""/>
                <label>Email :</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" value=""/>
                <label>Password :</label>
            </div>

            <input type="submit" class="login-btn" name="submit" value="submit"/> <br><br>

            <div class="signup-link">
                <a href="chooseRole.php">Don't have an account? Sign Up</a>
            </div>
        </div>
        </form>
    </body>
</html>