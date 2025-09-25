<?php
require_once('db.php');

function getTotalCourses() {
    $con = getConnection();
    $sql = "SELECT COUNT(*) as total FROM courses";
    $result = mysqli_query($con, $sql);
    if(!$result){
        die("SQL Error: " . mysqli_error($con));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

function getTotalStudents() {
    $con = getConnection();
    $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'student'";
    $result = mysqli_query($con, $sql);
    if(!$result){
        die("SQL Error: " . mysqli_error($con));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

function getCertificatesIssued() {
    $con = getConnection();
    $sql = "SELECT COUNT(*) as total FROM certificates";
    $result = mysqli_query($con, $sql);
    if(!$result){
        die("SQL Error: " . mysqli_error($con));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}


function getTotalInstructors(){
   $con = getConnection();
   $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'instructor'";
   $result = mysqli_query($con, $sql);
    if(!$result){
        die("SQL Error: " . mysqli_error($con));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}