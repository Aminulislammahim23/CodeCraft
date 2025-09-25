<?php
require_once('../model/messageMod.php');

$user1 = $_GET['user1'] ?? 0;
$user2 = $_GET['user2'] ?? 0;

$messages = getChatMessages($user1, $user2);
echo json_encode($messages);
?>



