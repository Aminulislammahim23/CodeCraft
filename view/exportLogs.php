<?php
session_start();
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=unauthorized');
    exit();
}
require_once('../controller/auth.php');
checkRole(['admin']);

require_once('../model/activityLogModel.php');

$filters = [
    'user' => $_GET['user'] ?? '',
    'action_type' => $_GET['action_type'] ?? '',
    'start_date' => $_GET['start_date'] ?? '',
    'end_date' => $_GET['end_date'] ?? ''
];

$logs = getActivityLogs($filters);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="activity_logs.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'User', 'Action Type', 'Description', 'Date/Time']);

foreach ($logs as $log) {
    fputcsv($output, [
        $log['id'], 
        $log['user'], 
        $log['action_type'], 
        $log['description'], 
        $log['created_at']
    ]);
}

fclose($output);
exit();
