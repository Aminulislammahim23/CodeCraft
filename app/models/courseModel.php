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
    $sql = "SELECT c.* FROM courses c 
            JOIN enrollments e ON c.id = e.course_id 
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
    
    // Check if connection is valid
    if (!$con) {
        error_log("Database connection failed in enrollInCourse");
        return false;
    }
    
    // Check if user is already enrolled
    $checkSql = "SELECT id FROM enrollments WHERE user_id = $userId AND course_id = $courseId";
    $checkResult = mysqli_query($con, $checkSql);
    
    if (!$checkResult) {
        error_log("Query failed: " . mysqli_error($con));
        return false;
    }
    
    if (mysqli_num_rows($checkResult) > 0) {
        error_log("User $userId already enrolled in course $courseId");
        return false; // Already enrolled
    }
    
    $sql = "INSERT INTO enrollments (user_id, course_id, payment_status) VALUES ($userId, $courseId, 'free')";
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        error_log("Insert failed: " . mysqli_error($con));
        return false;
    }
    
    return $result;
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

function searchCourse($query) {
    $con = getConnection();
    $q = trim($query);
    if ($q === '') return false;

    // If user typed a numeric id, search by id
    if (ctype_digit($q)) {
        $id = (int)$q;
        $stmt = $con->prepare("SELECT * FROM courses WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
    } else {
        // Use LIKE for title to allow partial matches
        $like = "%" . $q . "%";
        $stmt = $con->prepare("SELECT * FROM courses WHERE title LIKE ? LIMIT 1");
        $stmt->bind_param("s", $like);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return ($result && $result->num_rows) ? $result->fetch_assoc() : false;
}
