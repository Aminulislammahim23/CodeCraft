<?php
    require_once('db.php');

    function login($user){
    $con = getConnection();
    $email = mysqli_real_escape_string($con, $user['email']);
    $password = mysqli_real_escape_string($con, $user['password']);

    $sql = "SELECT * FROM users WHERE email='{$email}' AND password='{$password}' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}


function getAllUser() {
    $con = getConnection();
    $sql = "SELECT * FROM users";
    $result = mysqli_query($con, $sql);

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    return $users;
}


function addUser($user){
    $con = getConnection();
    $sql = "insert into users values(null, '{$user['name']}' ,'{$user['username']}','{$user['password']}', '{$user['email']}', '{$user['dob']}','{$user['role']}',null)";
    if(mysqli_query($con, $sql)){
        return true;
    }else{
     return false;
    }
}

function getUserById($id){
    $con = getConnection();
    $sql = "select * from users where id={$id}";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function getAllInstructors() {
    $con = getConnection();
    $sql = "SELECT * FROM users WHERE role = 'instructor'" ;
    $result = mysqli_query($con, $sql);

    $instructors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $instructors[] = $row;
    }

    return $instructors;
}

function getAllStudents(){
    $con = getConnection();
    $sql = "SELECT * FROM users WHERE role = 'student'" ;
    $result = mysqli_query($con, $sql);

    $students = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }

    return $students;
}

    


function updateUser($user){
    $con = getConnection();

    $sql = "UPDATE users 
SET name='{$user['name']}', username='{$user['username']}', password='{$user['password']}',
    email='{$user['email']}', dob='{$user['dob']}', role='{$user['role']}', avatar='{$user['avatar']}'
WHERE id={$user['id']};";

    $result = mysqli_query($con, $sql);

    if(!$result){
        echo "Update Failed: " . mysqli_error($con);
        return false;
    }
    return true;
}
    

function deleteUser($id){
    $con = getConnection();

    $sql = "DELETE FROM users WHERE id={$id}";
    $result = mysqli_query($con, $sql);

    if(!$result){
        echo "Delete Failed: " . mysqli_error($con);
        return false;
    }
    return true;
}

function addSt($user){
    $con = getConnection();
    $sql = "insert into students values(null, '{$user['student_id']}' ,'{$user['user_id']}','{$user['enroll_date']}', '{$user['total_courses_enrolled']}', '{$user['total_courses_completed']}','{$user['learning_streak']}','{$user['certificates_earned']}')";
    if(mysqli_query($con, $sql)){
        return true;
    }else{
     return false;
    }
}