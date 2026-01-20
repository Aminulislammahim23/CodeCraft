<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/asset_helper.php';
require_once __DIR__ . '/../../models/paymentModel.php';
require_once __DIR__ . '/../../models/courseModel.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: " . controller_url('auth/login.php'));
    exit();
}
$userId = $_SESSION['user_id'];
$payments = getPaymentsByUser($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Invoices | Student Dashboard</title>
    <link rel="stylesheet" href="<?php echo css_url('student.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .invoices-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .invoices-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoices-header h1 {
            color: #4f46e5;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .invoices-header p {
            color: #64748b;
            font-size: 1.1rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card .icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #4f46e5;
        }
        .stat-card .label {
            color: #64748b;
            font-size: 0.9rem;
        }
        .invoices-list {
            margin-top: 30px;
        }
        .filter-section {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-section select, .filter-section input {
            padding: 10px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
        }
        .invoice-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .invoice-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .invoice-card-content {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .invoice-info {
            flex: 1;
        }
        .invoice-info h3 {
            margin: 0 0 10px 0;
            color: #1e293b;
        }
        .invoice-info p {
            margin: 5px 0;
            color: #64748b;
        }
        .invoice-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-success {
            background: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-failed {
            background: #fee2e2;
            color: #b91c1c;
        }
        .invoice-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .invoice-btn {
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .view-btn {
            background: #4f46e5;
            color: white;
        }
        .download-btn {
            background: #10b981;
            color: white;
        }
        .invoice-btn:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }
        .no-invoices {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .no-invoices .icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        .no-invoices h3 {
            color: #1e293b;
            margin-bottom: 10px;
        }
        .no-invoices p {
            color: #64748b;
            max-width: 500px;
            margin: 0 auto;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .search-box input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
        }
        .sort-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        .pagination a, .pagination span {
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            color: #4f46e5;
        }
        .pagination a:hover {
            background: #eff6ff;
        }
        .pagination .current {
            background: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }
        @media (max-width: 768px) {
            .invoice-card-content {
                flex-direction: column;
                align-items: flex-start;
            }
            .invoice-actions {
                width: 100%;
                justify-content: flex-end;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        
        <aside class="sidebar">
            <img src="<?php echo img_url('logo.png'); ?>" class="brand-logo">
            <h2 class="logo">Welcome to CodeCraft</h2>
            <ul class="menu">
                <li>
                    <a href="<?php echo view_url('student/dashboard.php'); ?>">📊 Dashboard</a>
                </li>
                <li>
                    <a href="<?php echo view_url('student/dashboard.php#courses'); ?>">📚 Courses</a>
                </li>
                <li>
                    <a href="<?php echo view_url('student/dashboard.php#enrollments'); ?>">📦 Enrollments</a>
                </li>
                <li class="active">
                    <a href="#">📄 Invoices</a>
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
                <h1><i class="fas fa-file-invoice"></i> My Invoices</h1>
                <div class="student-info">
                    <span><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'student'); ?></span>
                </div>
            </header>
            <div class="invoices-container">
                <div class="invoices-header">
                    <h1><i class="fas fa-receipt"></i> My Payment Invoices</h1>
                    <p>View and download your payment receipts</p>
                </div>
                <?php if (!empty($payments)): ?>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="icon">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div class="value"><?php echo count($payments); ?></div>
                            <div class="label">Total Invoices</div>
                        </div>
                        <div class="stat-card">
                            <div class="icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="value">$<?php echo number_format(array_sum(array_column($payments, 'amount')), 2); ?></div>
                            <div class="label">Total Spent</div>
                        </div>
                        <div class="stat-card">
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="value"><?php echo count(array_filter($payments, function($p) { return $p['payment_status'] === 'success'; })); ?></div>
                            <div class="label">Successful Payments</div>
                        </div>
                        <div class="stat-card">
                            <div class="icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="value"><?php echo !empty($payments) ? date('M Y', strtotime(max(array_column($payments, 'paid_at')))) : 'N/A'; ?></div>
                            <div class="label">Last Payment</div>
                        </div>
                    </div>
                    
                    <div class="search-box">
                        <input type="text" id="invoice-search" placeholder="Search invoices by course name...">
                    </div>
                    <div class="filter-section">
                        <select id="status-filter">
                            <option value="">All Statuses</option>
                            <option value="success">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                        </select>
                        <select id="date-filter">
                            <option value="">All Dates</option>
                            <option value="this-month">This Month</option>
                            <option value="last-month">Last Month</option>
                            <option value="this-year">This Year</option>
                        </select>
                    </div>
                    
                    <div class="invoices-list" id="invoices-list">
                        <?php foreach ($payments as $payment): ?>
                            <div class="invoice-card" data-course="<?php echo htmlspecialchars(strtolower($payment['course_title'])); ?>" data-status="<?php echo $payment['payment_status']; ?>" data-date="<?php echo $payment['paid_at']; ?>">
                                <div class="invoice-card-content">
                                    <div class="invoice-info">
                                        <h3><i class="fas fa-book"></i> <?php echo htmlspecialchars($payment['course_title']); ?></h3>
                                        <p><i class="fas fa-hashtag"></i> Payment ID: #<?php echo $payment['id']; ?></p>
                                        <p><i class="far fa-calendar"></i> Date: <?php echo date('M j, Y', strtotime($payment['paid_at'])); ?></p>
                                        <p><i class="fas fa-money-bill-wave"></i> Method: <?php echo htmlspecialchars($payment['payment_method']); ?></p>
                                        <p class="invoice-status status-<?php echo $payment['payment_status']; ?>">
                                            <i class="fas fa-circle"></i> Status: <?php echo ucfirst($payment['payment_status']); ?>
                                        </p>
                                    </div>
                                    <div class="invoice-actions">
                                        <a href="<?php echo controller_url('studentController/viewInvoice.php?payment_id=' . $payment['id']); ?>" class="invoice-btn view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="<?php echo controller_url('studentController/downloadInvoice.php?payment_id=' . $payment['id']); ?>" class="invoice-btn download-btn">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-invoices">
                        <div class="icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h3>No invoices yet</h3>
                        <p>You haven't made any payments yet. Once you enroll in a paid course, your invoices will appear here.</p>
                        <a href="<?php echo controller_url('studentController/dashboard.php#courses'); ?>" class="enroll-btn" style="margin-top: 20px; display: inline-block;">Browse Courses</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <script src="<?php echo js_url('student.js'); ?>"></script>
    <script>
        document.getElementById('invoice-search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.invoice-card');
            cards.forEach(card => {
                const courseName = card.getAttribute('data-course');
                if (courseName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        document.getElementById('status-filter').addEventListener('change', function() {
            const statusFilter = this.value;
            const cards = document.querySelectorAll('.invoice-card');
            cards.forEach(card => {
                const status = card.getAttribute('data-status');
                if (statusFilter === '' || status === statusFilter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        document.getElementById('date-filter').addEventListener('change', function() {
            const dateFilter = this.value;
            const cards = document.querySelectorAll('.invoice-card');
            cards.forEach(card => {
                const dateStr = card.getAttribute('data-date');
                const date = new Date(dateStr);
                const today = new Date();
                let show = true;
                if (dateFilter === 'this-month') {
                    show = date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear();
                } else if (dateFilter === 'last-month') {
                    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    show = date.getMonth() === lastMonth.getMonth() && date.getFullYear() === lastMonth.getFullYear();
                } else if (dateFilter === 'this-year') {
                    show = date.getFullYear() === today.getFullYear();
                }
                if (show) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>