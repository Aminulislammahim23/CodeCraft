<?php
    require_once 'db.php';
function getAllCourses() {
    $con = getConnection();
    $sql = "SELECT * FROM courses";
    $result = mysqli_query($con, $sql);
    $courses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
    return $courses;
}
function countCourses() {
    $con = getConnection();
    $sql = "SELECT COUNT(*) AS total FROM courses";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
function countCoursesByInstructor($instructorId) {
    $con = getConnection();
    $instructorId = (int)$instructorId;
    $sql = "SELECT COUNT(*) AS total FROM courses WHERE instructor_id = $instructorId";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
function getCoursesByInstructor($instructorId) {
    $con = getConnection();
    $instructorId = (int)$instructorId;
    $sql = "SELECT * FROM courses WHERE instructor_id = $instructorId";
    $result = mysqli_query($con, $sql);
    $courses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
    return $courses;
}
function getCourseByTitle($title) {
    $con = getConnection();
    $title = mysqli_real_escape_string($con, $title);
    $sql = "SELECT * FROM courses WHERE title='{$title}' LIMIT 1";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
function addCourse($course) {
    $con = getConnection();
    $title = mysqli_real_escape_string($con, $course['title']);
    $description = mysqli_real_escape_string($con, $course['description'] ?? '');
    $category_id = (int)($course['category_id'] ?? 1);
    $instructor_id = (int)($course['instructor_id'] ?? null);
    $difficulty = mysqli_real_escape_string($con, $course['difficulty']);
    $duration = mysqli_real_escape_string($con, $course['duration']);
    $price = (float)($course['price'] ?? 0);
    $rating = (float)($course['rating'] ?? 0);
    $course_image = mysqli_real_escape_string($con, $course['course_image'] ?? 'default.png');
    $sql = "INSERT INTO courses (title, description, category_id, instructor_id, course_image, difficulty, duration, price, rating) 
            VALUES ('$title', '$description', $category_id, " . ($instructor_id ? $instructor_id : 'NULL') . ", '$course_image', '$difficulty', '$duration', $price, $rating)";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Add course failed: " . mysqli_error($con));
    }
    return $result;
}
function updateCourse($course) {
    $con = getConnection();
    $id = (int)$course['id'];
    $title = mysqli_real_escape_string($con, $course['title']);
    $description = mysqli_real_escape_string($con, $course['description'] ?? '');
    $category_id = (int)($course['category_id'] ?? 1);
    $difficulty = mysqli_real_escape_string($con, $course['difficulty']);
    $duration = mysqli_real_escape_string($con, $course['duration']);
    $price = (float)($course['price'] ?? 0);
    $rating = (float)($course['rating'] ?? 0);
    $sql = "UPDATE courses 
            SET title='$title', description='$description', category_id=$category_id, 
                difficulty='$difficulty', duration='$duration', price=$price, rating=$rating 
            WHERE id=$id";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Update course failed: " . mysqli_error($con));
    }
    return $result;
}
function deleteCourse($id) {
    $con = getConnection();
    $id = (int)$id;
    $sql = "DELETE FROM courses WHERE id=$id";
    return mysqli_query($con, $sql);
}
function getCourseById($id) {
    $con = getConnection();
    $id = (int)$id;
    $sql = "SELECT * FROM courses WHERE id = $id LIMIT 1";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
function getEnrolledCourses($userId) {
    $con = getConnection();
    $userId = (int)$userId;
    $sql = "SELECT c.*, p.completed_percentage FROM courses c 
            JOIN enrollments e ON c.id = e.course_id 
            LEFT JOIN progress p ON c.id = p.course_id AND p.user_id = $userId
            WHERE e.user_id = $userId";
    $result = mysqli_query($con, $sql);
    $courses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
    return $courses;
}
function enrollInCourse($userId, $courseId) {
    $con = getConnection();
    $userId = (int)$userId;
    $courseId = (int)$courseId;
    if (!$con) {
        error_log("Database connection failed in enrollInCourse");
        return false;
    }
    $checkSql = "SELECT id FROM enrollments WHERE user_id = $userId AND course_id = $courseId";
    $checkResult = mysqli_query($con, $checkSql);
    if (!$checkResult) {
        error_log("Query failed: " . mysqli_error($con));
        return false;
    }
    if (mysqli_num_rows($checkResult) > 0) {
        error_log("User $userId already enrolled in course $courseId");
        return false;
    }
    mysqli_autocommit($con, FALSE);
    try {
        $sql = "INSERT INTO enrollments (user_id, course_id, payment_status) VALUES ($userId, $courseId, 'free')";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            throw new Exception("Enrollment insert failed: " . mysqli_error($con));
        }
        $progressSql = "INSERT INTO progress (user_id, course_id, completed_percentage) VALUES ($userId, $courseId, 0)";
        $progressResult = mysqli_query($con, $progressSql);
        if (!$progressResult) {
            throw new Exception("Progress record insert failed: " . mysqli_error($con));
        }
        mysqli_commit($con);
        mysqli_autocommit($con, TRUE);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($con);
        mysqli_autocommit($con, TRUE);
        error_log("Enrollment transaction failed: " . $e->getMessage());
        return false;
    }
}
function getCategories() {
    $con = getConnection();
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($con, $sql);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}
function countTotalEnrollments() {
    $con = getConnection();
    $sql = "SELECT COUNT(*) AS total FROM enrollments";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
function countEnrollmentsByInstructor($instructorId) {
    $con = getConnection();
    $instructorId = (int)$instructorId;
    $sql = "SELECT COUNT(*) AS total FROM enrollments e 
            JOIN courses c ON e.course_id = c.id 
            WHERE c.instructor_id = $instructorId";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
function countStudentsByInstructor($instructorId) {
    $con = getConnection();
    $instructorId = (int)$instructorId;
    $sql = "SELECT COUNT(DISTINCT e.user_id) AS total FROM enrollments e 
            JOIN courses c ON e.course_id = c.id 
            WHERE c.instructor_id = $instructorId";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
function countRevenueByInstructor($instructorId) {
    $con = getConnection();
    $instructorId = (int)$instructorId;
    $sql = "SELECT SUM(p.amount) AS total FROM payments p 
            JOIN courses c ON p.course_id = c.id 
            WHERE c.instructor_id = $instructorId";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'] ?: 0;
}
function searchCourse($query) {
    $con = getConnection();
    $q = trim($query);
    if ($q === '') return false;
    $q = mysqli_real_escape_string($con, $q);
    if (ctype_digit($q)) {
        $id = (int)$q;
        $sql = "SELECT * FROM courses WHERE id = $id LIMIT 1";
    } else {
        $sql = "SELECT * FROM courses WHERE title LIKE '%$q%' LIMIT 1";
    }
    $result = mysqli_query($con, $sql);
    return ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : false;
}
function countEnrollments($userId) {
    $con = getConnection();
    $userId = (int)$userId;
    $sql = "SELECT COUNT(*) AS total FROM enrollments WHERE user_id = $userId";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
function getCompletedCourses($userId) {
    $con = getConnection();
    $userId = (int)$userId;
    $sql = "SELECT COUNT(*) AS total FROM progress WHERE user_id = $userId AND completed_percentage >= 100";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
function getUserCompletedCoursesData($userId) {
    $con = getConnection();
    $userId = (int)$userId;
    $sql = "SELECT c.*, p.completed_percentage FROM courses c 
            JOIN progress p ON c.id = p.course_id 
            WHERE p.user_id = $userId AND p.completed_percentage >= 100";
    $result = mysqli_query($con, $sql);
    $courses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
    return $courses;
}
function countCertificates($userId) {
    $con = getConnection();
    $userId = (int)$userId;
    $sql = "SELECT COUNT(*) AS total FROM certificates WHERE user_id = $userId";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
function getUserCertificates($userId) {
    $con = getConnection();
    $userId = (int)$userId;
    $sql = "SELECT c.*, co.title AS course_title FROM certificates c 
            JOIN courses co ON c.course_id = co.id 
            WHERE c.user_id = $userId";
    $result = mysqli_query($con, $sql);
    $certs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $certs[] = $row;
    }
    return $certs;
}
function getOverallProgress($userId) {
    $con = getConnection();
    $userId = (int)$userId;
    $sql = "SELECT AVG(completed_percentage) AS avg_progress FROM progress WHERE user_id = $userId";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['avg_progress'] ? round($data['avg_progress'], 2) : 0;
}
function getLessonsByCourseId($courseId) {
    $con = getConnection();
    $courseId = (int)$courseId;
    $sql = "SELECT * FROM lessons WHERE course_id = $courseId ORDER BY lesson_order ASC";
    $result = mysqli_query($con, $sql);
    $lessons = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $lessons[] = $row;
    }
    return $lessons;
}
function addLesson($lessonData) {
    $con = getConnection();
    $courseId = (int)$lessonData['course_id'];
    $title = mysqli_real_escape_string($con, $lessonData['title']);
    $videoUrl = mysqli_real_escape_string($con, $lessonData['video_url'] ?? '');
    $content = mysqli_real_escape_string($con, $lessonData['content'] ?? '');
    $order = (int)($lessonData['lesson_order'] ?? 0);
    $sql = "INSERT INTO lessons (course_id, title, video_url, content, lesson_order) 
            VALUES ($courseId, '$title', '$videoUrl', '$content', $order)";
    return mysqli_query($con, $sql);
}
function updateLesson($lessonData) {
    $con = getConnection();
    $id = (int)$lessonData['id'];
    $title = mysqli_real_escape_string($con, $lessonData['title']);
    $videoUrl = mysqli_real_escape_string($con, $lessonData['video_url'] ?? '');
    $content = mysqli_real_escape_string($con, $lessonData['content'] ?? '');
    $order = (int)($lessonData['lesson_order'] ?? 0);
    $sql = "UPDATE lessons SET title='$title', video_url='$videoUrl', content='$content', lesson_order=$order WHERE id=$id";
    return mysqli_query($con, $sql);
}
function deleteLesson($id) {
    $con = getConnection();
    $id = (int)$id;
    $sql = "DELETE FROM lessons WHERE id=$id";
    return mysqli_query($con, $sql);
}
function isUserEnrolled($userId, $courseId) {
    $con = getConnection();
    $userId = (int)$userId;
    $courseId = (int)$courseId;
    $sql = "SELECT id FROM enrollments WHERE user_id = $userId AND course_id = $courseId";
    $result = mysqli_query($con, $sql);
    return (mysqli_num_rows($result) > 0);
}
function getQuizzesByCourseId($courseId) {
    $con = getConnection();
    $courseId = (int)$courseId;
    $sql = "SELECT * FROM quizzes WHERE course_id = $courseId";
    $result = mysqli_query($con, $sql);
    $quizzes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $quizzes[] = $row;
    }
    return $quizzes;
}
function saveQuizResult($userId, $courseId, $score) {
    $con = getConnection();
    $userId = (int)$userId;
    $courseId = (int)$courseId;
    $score = (int)$score;
    $sql = "INSERT INTO quiz_results (user_id, course_id, score) VALUES ($userId, $courseId, $score)";
    return mysqli_query($con, $sql);
}
function issueCertificate($userId, $courseId) {
    $con = getConnection();
    $userId = (int)$userId;
    $courseId = (int)$courseId;
    $issueDate = date('Y-m-d');
    $checkSql = "SELECT id FROM certificates WHERE user_id = $userId AND course_id = $courseId";
    $checkResult = mysqli_query($con, $checkSql);
    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        return true;
    }
    $sql = "INSERT INTO certificates (user_id, course_id, issue_date) VALUES ($userId, $courseId, '$issueDate')";
    return mysqli_query($con, $sql);
}
function getCourseProgress($userId, $courseId) {
    $con = getConnection();
    $userId = (int)$userId;
    $courseId = (int)$courseId;
    $sql = "SELECT completed_percentage FROM progress WHERE user_id = $userId AND course_id = $courseId LIMIT 1";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
        return (int)$data['completed_percentage'];
    }
    return 0;
}