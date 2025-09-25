<?php
session_start();
require_once('../model/userMod.php');

// Authentication check
if(!isset($_COOKIE['status']) || !isset($_SESSION['user_id'])){
    header('location: ../view/login.php?error=badrequest');
    exit();
}

$id = $_SESSION['user_id'];
$user = getUserById($id);

if(!$user){
    die("User not found!");
}

// Avatar fallback
$avatarPath = !empty($user['avatar']) ? $user['avatar'] : "assets/uploads/imgP/default.jpg";
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
        <div class="logo">CodeCraft</div>
        <h1>Hello! <?= htmlspecialchars($_SESSION['username']) ?></h1>
        <nav>
            <ul>
                <li>
                <?php
                $role = $_SESSION['role'] ?? '';
                if ($role === 'admin') {
                    $homeLink = '../view/adminDash.php';
                } elseif ($role === 'instructor') {
                    $homeLink = '../view/instructorDash.php';
                } else {
                    $homeLink = '../view/studentDash.php';
                }
                ?>
                <a href="<?= $homeLink ?>">Home</a>
                </li>
                <!-- <li><a href="../view/enrollment.php">Enroll</a></li>
                <li><a href="../view/progress.php">Progress</a></li> -->
                <li><a href="../controller/logout.php">Logout</a></li>
            </ul>
        </nav>  
    </header>

  <div class="sidebar">
    <h2>MY PROFILE</h2>
    <a href="#" onclick="showSection('view')">👨 View Profile</a>
    <a href="#" onclick="showSection('edit')">⚙ Edit Profile</a>
  </div>

  <div class="main">

    <!-- View Profile -->
    <div class="card section" id="view">
      <h3>View Profile</h3>
      <p><b>Name:</b> <span><?= htmlspecialchars($user['name']) ?></span></p><br>
      <p><b>Username:</b> <span><?= htmlspecialchars($user['username']) ?></span></p><br>
      <p><b>Email:</b> <span><?= htmlspecialchars($user['email']) ?></span></p><br>
      <p><b>Date of Birth:</b> <span><?= htmlspecialchars($user['dob']) ?></span></p><br><br>
      <p><b>Profile Picture</b><br><br>
        <img src="../<?= htmlspecialchars($avatarPath) ?>" width="180" height="180" style="border-radius:50%">
      </p>
    </div>

    <!-- Edit Profile -->
    <div class="card section" id="edit" style="display:none;">
      <h3>Edit Profile</h3>
      <form action="../controller/updateProfile.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
        
        <div class="grid-2">
          <div>
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>">
          </div>
          <div>
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>">
          </div>
          <div>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
          </div>
          <div>
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter new password">
          </div>
          <div>
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($user['dob']) ?>">
          </div>
        </div>

        <div class="avatar-editor">
          <label>Profile Picture</label><br>
          <img id="avatarPreview" src="../<?= htmlspecialchars($avatarPath) ?>" 
               width="120" height="120" style="border-radius:50%"><br><br>
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
