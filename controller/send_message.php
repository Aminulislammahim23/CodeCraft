<?php
require_once('../model/messageMod.php');

$sender_id = isset($_POST['sender_id']) ? (int)$_POST['sender_id'] : 0;
$receiver_id = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if($sender_id && $receiver_id && $message){
    if(!sendMessage($sender_id, $receiver_id, $message)){
        echo "DB Insert failed!";
    }
} else {
    echo "Invalid input!";
}
?>

