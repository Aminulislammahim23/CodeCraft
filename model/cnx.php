<?php


$host = 'localhost'; 
$db = 'elpdb';
$user = 'root';     
$pass = '';          
$port = '3307';     
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
     
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>