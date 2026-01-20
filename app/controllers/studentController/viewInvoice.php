<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/asset_helper.php';
require_once __DIR__ . '/../../models/paymentModel.php';

function getAvatarPath($avatarFilename)
{
    $avatar = $avatarFilename ?? 'default.png';
    return UPLOADS_URL . '/users/avatars/' . htmlspecialchars($avatar);
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: " . controller_url('auth/login.php'));
    exit();
}
$paymentId = $_GET['payment_id'] ?? 0;
if ($paymentId <= 0) {
    header("Location: " . controller_url('studentController/invoices.php?error=invalid_invoice'));
    exit();
}
$invoice = generateInvoice($paymentId);
if (!$invoice || $invoice['user_id'] != $_SESSION['user_id']) {
    header("Location: " . controller_url('studentController/invoices.php?error=invoice_not_found'));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $invoice['id']; ?> | Student Dashboard</title>
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
                <li>
                    <a href="<?php echo controller_url('studentController/dashboard.php#courses'); ?>">📚 Courses</a>
                </li>
                <li>
                    <a href="<?php echo controller_url('studentController/dashboard.php#enrollments'); ?>">📦 Enrollments</a>
                </li>
                <li class="active">
                    <a href="<?php echo controller_url('studentController/invoices.php'); ?>">📄 Invoices</a>
                </li>
                <li>
                    <a href="<?php echo controller_url('logout.php'); ?>">🚪 Logout</a>
                </li>
            </ul>
        </aside>
        <main class="main">
            
            <header class="topbar">
                <h1>Invoice</h1>
                <div class="student-info">
                    <img src="<?php echo getAvatarPath($_SESSION['avatar'] ?? null); ?>" 
                         alt="<?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?> Avatar" 
                         class="user-avatar"
                         onerror="this.onerror=null; this.src='<?php echo getAvatarPath('default.png'); ?>';">
                    <span><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'student'); ?></span>
                </div>
            </header>
            <div class="invoice-container">
                <div class="invoice-header">
                    <h1>INVOICE</h1>
                    <p>Invoice #: #<?php echo $invoice['id']; ?></p>
                    <div class="invoice-status status-paid">PAID</div>
                </div>
                <div class="invoice-details">
                    <div class="invoice-from">
                        <h3>From:</h3>
                        <p>CodeCraft Academy</p>
                        <p>123 Education Street</p>
                        <p>Learning City, LC 12345</p>
                        <p>Email: info@codecraft.com</p>
                    </div>
                    <div class="invoice-to">
                        <h3>To:</h3>
                        <p><?php echo htmlspecialchars($invoice['user_name']); ?></p>
                        <p><?php echo htmlspecialchars($invoice['user_email']); ?></p>
                    </div>
                </div>
                <div class="invoice-info">
                    <div style="display: flex; justify-content: space-between;">
                        <div>
                            <p><strong>Invoice Date:</strong> <?php echo date('F j, Y', strtotime($invoice['paid_at'])); ?></p>
                            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($invoice['payment_method']); ?></p>
                        </div>
                        <div style="text-align: right;">
                            <p><strong>Due Date:</strong> <?php echo date('F j, Y', strtotime($invoice['paid_at'])); ?></p>
                            <p><strong>Status:</strong> <span class="invoice-status status-paid">PAID</span></p>
                        </div>
                    </div>
                </div>
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($invoice['course_title']); ?></td>
                            <td>$<?php echo number_format($invoice['amount'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="invoice-summary">
                    <div class="summary-row">
                        <div class="summary-label">Subtotal:</div>
                        <div class="summary-value">$<?php echo number_format($invoice['amount'], 2); ?></div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Tax (0%):</div>
                        <div class="summary-value">$0.00</div>
                    </div>
                    <div class="summary-row total-row">
                        <div class="summary-label">Total:</div>
                        <div class="summary-value">$<?php echo number_format($invoice['amount'], 2); ?></div>
                    </div>
                </div>
                <div class="invoice-actions">
                    <a href="<?php echo controller_url('studentController/downloadInvoice.php?payment_id=' . $invoice['id']); ?>" class="invoice-btn download-btn">Download PDF</a>
                    <a href="<?php echo controller_url('studentController/invoices.php'); ?>" class="invoice-btn back-btn">Back to Invoices</a>
                </div>
            </div>
        </main>
    </div>
    <script src="<?php echo js_url('student.js'); ?>"></script>
</body>
</html>