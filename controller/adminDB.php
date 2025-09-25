<?php
session_start();
require_once('../model/adminDashMod.php');

// dashboard data
$totalCourses     = getTotalCourses();
$totalStudents    = getTotalStudents();
$certificatesIssued = getCertificatesIssued();

// pass to view
$_SESSION['dashboard'] = [
    'totalCourses' => $totalCourses,
    'totalStudents' => $totalStudents,
    'certificatesIssued' => $certificatesIssued
];

header("Location: ../view/admin.php");
exit();
?>