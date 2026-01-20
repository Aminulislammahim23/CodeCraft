<?php
    session_start();
    session_unset();
    session_destroy();
    setcookie('status', true, time()  - 300, '/');
    header('location: ../views/auth/login.php');
?>