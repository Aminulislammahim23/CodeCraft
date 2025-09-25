<?php
    session_start();
    if(!isset($_COOKIE['status'])){
        header('location: login.php?error=badrequest');
    }
   
    require_once('../controller/auth.php');
    checkRole(['admin']);   // only admin can access

    require_once('../model/userMod.php');
    $users = getAllUser();
    $instructors = getAllInstructors();
    $students = getAllStudents();

    require_once('../model/courseMod.php');  // course model
    $courses = getAllCourses();

    require_once('../model/AdminDashMod.php');
    $dashboard = [
    'totalCourses'      => getTotalCourses(),
    'totalStudents'     => getTotalStudents(),
    'totalInstructors'  => getTotalInstructors(),
    'certificatesIssued'=> getCertificatesIssued()
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/adminDash.css">
</head>
<body>
  <header>
        <div class="logo">CodeCraft
          <h1>welcome Admin! <?=$_SESSION['username']?></h1>
        </div>
        <nav>
            <ul>
                <li></li>
                <li><a href="../view/adminDash.php">Home</a></li>
                <li><a href="../view/forum.html">Forums</a></li>
                <li><a href='../controller/logout.php'>logout </a></li>
            </ul>
        </nav>  
    </header>
    <div class="sidebar">
    <h2>Admin Dashboard</h2>
    <a href="#" onclick="showSection('dashboard')">📊 Dashboard</a>
    <a href="#" onclick="showSection('courses')">📚 Manage Courses</a>
    <a href="#" onclick="showSection('instructor')">📚 Manage Instructors</a>
    <a href="#" onclick="showSection('students')">👨‍🎓 Students</a>
    <a href="#" onclick="showSection('certificates')">🏅 Certificates</a>
    <a href="#" onclick="showSection('messages')">💬 Messages</a>
    <a href="profile.php">⚙ Profile</a>
    <a href="#" onclick="showSection('export')">📥 Export</a>
  </div>
  <div class="main">
 
    <div class="card section" id="dashboard">
      <h3>Dashboard Overview</h3>
      <p><strong>Total Courses:</strong> <?= $dashboard['totalCourses']; ?></p>
      <p><strong>Total Students:</strong> <?= $dashboard['totalStudents']; ?></p>
      <p><strong>Total Instructors:</strong> <?= $dashboard['totalInstructors']; ?></p>
        <p><strong>Certificates Issued:</strong> <?= $dashboard['certificatesIssued']; ?></p>
    </div>

    

    <div class="card section" id="courses" style="display:none;">
      <h3>Manage Courses</h3>
      <button onclick="addCourse()">+ Add New Course</button>
      <table border="1">
            <tr>
                <td>ID</td>
                <td>Title</td>
                <td>Price</td>
                <td>ACTION</td>
            </tr>
        <?php  foreach($courses as $c){ ?>
            <tr>
                <td><?php echo $c['course_id']; ?> </td>
                <td><?=$c['title'] ?> </td>
                <td><?=$c['price'] ?></td>
                <td>
                <a href='editCourse.php?id=<?=$c['course_id']?>'>EDIT </a>  |
                <a href='detailsCourse.php?id=<?=$c['course_id']?>'>DETAILS </a>  |
                <a href='deleteCourse.php?id=<?=$c['course_id']?>'>DELETE </a> 
                </td>
            </tr>

        <?php } ?>

        </table>
    </div>

<div class="card section" id="instructor" style="display:none;">
      <h3>Manage Instructors</h3>
      <button onclick="addInstructor()">+ Add New Instructor</button>
      <table border="1">
            <tr>
                <td>ID</td>
                <td>USERNAME</td>
                <td>EMAIL</td>
                <td>ACTION</td>
            </tr>
        <?php  foreach($instructors as $i){ ?>
            <tr>
                <td><?php echo $i['id']; ?> </td>
                <td><?=$i['username'] ?> </td>
                <td><?=$i['email'] ?></td>
                <td>
                    <a href='editUser.php?id=<?=$i['id']?>'>EDIT </a> |
                    <a href='detailsUser.php'>DETAILS </a> |
                    <a href='deleteUser.php'>DELETE </a> 
                </td>
            </tr>

        <?php } ?>

        </table>
    </div>


    <div class="card section" id="students" style="display:none;">   
      <h3>Enrolled Students</h3>
      <table border="1">
            <tr>
                <td>ID</td>
                <td>USERNAME</td>
                <td>EMAIL</td>
                <td>Course</td>
                <td>ACTION</td>
            </tr>
        <?php  foreach($students as $r){ ?>
            <tr>
                <td><?php echo $r['id']; ?> </td>
                <td><?=$r['username'] ?> </td>
                <td><?=$r['email'] ?></td>
                <td><?=$r['password'] ?></td>
                <td>
                    <a href='editUser.php?id=<?=$r['id']?>'>EDIT </a> |
                    <a href='detailsUser.php'>DETAILS </a> |
                    <a href='deleteUser.php'>DELETE </a> 
                </td>
            </tr>

        <?php } ?>

        </table>
    </div>


    <div class="card section" id="certificates" style="display:none;">
      <h3>Certificate Management</h3>
      <p> No issued certificates</p>
      <button onclick="alert('Certificate Issued!')">Issue Certificate</button>
    </div>
    <script src="../assets/js/adminDash.js"></script>
</body>
</html>