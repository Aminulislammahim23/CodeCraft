<?php
session_start();

// Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../views/auth/login.php");
    exit();
}

require_once('../../models/paymentModel.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['type'])) {
    header("Location: ../../views/admin/dashboard.php?error=invalid_request");
    exit();
}

$type = $_POST['type'];
$payments = [];

// Get the appropriate data based on type
switch ($type) {
    case 'today':
        $payments = getTodayPaymentsForCSV();
        $filename = 'revenue_today_' . date('Y-m-d') . '.csv';
        break;
    case 'monthly':
        $payments = getMonthlyPaymentsForCSV();
        $filename = 'revenue_monthly_' . date('Y-m') . '.csv';
        break;
    case 'yearly':
        $payments = getYearlyPaymentsForCSV();
        $filename = 'revenue_yearly_' . date('Y') . '.csv';
        break;
    case 'all':
        $payments = getAllPaymentsForCSV();
        $filename = 'revenue_all_' . date('Y-m-d') . '.csv';
        break;
    default:
        header("Location: ../../views/admin/dashboard.php?error=invalid_request");
        exit();
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream
$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, [
    'ID',
    'User Name',
    'User Email',
    'Course Title',
    'Amount',
    'Payment Method',
    'Status',
    'Date'
]);

// Add data rows
foreach ($payments as $payment) {
    fputcsv($output, [
        $payment['id'],
        $payment['user_name'],
        $payment['user_email'],
        $payment['course_title'],
        $payment['amount'],
        $payment['payment_method'],
        $payment['payment_status'],
        $payment['paid_at']
    ]);
}

// Close output stream
fclose($output);
exit();
?>