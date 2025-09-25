<?php
require_once('db.php');

// Send message
function sendMessage($sender_id, $receiver_id, $message) {
    $con = getConnection();
    $stmt = $con->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
    return $stmt->execute();
}

// Fetch chat messages between two users
function getChatMessages($user1, $user2){
    $con = getConnection();
    $sql = "SELECT m.*, s.username AS sender, s.avatar AS sender_avatar
            FROM messages m
            JOIN users s ON m.sender_id = s.id
            WHERE (m.sender_id=? AND m.receiver_id=?) OR (m.sender_id=? AND m.receiver_id=?)
            ORDER BY m.sent_at ASC";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iiii", $user1, $user2, $user2, $user1);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>


