<?php
    require_once 'db.php';
    function login($user) {
        $con = getConnection();
        $email = mysqli_real_escape_string($con, $user['email']);
        $password = $user['password'];
        $sql = "SELECT * FROM users WHERE email='{$email}' LIMIT 1";
        $result = mysqli_query($con, $sql);
        if ($result && mysqli_num_rows($result) == 1) {
            $userData = mysqli_fetch_assoc($result);
            if (password_verify($password, $userData['password'])) {
                return $userData;
            } else {
                if ($userData['password'] === $password) {
                    return $userData;
                }
            }
        }
        return false;
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
        $check = $con->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows > 0) {
            return "EMAIL_EXISTS";
        }
        $avatar = "default.png";
        if ($file && isset($file['avatar']) && $file['avatar']['name'] !== "") {
            $ext = pathinfo($file['avatar']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array(strtolower($ext), $allowed)) {
                $avatar = uniqid("avatar_") . "." . $ext;
                $uploadPath = "../../assets/uploads/users/avatars/" . $avatar;
                if (!is_dir("../../assets/uploads/users/avatars/")) {
                    mkdir("../../assets/uploads/users/avatars/", 0777, true);
                }
                move_uploaded_file($file['avatar']['tmp_name'], $uploadPath);
            }
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $con->prepare(
            "INSERT INTO users (full_name, email, password, role, avatar) 
            VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $full_name, $email, $hashed_password, $role, $avatar);
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
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $con->prepare(
                "UPDATE users 
                SET full_name = ?, email = ?, password = ?, avatar = ?, role = ?
                WHERE id = ?"
            );
            $stmt->bind_param("sssssi", $full_name, $email, $hashed_password, $avatar, $role, $id);
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
        if (ctype_digit($q)) {
            $id = (int)$q;
            $stmt = $con->prepare("SELECT id, full_name, email, role, avatar FROM users WHERE id = ? LIMIT 1");
            $stmt->bind_param("i", $id);
        } else {
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
        $check = $con->prepare("SELECT id, avatar FROM users WHERE id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows === 0) {
            return "NOT_FOUND";
        }
        $row = $result->fetch_assoc();
        $avatar = $row['avatar'];
        $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        if ($success && $avatar !== "default.png") {
            $avatarPath = "../../assets/uploads/users/avatars/" . $avatar;
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }
        }
        return $success;
    }
    function getUserPassword($id) {
        $con = getConnection();
        $stmt = $con->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = ($result && $result->num_rows) ? $result->fetch_assoc() : false;
        return $user ? $user['password'] : false;
    }
    function updateUserPassword($id, $hashedPassword) {
        $con = getConnection();
        $stmt = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }
    function updateUserAvatar($id, $avatar) {
        $con = getConnection();
        $stmt = $con->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $stmt->bind_param("si", $avatar, $id);
        return $stmt->execute();
    }
    function updateUserInfo($id, $full_name, $email, $phone = null, $bio = null) {
        $con = getConnection();
        $sql = "UPDATE users SET full_name = ?, email = ?";
        $params = [$full_name, $email];
        $types = "ss";
        $phoneColumnExists = checkColumnExists($con, 'users', 'phone');
        if ($phoneColumnExists && $phone !== null) {
            $sql .= ", phone = ?";
            $params[] = $phone;
            $types .= "s";
        }
        $bioColumnExists = checkColumnExists($con, 'users', 'bio');
        if ($bioColumnExists && $bio !== null) {
            $sql .= ", bio = ?";
            $params[] = $bio;
            $types .= "s";
        }
        $sql .= " WHERE id = ?";
        $params[] = $id;
        $types .= "i";
        $stmt = $con->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }
    function checkColumnExists($connection, $table, $column) {
        $sql = "SHOW COLUMNS FROM `{$table}` LIKE '{$column}'";
        $result = mysqli_query($connection, $sql);
        return mysqli_num_rows($result) > 0;
    }