<?php
require_once('../models/userModel.php');
ini_set('display_errors', 0);
error_reporting(0);
ob_start();
header('Content-Type: application/json');
$query = trim($_POST['query'] ?? '');
if ($query === '') {
    ob_clean();
    echo json_encode(["status" => "not_found"]);
    exit;
}
$user = searchUser($query);
if ($user) {
    ob_clean();
    echo json_encode([
        "status" => "found",
        "user" => $user
    ]);
} else {
    ob_clean();
    echo json_encode([
        "status" => "not_found"
    ]);
}
exit;