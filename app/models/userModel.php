<?php
    require_once 'db.php';

    function login($user) {
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

    function getAllusers() {
        $con = getConnection();
        $sql = "SELECT * FROM users";
        $result = mysqli_query($con, $sql);
        $users = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }

        return $users;
    }

    function countUsers() {
        $con = getConnection();
        $sql = "SELECT COUNT(*) AS total FROM users";
        $result = mysqli_query($con, $sql);
        $data = mysqli_fetch_assoc($result);
        return $data['total'];
    }

  
    function addUser($user, $file = null) {

        $con = getConnection();
        $full_name = trim($user['full_name']);
        $email     = trim($user['email']);
        $password  = $user['password'];
        $role      = $user['role'];

        // 🔍 Check if user already exists
        $check = $con->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            return "EMAIL_EXISTS";
        }

        // 🖼️ Avatar upload (optional)
        $avatar = "default.png";

        if ($file && isset($file['avatar']) && $file['avatar']['name'] !== "") {
            $ext = pathinfo($file['avatar']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array(strtolower($ext), $allowed)) {
                $avatar = uniqid("avatar_") . "." . $ext;
                $uploadPath = "../../assets/uploads/users/avatars/" . $avatar;
                
                move_uploaded_file($file['avatar']['tmp_name'], $uploadPath);
            }
        }

        // ✅ Insert new user (password plain text - should be hashed in production)
        $stmt = $con->prepare(
            "INSERT INTO users (full_name, email, password, role, avatar) 
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("sssss", $full_name, $email, $password, $role, $avatar);

        return $stmt->execute();

    }

    function updateUser($user) {
        $con = getConnection();
        $id = intval($user['id']);
        $full_name = trim($user['full_name']);
        $email = trim($user['email']);
        $password = $user['password'] ?? '';
        $role = $user['role'] ?? 'student';
        $file = $user['avatar'] ?? null;

        // Check user exists
        $check = $con->prepare("SELECT id, avatar FROM users WHERE id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 0) {
            return "NOT_FOUND";
        }

        $row = $result->fetch_assoc();
        $oldAvatar = $row['avatar'];

        $avatar = $oldAvatar;

        if ($file && isset($file['name']) && $file['name'] !== "") {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $avatar = uniqid("avatar_") . "." . $ext;

            move_uploaded_file(
                $file['tmp_name'],
                "../../assets/uploads/users/avatars/" . $avatar
            );

            if ($oldAvatar !== "default.png" && file_exists("../../assets/uploads/users/avatars/" . $oldAvatar)) {
                unlink("../../assets/uploads/users/avatars/" . $oldAvatar);
            }
        }

        if ($password !== '') {
            $stmt = $con->prepare(
                "UPDATE users 
                SET full_name = ?, email = ?, password = ?, avatar = ?, role = ?
                WHERE id = ?"
            );
            $stmt->bind_param("sssssi", $full_name, $email, $password, $avatar, $role, $id);
        } else {
            $stmt = $con->prepare(
                "UPDATE users 
                SET full_name = ?, email = ?, avatar = ?, role = ?
                WHERE id = ?"
            );
            $stmt->bind_param("ssssi", $full_name, $email, $avatar, $role, $id);
        }

        return $stmt->execute();
    }

    function searchUser($query) {

        $con = getConnection();

        $q = trim($query);
        if ($q === '') return false;

        // If user typed a numeric id, search by id
        if (ctype_digit($q)) {
            $id = (int)$q;
            $stmt = $con->prepare("SELECT id, full_name, email, role, avatar FROM users WHERE id = ? LIMIT 1");
            $stmt->bind_param("i", $id);
        } else {
            // Use LIKE for both email and full_name to allow partial matches
            $like = "%" . $q . "%";
            $stmt = $con->prepare("SELECT id, full_name, email, role, avatar FROM users WHERE email LIKE ? OR full_name LIKE ? LIMIT 1");
            $stmt->bind_param("ss", $like, $like);
        }

        $stmt->execute();

        $result = $stmt->get_result();
        return ($result && $result->num_rows) ? $result->fetch_assoc() : false;
    }

    function getUserById($id) {
        $con = getConnection();
        $stmt = $con->prepare("SELECT id, full_name, email, role, avatar FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows) ? $result->fetch_assoc() : false;
    }

    function deleteUser($id) {
        $con = getConnection();
        $id = intval($id);
        
        // Check if user exists
        $check = $con->prepare("SELECT id, avatar FROM users WHERE id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows === 0) {
            return "NOT_FOUND";
        }
        
        $row = $result->fetch_assoc();
        $avatar = $row['avatar'];
        
        // Delete user from database
        $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        
        // Delete avatar file if not default
        if ($success && $avatar !== "default.png") {
            $avatarPath = "../../assets/uploads/users/avatars/" . $avatar;
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }
        }
        
        return $success;
    }


