<?php
    session_start();
    if(!isset($_COOKIE['status'])){
        header('location: login.php?error=badrequest');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
                <li><h1>welcome home! <?=$_SESSION['username']?></h1></li>
                <li><a href="index.html">Home</a></li>
                <li><a href="../view/enrollment.html">Enroll</a></li>
                <li><a href="../view/progress.html">Progress</a></li>
                <li><a href="../view/forum.html">Forums</a></li>
                <li><a href='logout.php'>logout </a></li>
            </ul>
        </nav>  
    </header>

  <div class="sidebar">
    <h2>Instructor</h2>
    <a href="#" onclick="showSection('dashboard')">📊 Dashboard</a>
    <a href="#" onclick="showSection('courses')">📚 Manage Courses</a>
    <a href="#" onclick="showSection('students')">👨‍🎓 Students</a>
    <a href="#" onclick="showSection('certificates')">🏅 Certificates</a>
    <a href="#" onclick="showSection('messages')">💬 Messages</a>
    <a href="#" onclick="showSection('profile')">⚙ Profile</a>
  </div>


  <div class="main">
 
    <div class="card section" id="dashboard">
      <h3>Dashboard Overview</h3>
      <p>Total Courses: <b>5</b></p>
      <p>Total Students: <b>120</b></p>
      <p>Certificates Issued: <b>45</b></p>
    </div>

 
    <div class="card section" id="courses" style="display:none;">
      <h3>Manage Courses</h3>
      <button onclick="addCourse()">+ Add New Course</button>
      <table>
        <tr><th>Course Name</th><th>Enrolled</th><th>Actions</th></tr>
        <tr><td>JavaScript Basics</td><td>40</td><td><button>Edit</button> <button>Delete</button></td></tr>
        <tr><td>Python for Beginners</td><td>50</td><td><button>Edit</button> <button>Delete</button></td></tr>
      </table>
    </div>


    <div class="card section" id="students" style="display:none;">
      <h3>Enrolled Students</h3>
      <table>
        <tr><th>Name</th><th>Course</th><th>Status</th></tr>
        <tr><td>Aminul</td><td>JavaScript Basics</td><td>Completed</td></tr>
        <tr><td>Mahim</td><td>Python for Beginners</td><td>In Progress</td></tr>
      </table>
    </div>


    <div class="card section" id="certificates" style="display:none;">
      <h3>Certificate Management</h3>
      <p>Issue and manage course completion certificates.</p>
      <button onclick="alert('Certificate Issued!')">Issue Certificate</button>
    </div>


    <div class="card section" id="messages" style="display:none;">
      <h3>Messages</h3>
      <p>No new messages.</p>
    </div>


    <div class="card section" id="profile" style="display:none;">
      <h3>Profile Settings</h3>
      <p>Name: Instructor Name</p>
      <p>Email: instructor@email.com</p>
      <button>Edit Profile</button>
    </div>
  </div>

  <script src="../assets/js/admin.js"></script>
</body>
</html>
