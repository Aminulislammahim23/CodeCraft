<?php
session_start();
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CodeCraft</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
                <li><h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1></li>
                <li><a href="home.php">Home</a></li>
                <li><a href="../view/enrollment.php">Enroll</a></li>
                <li><a href="../view/progress.php">Progress</a></li>
                <li><a href="../view/forum.php">Forums</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <a href="#" onclick="showSection('dashboard')">📊 Dashboard</a>
            <a href="#" onclick="showSection('students')">👨‍🎓 All Students</a>
            <a href="#" onclick="showSection('instructors')">👨‍🏫 Instructors</a>
            <a href="#" onclick="showSection('progress')">📈 Student Progress</a>
            <a href="#" onclick="showSection('certificates')">🏅 Certificate Generation</a>
            <a href="#" onclick="showSection('enrollment')">📋 Enrollment Count</a>
            <a href="#" onclick="showSection('profile')">⚙ Profile</a>
        </div>

        <div class="main-content">
            <div class="card section" id="dashboard">
                <h3>Dashboard Overview</h3>
                <div class="stats">
                    <div class="stat-box">Total Courses: <b>5</b></div>
                    <div class="stat-box">Total Students: <b>120</b></div>
                    <div class="stat-box">Certificates Issued: <b>45</b></div>
                    <div class="stat-box">Total Enrollments: <b>150</b></div>
                </div>
            </div>

            <div class="card section" id="students" style="display:none;">
                <h3>All Students</h3>
                <table>
                    <thead>
                        <tr><th>Name</th><th>Email</th><th>Registered Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="studentsTable">
                        <tr><td>Aminul</td><td>aminul@example.com</td><td>2025-08-01</td><td><button onclick="editStudent(this)">Edit</button> <button onclick="deleteStudent(this)">Delete</button></td></tr>
                        <tr><td>Mahim</td><td>mahim@example.com</td><td>2025-08-15</td><td><button onclick="editStudent(this)">Edit</button> <button onclick="deleteStudent(this)">Delete</button></td></tr>
                    </tbody>
                </table>
                <button onclick="addStudent()">+ Add Student</button>
            </div>

            <div class="card section" id="instructors" style="display:none;">
                <h3>Instructors</h3>
                <table>
                    <thead>
                        <tr><th>Name</th><th>Email</th><th>Assigned Courses</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="instructorsTable">
                        <tr><td>John Doe</td><td>john@example.com</td><td>JavaScript, Python</td><td><button onclick="editInstructor(this)">Edit</button> <button onclick="deleteInstructor(this)">Delete</button></td></tr>
                        <tr><td>Jane Smith</td><td>jane@example.com</td><td>SQL, C#</td><td><button onclick="editInstructor(this)">Edit</button> <button onclick="deleteInstructor(this)">Delete</button></td></tr>
                    </tbody>
                </table>
                <button onclick="addInstructor()">+ Add Instructor</button>
            </div>

            <div class="card section" id="progress" style="display:none;">
                <h3>Student Progress</h3>
                <table>
                    <thead>
                        <tr><th>Name</th><th>Course</th><th>Progress</th><th>Score</th><th>Status</th></tr>
                    </thead>
                    <tbody id="progressTable">
                        <tr><td>Aminul</td><td>JavaScript Basics</td><td>80%</td><td>75/100</td><td>Completed</td></tr>
                        <tr><td>Mahim</td><td>Python for Beginners</td><td>50%</td><td>45/100</td><td>In Progress</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="card section" id="certificates" style="display:none;">
                <h3>Certificate Generation</h3>
                <div class="form-group">
                    <label for="studentSelect">Select Student:</label>
                    <select id="studentSelect">
                        <option value="">-- Select Student --</option>
                        <option value="1">Aminul</option>
                        <option value="2">Mahim</option>
                    </select>
                    <label for="courseSelect">Select Course:</label>
                    <select id="courseSelect">
                        <option value="">-- Select Course --</option>
                        <option value="js">JavaScript Basics</option>
                        <option value="python">Python for Beginners</option>
                    </select>
                    <button onclick="generateCertificate()">Generate Certificate</button>
                </div>
                <div id="certificatePreview" style="display:none;">
                    <h4>Certificate Preview</h4>
                    <p>Certificate for: <span id="certStudentName"></span></p>
                    <p>Course: <span id="certCourseName"></span></p>
                    <p>Issued on: <span id="certIssueDate"><?php echo date('Y-m-d'); ?></span></p>
                    <button onclick="downloadCertificate()">Download PDF</button>
                </div>
            </div>

            <div class="card section" id="enrollment" style="display:none;">
                <h3>Enrollment Student Count</h3>
                <div class="enrollment-stats">
                    <div class="stat-box">JavaScript Basics: <b>40</b></div>
                    <div class="stat-box">Python for Beginners: <b>50</b></div>
                    <div class="stat-box">SQL for Beginners: <b>30</b></div>
                    <div class="stat-box">C# for Beginners: <b>30</b></div>
                </div>
            </div>

            <div class="card section" id="profile" style="display:none;">
                <h3>Profile Settings</h3>
                <p>Name: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p>Email: <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)</p>
                <button onclick="editProfile()">Edit Profile</button>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
