<?php
    require_once('mydb.php');

    function login($user){
    $con = getConnection();
    $username = mysqli_real_escape_string($con, $user['username']);
    $password = mysqli_real_escape_string($con, $user['password']);

    $sql = "SELECT * FROM users WHERE username='{$username}' AND password='{$password}' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}

    function addUser($user){
        $con = getConnection();
        $sql = "insert into users values(null, '{$user['username']}', '{$user['password']}', '{$user['email']}')";
        if(mysqli_query($con, $sql)){
            return true;
        }else{
            return false;
        }
    }

    function getAlluser(){
        $con = getConnection();
        $sql = "select * from users";
        $result = mysqli_query($con, $sql);
        $users = [];

        while($row = mysqli_fetch_assoc($result)){
            //print_r($row);
            array_push($users, $row);
        }

        //print_r($users);
        return $users;
    }

    function getUserById($id){
        $con = getConnection();
        $sql = "select * from users where id={$id}";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    function updateUser($user){

    }

    function deleteUser($id){

    }



    function getInstructors() {
    global $conn;
    $sql = "SELECT * FROM users WHERE role='instructor'";
    $result = $conn->query($sql);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}


function getStudents() {
    global $conn;
    $sql = "SELECT * FROM users WHERE role='student'";
    $result = $conn->query($sql);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}
    
?>