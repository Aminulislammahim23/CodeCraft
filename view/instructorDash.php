<?php
session_start();
if (!isset($_COOKIE['status'])) {
    header('location: ../view/login.php?error=badrequest');
}
?>

<?php
          require_once('../controller/auth.php');
          checkRole(['instructor']);   // only instructor can access
        ?>