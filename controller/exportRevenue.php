<?php
session_start();

if (!isset($_COOKIE['status'])) {
    header('location: ../view/login.php?error=unauthorized');
    exit();
}

require_once('../model/revenueMod.php'); 
require_once('auth.php');
checkRole(['admin']);

if (isset($_POST['export']) && $_POST['export'] === 'csv') {
    $year = $_POST['year'];

    // Fetch revenue data
    $data = getAnnualRevenue($year);

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=annual_revenue_$year.csv");

    $output = fopen("php://output", "w");

    // CSV Column Headers
    fputcsv($output, ['Date', 'Course Name', 'Student', 'Amount']);
foreach ($data as $row) {
    fputcsv($output, [$row['date'], $row['course_name'], $row['student_name'], $row['amount']]);
}


    fclose($output);
    exit();

} else {
    echo "Invalid Request.";
}
?>
