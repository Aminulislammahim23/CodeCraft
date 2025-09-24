<?php
$host = "127.0.0.1";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "codecraftdb";


    function getConnection(){
        global $dbname, $dbpass, $dbuser;

        $con = mysqli_connect($GLOBALS['host'], $dbuser, $dbpass, $dbname);
        return $con;
    }
?>