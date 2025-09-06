<?php
session_start();
require_once('../model/userModel.php');

if(!isset($_COOKIE['status']) || !isset($_SESSION['user_id'])){
    header('location: ../view/login.php?error=badrequest');
    exit();
}

$id = $_SESSION['user_id'];
$user = getUserById($id);

if(!$user){
    die("User not found!");
}
?>

<?php
          require_once('../controller/auth.php');
          checkRole(['student']);   // only student can access
        ?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profile Management</title>
  <link rel="stylesheet" href="../assets/css/profile.css" />
</head>
<body>
    <header>
      
        <div class="logo">CodeCraft 
          <h1>Hello! <?= htmlspecialchars($_SESSION['username']) ?></h1>
        </div>
        <nav>
            <ul>
                <li><a href="../view/student.php">Home</a></li>
                <li><a href="../view/enrollment.php">Enroll</a></li>
                <li><a href="../view/progress.php">Progress</a></li>
                <li><a href="../controller/logout.php">Logout</a></li>
            </ul>
        </nav>  
    </header>

  <div class="sidebar">
    <h2>MY PROFILE</h2>
    <a href="#" onclick="showSection('view')">👨‍🎓 View Profile</a>
    <a href="#" onclick="showSection('edit')">🏅 Edit Profile</a>
  </div>

  <div class="main">

    <!-- View Profile -->
    <div class="card section" id="view">
      <h3>View Profile</h3>
      <p><b>Name:</b> <span><?= htmlspecialchars($user['username']) ?></span></p><br>
      <p><b>Email:</b> <span><?= htmlspecialchars($user['email']) ?></span></p><br>
      <p><b>Phone:</b> <span><?= htmlspecialchars($user['phone']) ?></span></p><br>
      <p><b>Date of Birth:</b> <span><?= htmlspecialchars($user['dob']) ?></span></p><br>
      <p><b>Avatar:</b><br>
        <img src="../<?= $user['avatar'] ?: 'assets/img/default-avatar.png' ?>" width="120" height="120" style="border-radius:50%">
      </p>
    </div>

    <!-- Edit Profile -->
    <div class="card section" id="edit" style="display:none;">
      <h3>Edit Profile</h3>
      <form action="../controller/updateProfile.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        
        <div class="grid-2">
          <div>
            <label>Name</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>">
          </div>
          <div>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
          </div>
          <div>
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter new password (leave blank to keep old)">
          </div>
          <div>
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
          </div>
          <div>
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($user['dob']) ?>">
          </div>
          <div>
            <!-- <label>Role</label>
            <select name="role">
              <option value="student" <?= $user['role']=='student'?'selected':'' ?>>Student</option>
              <option value="instructor" <?= $user['role']=='instructor'?'selected':'' ?>>Instructor</option>
              <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
            </select> -->
          </div>
        </div>

        <div class="avatar-editor">
          <label>Profile Picture</label><br>
          <img id="avatarPreview" src="../<?= $user['avatar'] ?: 'assets/img/default-avatar.png' ?>" width="120" height="120" style="border-radius:50%"><br><br>
          <input type="file" name="avatar" id="avatarFile" accept="image/*" onchange="previewAvatar(event)">
        </div>

        <div class="actions">
          <button class="btn primary" type="submit" name="update" value="1">Save</button>
          <button class="btn secondary" type="button" onclick="showSection('view')">Cancel</button>
        </div>
      </form>
    </div>

  </div>

<script src="../assets/js/profile.js"></script>
</body>
</html>
