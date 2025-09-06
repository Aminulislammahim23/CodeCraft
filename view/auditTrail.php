<?php
session_start();
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=unauthorized');
    exit();
}
require_once('../controller/auth.php');
checkRole(['admin']);

require_once('../model/activityLogModel.php');

// Get filters from GET or POST
$filters = [
    'user' => $_GET['user'] ?? '',
    'action_type' => $_GET['action_type'] ?? '',
    'start_date' => $_GET['start_date'] ?? '',
    'end_date' => $_GET['end_date'] ?? ''
];

$logs = getActivityLogs($filters);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Audit Trail - Activity Logs</title>
<link rel="stylesheet" href="../assets/css/admin.css" />
</head>
<body>

<header>
    <div class="logo">CodeCraft</div>
    <nav>
      <ul>
        <li><a href="admin.php">Dashboard</a></li>
        <li><a href="auditTrail.php" class="active">Audit Trail</a></li>
        <li><a href="../controller/logout.php">Logout</a></li>
      </ul>
    </nav>
</header>

<main class="container">
    <h2>Audit Trail - Activity Logs</h2>

    <form method="GET" action="auditTrail.php" class="filter-form">
        <label>User: <input type="text" name="user" value="<?=htmlspecialchars($filters['user'])?>" /></label>
        <label>Action Type: 
            <select name="action_type">
                <option value="">-- All --</option>
                <option value="login" <?=($filters['action_type']=='login' ? 'selected' : '')?>>Login</option>
                <option value="logout" <?=($filters['action_type']=='logout' ? 'selected' : '')?>>Logout</option>
                <option value="update" <?=($filters['action_type']=='update' ? 'selected' : '')?>>Update</option>
                <option value="delete" <?=($filters['action_type']=='delete' ? 'selected' : '')?>>Delete</option>
            </select>
        </label>
        <label>Start Date: <input type="date" name="start_date" value="<?=htmlspecialchars($filters['start_date'])?>" /></label>
        <label>End Date: <input type="date" name="end_date" value="<?=htmlspecialchars($filters['end_date'])?>" /></label>
        <button type="submit">Filter</button>
        <a href="exportLogs.php?<?=http_build_query($filters)?>" class="btn-export">Export CSV</a>
    </form>

    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Action Type</th>
                <th>Description</th>
                <th>Date/Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($logs) == 0): ?>
                <tr><td colspan="5" style="text-align:center;">No logs found.</td></tr>
            <?php else: ?>
                <?php foreach($logs as $log): ?>
                    <tr>
                        <td><?=htmlspecialchars($log['id'])?></td>
                        <td><?=htmlspecialchars($log['user'])?></td>
                        <td><?=htmlspecialchars($log['action_type'])?></td>
                        <td><?=htmlspecialchars($log['description'])?></td>
                        <td><?=htmlspecialchars($log['created_at'])?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
