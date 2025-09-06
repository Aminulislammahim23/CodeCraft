<?php

    session_start();
    setcookie('status', true, time()-10, '/');
    header('location: ../view/login.php');

?>