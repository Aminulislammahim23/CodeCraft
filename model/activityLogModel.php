<?php
require_once('db.php');

function getActivityLogs($filters = []) {
    $con = dbConnection();

    $where = [];
    if (!empty($filters['user'])) {
        $user = mysqli_real_escape_string($con, $filters['user']);
        $where[] = "user = '$user'";
    }
    if (!empty($filters['action_type'])) {
        $action = mysqli_real_escape_string($con, $filters['action_type']);
        $where[] = "action_type = '$action'";
    }
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $start = mysqli_real_escape_string($con, $filters['start_date']);
        $end = mysqli_real_escape_string($con, $filters['end_date']);
        $where[] = "created_at BETWEEN '$start 00:00:00' AND '$end 23:59:59'";
    }

    $sql = "SELECT * FROM activity_logs";
    if (count($where) > 0) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    $sql .= " ORDER BY created_at DESC";

    $result = mysqli_query($con, $sql);
    $logs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $logs[] = $row;
    }
    return $logs;
}
