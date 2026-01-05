<?php

require_once 'db.php';   

function addPayment($paymentData) {
    $con = getConnection();
    
    $user_id = (int)$paymentData['user_id'];
    $course_id = (int)$paymentData['course_id'];
    $amount = (float)$paymentData['amount'];
    $payment_method = mysqli_real_escape_string($con, $paymentData['payment_method']);
    $payment_status = mysqli_real_escape_string($con, $paymentData['payment_status']);
    
    $sql = "INSERT INTO payments (user_id, course_id, amount, payment_method, payment_status) 
            VALUES ($user_id, $course_id, $amount, '$payment_method', '$payment_status')";
    
    return mysqli_query($con, $sql);
}

function getPaymentByUserAndCourse($userId, $courseId) {
    $con = getConnection();
    $userId = (int)$userId;
    $courseId = (int)$courseId;
    
    $sql = "SELECT * FROM payments WHERE user_id = $userId AND course_id = $courseId ORDER BY paid_at DESC LIMIT 1";
    $result = mysqli_query($con, $sql);
    
    if ($result && mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}

function getPaymentsByUser($userId) {
    $con = getConnection();
    $userId = (int)$userId;
    
    $sql = "SELECT p.*, c.title as course_title FROM payments p 
            JOIN courses c ON p.course_id = c.id
            WHERE p.user_id = $userId ORDER BY p.paid_at DESC";
    $result = mysqli_query($con, $sql);
    $payments = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }
    
    return $payments;
}

function generateInvoice($paymentId) {
    $con = getConnection();
    $paymentId = (int)$paymentId;
    
    $sql = "SELECT p.*, c.title as course_title, c.description as course_description, 
                 u.full_name as user_name, u.email as user_email
            FROM payments p 
            JOIN courses c ON p.course_id = c.id
            JOIN users u ON p.user_id = u.id
            WHERE p.id = $paymentId";
    $result = mysqli_query($con, $sql);
    
    if ($result && mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}

function getMonthlyRevenue() {
    $con = getConnection();

    if (!$con) {
        return 0; // safety fallback
    }

    $sql = "
        SELECT IFNULL(SUM(amount),0) AS monthly_total
        FROM payments
        WHERE payment_status = 'success'
        AND MONTH(paid_at) = MONTH(CURRENT_DATE())
        AND YEAR(paid_at) = YEAR(CURRENT_DATE())
    ";

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['monthly_total'];
}

function getTodayRevenue() {
    $con = getConnection();

    if (!$con) {
        return 0; // safety fallback
    }

    $sql = "
        SELECT IFNULL(SUM(amount),0) AS today_total
        FROM payments
        WHERE payment_status = 'success'
        AND DATE(paid_at) = CURDATE()
    ";

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['today_total'];
}

function getTodayOrdersCount() {
    $con = getConnection();

    if (!$con) {
        return 0; // safety fallback
    }

    $sql = "
        SELECT COUNT(*) AS order_count
        FROM payments
        WHERE payment_status = 'success'
        AND DATE(paid_at) = CURDATE()
    ";

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['order_count'];
}

function getTodayAverageRevenue() {
    $con = getConnection();

    if (!$con) {
        return 0; // safety fallback
    }

    $sql = "
        SELECT IFNULL(AVG(amount),0) AS avg_amount
        FROM payments
        WHERE payment_status = 'success'
        AND DATE(paid_at) = CURDATE()
    ";

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['avg_amount'];
}

function getYearlyRevenue() {
    $con = getConnection();

    if (!$con) {
        return 0; // safety fallback
    }

    $sql = "
        SELECT IFNULL(SUM(amount),0) AS yearly_total
        FROM payments
        WHERE payment_status = 'success'
        AND YEAR(paid_at) = YEAR(CURRENT_DATE())
    ";

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['yearly_total'];
}

function getYearlyTarget() {
    // For now, return a default target value
    // This could be configurable in a settings table
    return 50000; // $50,000 annual target
}

function getMonthlyGrowth() {
    $con = getConnection();

    if (!$con) {
        return '0%'; // safety fallback
    }

    // Get current month revenue
    $currentSql = "
        SELECT IFNULL(SUM(amount),0) AS current_month
        FROM payments
        WHERE payment_status = 'success'
        AND MONTH(paid_at) = MONTH(CURRENT_DATE())
        AND YEAR(paid_at) = YEAR(CURRENT_DATE())
    ";
    
    $prevSql = "
        SELECT IFNULL(SUM(amount),0) AS prev_month
        FROM payments
        WHERE payment_status = 'success'
        AND MONTH(paid_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
        AND YEAR(paid_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
    ";
    
    $currentResult = mysqli_query($con, $currentSql);
    $prevResult = mysqli_query($con, $prevSql);
    
    $currentRow = mysqli_fetch_assoc($currentResult);
    $prevRow = mysqli_fetch_assoc($prevResult);
    
    $current = $currentRow['current_month'];
    $prev = $prevRow['prev_month'];
    
    if ($prev == 0) {
        return $current > 0 ? '+100%' : '0%';
    }
    
    $growth = (($current - $prev) / $prev) * 100;
    return ($growth >= 0 ? '+' : '') . number_format($growth, 2) . '%';
}

function getRecentTransactions($limit = 10) {
    $con = getConnection();

    if (!$con) {
        return []; // safety fallback
    }

    $sql = "
        SELECT p.*, u.full_name as user_name, c.title as course_title
        FROM payments p
        JOIN users u ON p.user_id = u.id
        JOIN courses c ON p.course_id = c.id
        WHERE p.payment_status = 'success'
        ORDER BY p.paid_at DESC
        LIMIT $limit
    ";

    $result = mysqli_query($con, $sql);
    $transactions = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }

    return $transactions;
}

function getAllPaymentsForCSV() {
    $con = getConnection();

    if (!$con) {
        return []; // safety fallback
    }

    $sql = "
        SELECT p.*, u.full_name as user_name, u.email as user_email, c.title as course_title
        FROM payments p
        JOIN users u ON p.user_id = u.id
        JOIN courses c ON p.course_id = c.id
        ORDER BY p.paid_at DESC
    ";

    $result = mysqli_query($con, $sql);
    $payments = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }

    return $payments;
}

function getTodayPaymentsForCSV() {
    $con = getConnection();

    if (!$con) {
        return []; // safety fallback
    }

    $sql = "
        SELECT p.*, u.full_name as user_name, u.email as user_email, c.title as course_title
        FROM payments p
        JOIN users u ON p.user_id = u.id
        JOIN courses c ON p.course_id = c.id
        WHERE DATE(p.paid_at) = CURDATE()
        ORDER BY p.paid_at DESC
    ";

    $result = mysqli_query($con, $sql);
    $payments = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }

    return $payments;
}

function getMonthlyPaymentsForCSV() {
    $con = getConnection();

    if (!$con) {
        return []; // safety fallback
    }

    $sql = "
        SELECT p.*, u.full_name as user_name, u.email as user_email, c.title as course_title
        FROM payments p
        JOIN users u ON p.user_id = u.id
        JOIN courses c ON p.course_id = c.id
        WHERE MONTH(p.paid_at) = MONTH(CURRENT_DATE())
        AND YEAR(p.paid_at) = YEAR(CURRENT_DATE())
        ORDER BY p.paid_at DESC
    ";

    $result = mysqli_query($con, $sql);
    $payments = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }

    return $payments;
}

function getYearlyPaymentsForCSV() {
    $con = getConnection();

    if (!$con) {
        return []; // safety fallback
    }

    $sql = "
        SELECT p.*, u.full_name as user_name, u.email as user_email, c.title as course_title
        FROM payments p
        JOIN users u ON p.user_id = u.id
        JOIN courses c ON p.course_id = c.id
        WHERE YEAR(p.paid_at) = YEAR(CURRENT_DATE())
        ORDER BY p.paid_at DESC
    ";

    $result = mysqli_query($con, $sql);
    $payments = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }

    return $payments;
}

    
