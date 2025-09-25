<?php
require_once('db.php'); 

// Add a new course safely
function addCourse($course){
    $con = getConnection();

    // Prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "INSERT INTO courses (title, description, instructor_id, category, level, price, duration) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if(!$stmt){
        die("Prepare failed: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssissds", // s=string, i=int, d=double
        $course['title'],
        $course['description'],
        $course['instructor_id'],
        $course['category'],
        $course['level'],
        $course['price'],
        $course['duration']
    );

    $result = mysqli_stmt_execute($stmt);

    if(!$result){
        echo "Execute failed: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);

    return $result;
}

// Get all courses
function getAllCourses() {
    $con = getConnection();
    $sql = "SELECT * FROM courses";
    $result = mysqli_query($con, $sql);
    $courses = [];
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row;
        }
    }
    return $courses;
}
?>


