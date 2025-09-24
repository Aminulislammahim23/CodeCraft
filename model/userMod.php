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
        $sql = "insert into users values(null, '{$user['name']}' ,'{$user['username']}','{$user['email']}', '{$user['password']}', '{$user['dob']}','{$user['role']}')";
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