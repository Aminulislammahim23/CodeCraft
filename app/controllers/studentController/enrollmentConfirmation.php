<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/asset_helper.php';
require_once __DIR__ . '/../../models/courseModel.php';
require_once __DIR__ . '/../../models/paymentModel.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: " . controller_url('auth/login.php'));
    exit();
}
$receipt = $_SESSION['receipt'] ?? null;
if (!$receipt) {
    header("Location: " . controller_url('studentController/dashboard.php'));
    exit();
}
unset($_SESSION['receipt']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Confirmation | Student Dashboard</title>
    <link rel="stylesheet" href="<?php echo css_url('student.css'); ?>">
</head>
<body>
    <div class="admin-container">
        
        <aside class="sidebar">
            <img src="<?php echo img_url('logo.png'); ?>" class="brand-logo">
            <h2 class="logo">Welcome to CodeCraft</h2>
            <ul class="menu">
                <li>
                    <a href="<?php echo controller_url('studentController/dashboard.php'); ?>">📊 Dashboard</a>
                </li>
                <li class="active">
                    <a href="<?php echo controller_url('studentController/dashboard.php#courses'); ?>">📚 Courses</a>
                </li>
                <li>
                    <a href="<?php echo controller_url('studentController/dashboard.php#enrollments'); ?>">📦 Enrollments</a>
                </li>
                <li>
                    <a href="profile.php">👤 Profile</a>
                </li>
                <li>
                    <a href="<?php echo controller_url('logout.php'); ?>">🚪 Logout</a>
                </li>
            </ul>
        </aside>
        <main class="main">
            
            <header class="topbar">
                <h1>Confirmation</h1>
                <div class="student-info">
                    <span><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'student'); ?></span>
                </div>
            </header>
            <div class="confirmation-container">
                <div class="confirmation-header">
                    <div class="success-icon">✓</div>
                    <h1>Enrollment Successful!</h1>
                    <p>Congratulations! You have been successfully enrolled in the course.</p>
                </div>
                <div class="receipt-details">
                    <h3>Receipt Details</h3>
                    <p><strong>Course:</strong> <?php echo htmlspecialchars($receipt['course']); ?></p>
                    <p><strong>Amount Paid:</strong> $<?php echo number_format($receipt['amount'], 2); ?></p>
                    <p><strong>Transaction Date:</strong> <?php echo $receipt['date']; ?></p>
                    <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">Completed</span></p>
                </div>
                <p>A confirmation email has been sent to your registered email address with detailed receipt information.</p>
                <div class="btn-container">
                    <a href="<?php echo controller_url('studentController/dashboard.php'); ?>" class="btn btn-primary">Go to Dashboard</a>
                    <a href="<?php echo controller_url('studentController/dashboard.php#enrollments'); ?>" class="btn btn-secondary">View My Enrollments</a>
                    <a href="<?php echo controller_url('studentController/invoices.php'); ?>" class="btn btn-secondary">View Invoice</a>
                </div>
                <a href="<?php echo controller_url('studentController/dashboard.php'); ?>" class="back-to-dashboard">← Back to Dashboard</a>
            </div>
        </main>
    </div>
    <script src="<?php echo js_url('student.js'); ?>"></script>
</body>
</html>