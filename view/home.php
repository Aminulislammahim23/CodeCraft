<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_COOKIE['status']) || $_COOKIE['status'] !== 'true') {
    header("Location: login.php");
    exit();
}

require_once '../model/mydb.php';
$user = getUserByEmail($_SESSION['username']);
$enrolledCourses = getEnrolledCourses($_SESSION['username']);
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka')); // +06 timezone
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <header>
        <h2>🎓 Student Dashboard</h2>
        <nav>
            <ul>
                <li><a href="home.php" class="active">Dashboard</a></li>
                <li><a href="../view/enrollment.php">Enrollment</a></li>
                <li><a href="../view/forum.html">Forum</a></li>
                <li><a href="../view/faq.html">FAQ</a></li>
                <li><a href="../controllers/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard">
        <h3>Welcome Back, <?php echo htmlspecialchars($user['name']); ?>!</h3>
        <p>Hello <a href="#" onclick="showUpdateProfile()"><?php echo htmlspecialchars($user['email']); ?></a>! | Last Updated: <?php echo $dateTime->format('Y-m-d H:i:s T'); ?></p>

        <div class="profile-section">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>

      
        <div class="enrolled-courses">
            <h4>Enrolled Courses</h4>
            <?php if (empty($enrolledCourses)): ?>
                <p>No courses enrolled yet. <a href="../view/enrollment.php">Enroll now!</a></p>
            <?php else: ?>
                <ul>
                    <?php foreach ($enrolledCourses as $course): ?>
                        <li>
                            <?php echo htmlspecialchars($course['name']); ?> (Enrolled on: <?php echo htmlspecialchars($course['enrollment_date']); ?>, Payment: <?php echo htmlspecialchars($course['payment_method']); ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>© 2025 CodeCraft Student Dashboard</p>
    </footer>

    <div id="overlay" onclick="hideUpdateProfile()"></div>
    <div id="profileUpdate">
        <h4>Update Profile</h4>
        <input type="text" id="updateName" placeholder="New Name" value="<?php echo htmlspecialchars($user['name']); ?>">
        <input type="email" id="updateEmail" placeholder="New Email" value="<?php echo htmlspecialchars($user['email']); ?>">
        <input type="password" id="updatePassword" placeholder="New Password">
        <button onclick="updateProfile()">Save Changes</button>
        <button onclick="hideUpdateProfile()">Cancel</button>
    </div>

    <script>
        function showUpdateProfile() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('profileUpdate').style.display = 'block';
        }

        function hideUpdateProfile() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('profileUpdate').style.display = 'none';
        }

        function updateProfile() {
            const newName = document.getElementById('updateName').value;
            const newEmail = document.getElementById('updateEmail').value;
            const newPassword = document.getElementById('updatePassword').value;

            if (!newName || !newEmail || (!newPassword && newPassword.length < 8)) {
                alert('Please fill all fields correctly. Password must be at least 8 characters if provided.');
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../controllers/update_profile.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === 'success') {
                        alert('Profile updated successfully!');
                        location.reload(); 
                    } else {
                        alert('Update failed: ' + xhr.responseText);
                    }
                }
            };
            xhr.send('name=' + encodeURIComponent(newName) + '&email=' + encodeURIComponent(newEmail) + '&password=' + encodeURIComponent(newPassword));
            hideUpdateProfile();
        }
    </script>
</body>
</html>