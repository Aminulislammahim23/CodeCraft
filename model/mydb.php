<?php


require_once 'cnx.php';  

function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function insertUser($name, $email, $password, $dob) {
    global $pdo;
    try {
     
        $existingUser = getUserByEmail($email);
        if ($existingUser) {
            return false; 
        }

       
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, dob) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$name, $email, $hashedPassword, $dob]);
        return $success;
    } catch (PDOException $e) {
        error_log("Error in insertUser: " . $e->getMessage());
        return false;
    }
}

function updateUserProfile($email, $newName, $newEmail, $newPassword) {
    global $pdo;
    $user = getUserByEmail($email);
    if (!$user) return false;

    $updateData = ['name' => $newName, 'email' => $newEmail];
    if (!empty($newPassword)) {
        $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
    } else {
        $updateData['password'] = $user['password'];
    }

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE email = ?");
    $success = $stmt->execute([$updateData['name'], $updateData['email'], $updateData['password'], $email]);

    return $success;
}

function isEnrolled($userEmail, $courseCode) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE user_email = ? AND course_code = ?");
    $stmt->execute([$userEmail, $courseCode]);
    return $stmt->fetch() !== false;
}

function enrollUser($userEmail, $courseCode, $paymentMethod) {
    global $pdo;
    if (isEnrolled($userEmail, $courseCode)) {
        return false;  
    }
    $stmt = $pdo->prepare("INSERT INTO enrollments (user_email, course_code, payment_method) VALUES (?, ?, ?)");
    return $stmt->execute([$userEmail, $courseCode, $paymentMethod]);
}

function getEnrolledCourses($userEmail) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.name, e.course_code AS code, e.enrollment_date, e.payment_method
        FROM enrollments e
        JOIN courses c ON e.course_code = c.code
        WHERE e.user_email = ?
        ORDER BY e.enrollment_date DESC
    ");
    $stmt->execute([$userEmail]);
    return $stmt->fetchAll();
}


function getQuestionsForCourse($courseCode) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE course_code = ?");
    $stmt->execute([$courseCode]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function recordQuizAttempt($userEmail, $courseCode, $score, $total) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO quiz_attempts (user_email, course_code, score) VALUES (?, ?, ?)");
    return $stmt->execute([$userEmail, $courseCode, $score]);
}


function getHighestQuizScore($userEmail, $courseCode) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT MAX(score) AS highest FROM quiz_attempts WHERE user_email = ? AND course_code = ?");
    $stmt->execute([$userEmail, $courseCode]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['highest'] ?? 0;
}
?>