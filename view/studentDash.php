<?php
session_start();
if (!isset($_COOKIE['status'])) {
    header('location: ../view/login.php?error=badrequest');
}
?>

<?php
          require_once('../controller/auth.php');
          checkRole(['student']);   // only student can access



    require_once('../model/studentDashMod.php');
    $dashboard = [
    'totalCourses'      => getTotalCourses(),
    'certificatesIssued'=> getCertificatesIssued()
];

require_once('../model/userMod.php');
$id = $_SESSION['user_id'];
    $userbyID = getUserById($id);
 $avatarPath = !empty($userbyID['avatar']) ? $userbyID['avatar'] : "assets/uploads/imgP/default.jpg";


$activities = [
    ['day' => 'Mon', 'hours' => 2],
    ['day' => 'Tue', 'hours' => 1.5],
    ['day' => 'Wed', 'hours' => 2.5],
    ['day' => 'Thu', 'hours' => 1],
    ['day' => 'Fri', 'hours' => 2],
    ['day' => 'Sat', 'hours' => 0.5],
    ['day' => 'Sun', 'hours' => 1],
];

$skills = [
    ['name' => 'Python Programming', 'progress' => 75],
    ['name' => 'Web Development', 'progress' => 100],
    ['name' => 'JavaScript', 'progress' => 25],
    ['name' => 'React Development', 'progress' => 10],
];

$goals = [
    ['title' => 'Complete Python Course', 'progress' => 75, 'remaining' => 4, 'color' => '#d1fae5'],
    ['title' => 'Learn JavaScript Basics', 'progress' => 25, 'remaining' => 9, 'color' => '#bfdbfe'],
    ['title' => 'Master React Framework', 'progress' => 10, 'remaining' => 13, 'color' => '#e5e7eb']
];




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/stDash.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h2>🎓 Student Dashboard</h2>
        <nav>
            <ul>
                <li><a href="student.php" class="active">Dashboard</a></li>
                <li><a href="../view/courseCatalog.php">Course</a></li>
                <li><a href="profile.php">Manage Profile</a></li>
                <li><a href="../controller/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
        <img src="../<?= htmlspecialchars($avatarPath) ?>" 
         alt="Profile Picture" 
         width="60" height="60" 
         style="border-radius: 50%; object-fit: cover; border: 2px solid #1e3a8a;">
        <h1 style="margin: 0; color: #000103ff;">
        Welcome Back, 
        <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Student'; ?>🔥
       </h1>
       </div>


        <div class="cards">
            <div class="card">
                <h4>📊 Courses Enrolled </h4>
                <p id=""><?= $dashboard['totalCourses']; ?></p>
            </div>

            <div class="card">
                <h4>✅ Courses Completed</h4>
                <p><?= $dashboard['certificatesIssued']; ?></p>
            </div>

            <div class="card">
                <h4>🏆 Certificates Earned</h4>
                <p><?= $dashboard['certificatesIssued']; ?></p>
            </div>

             <div class="card">
                <h4>🔥Learning Streak </h4>
                <p><?= $dashboard['certificatesIssued']; ?></p> 
            </div>

            <div class="long-card">
                <h1> Continue Learning </h1>
                <ul id="quizHistory">
                    <li>Python Fundamentals</li>
                    <li>Lesson 5: Functions and Modules</li>
                    <li>6/10 lessons completed 60% complete</li>
                </ul>
            </div>

            <div class="long-card">
                <section class="progress-container">
                <h1> Learning Progress </h1>
                <h4> This Week's Activity <h4>
                <canvas id="activityChart"></canvas>

                <div class="cards">
                <div class="card">
                <h3>Skills Development</h3>
                <?php foreach ($skills as $s): ?>
                    <p><strong><?= $s['name']; ?></strong> (<?= $s['progress']; ?>%)</p>
                    <div class="bar">
                        <div class="bar-fill" style="width: <?= $s['progress']; ?>%; background: <?= $s['progress']==100?'#16a34a':'#2563eb'; ?>"></div>
                    </div>
                <?php endforeach; ?>
                </div>

                <div class="long-card">
                 <h3>Learning Goals</h3>
                <?php foreach ($goals as $g): ?>
                    <div style="background: <?= $g['color']; ?>; padding: 12px; border-radius: 10px; margin-bottom: 12px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <strong><?= $g['title']; ?></strong>
                            <span><?= $g['progress']; ?>%</span>
                        </div>
                        <div class="bar">
                            <div class="bar-fill" style="width: <?= $g['progress']; ?>%; background:#16a34a;"></div>
                        </div>
                        <small><?= $g['remaining']; ?> lessons remaining</small>
                    </div>
                <?php endforeach; ?>
                </div>

                </div>
                </section>
            </div>

            <div class="long-card">
                <h1> Recent Achievements </h1>
                <div class="cards">
                    <div class="card">
                    <h4>🏆 First Certificate</h4>
                    <p><?= $dashboard['certificatesIssued']; ?>-Day Streak</p>
                    <p><?= $dashboard['certificatesIssued']; ?> Days ago</p>
                    </div>

                    <div class="card">
                    <h4>🔥 First Certificate</h4>
                    <p><?= $dashboard['certificatesIssued']; ?>-Day Streak</p>
                    <p>Consistent daily learning</p>
                    <p><?= $dashboard['certificatesIssued']; ?></p>
                    </div>

                    <div class="card">
                    <h4>📚 First Certificate</h4>
                    <p>Completed <?= $dashboard['certificatesIssued']; ?> lessons this week</p>
                    <p><?= $dashboard['certificatesIssued']; ?> Days ago</p>
                    </div>
                </div>
            </div>

            

            

            <!-- <div class="card">
                <h4>🚀 Quick Actions</h4>
                <button onclick="window.location.href='../view/courseQZ.php'">Start a New Quiz</button>
                <button onclick="window.location.href='../view/progress.html'">View Progress</button>
                <button onclick="window.location.href='../view/enrollment.php'">Enroll in a Course</button>
                <button onclick="window.location.href='../view/forum.html'">Visit Forum</button>
                <button onclick="window.location.href='../view/faq.html'">View FAQs</button>
            </div> -->

        </div>
    </main>

    <footer>
        <p>© 2025 CodeCraft Student Dashboard</p>
    </footer>

    <script>
        const ctx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($activities, 'day')); ?>,
                datasets: [{
                    label: 'Hours Studied',
                    data: <?= json_encode(array_column($activities, 'hours')); ?>,
                    backgroundColor: 'rgba(37, 99, 235, 0.8)',
                    borderRadius: 6
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Hours' } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>

    <script src="../assets/js/dashboard.js"></script> 
    
</body>
</html>
