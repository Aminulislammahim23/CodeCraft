<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/asset_helper.php';
require_once __DIR__ . '/../../models/paymentModel.php';

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
$invoiceContent = "CODECRAFT ACADEMY INVOICE\n";
$invoiceContent .= "=========================\n\n";
$invoiceContent .= "Invoice #: #{$invoice['id']}\n";
$invoiceContent .= "Invoice Date: " . date('F j, Y', strtotime($invoice['paid_at'])) . "\n";
$invoiceContent .= "Customer: {$invoice['user_name']}\n";
$invoiceContent .= "Email: {$invoice['user_email']}\n\n";
$invoiceContent .= "Description: {$invoice['course_title']}\n";
$invoiceContent .= "Amount: $" . number_format($invoice['amount'], 2) . "\n";
$invoiceContent .= "Payment Method: {$invoice['payment_method']}\n";
$invoiceContent .= "Status: PAID\n\n";
$invoiceContent .= "Thank you for your business!\n";
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="invoice_' . $invoice['id'] . '.txt"');
header('Content-Length: ' . strlen($invoiceContent));
echo $invoiceContent;
exit();
?>