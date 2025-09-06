<?php
session_start();
if (!isset($_COOKIE['status']) || !isset($_SESSION['username'])) {
    header('location: login.php?error=badrequest');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Profiles & Student Progress - CodeCraft</title>
    <link rel="stylesheet" href="../assets/css/instructor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo"><span class="brand">⚡ CodeCraft</span></div>
        <nav>
            <ul>
                <li><a href="home.php"><i class="fa-solid fa-book-open"></i> Courses</a></li>
                <li><a href="forum.php"><i class="fa-solid fa-comments"></i> Forum</a></li>
                <li><a href="progress.php"><i class="fa-solid fa-chart-line"></i> Progress</a></li>
                <li><a href="account.php"><i class="fa-solid fa-user"></i> Account</a></li>
                <li><a href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Instructor Profiles</h1>

        <div id="instructors-container" class="instructors-container">
            <?php
            // Sample instructors array (replace with database query later)
            $instructors = [
                [
                    "name" => "Alice Johnson",
                    "photo" => "https://i.pravatar.cc/150?img=1",
                    "bio" => "Alice has 10 years of experience teaching Web Development. She specializes in HTML, CSS, and JavaScript.",
                    "credentials" => ["PhD in Computer Science", "Certified Web Developer"],
                    "courses" => ["HTML Basics", "CSS Fundamentals", "JavaScript Essentials"]
                ],
                [
                    "name" => "Bob Smith",
                    "photo" => "https://i.pravatar.cc/150?img=2",
                    "bio" => "Bob is a full-stack developer and instructor with a passion for teaching C++ and Python.",
                    "credentials" => ["MSc in Software Engineering", "C++ Expert"],
                    "courses" => ["C++ Fundamentals", "Python Programming"]
                ],
                [
                    "name" => "Clara Evans",
                    "photo" => "https://i.pravatar.cc/150?img=3",
                    "bio" => "Clara is a data scientist with expertise in Machine Learning, AI, and Data Visualization.",
                    "credentials" => ["PhD in AI", "Machine Learning Expert"],
                    "courses" => ["Introduction to AI", "Machine Learning", "Data Visualization"]
                ],
                [
                    "name" => "David Lee",
                    "photo" => "https://i.pravatar.cc/150?img=4",
                    "bio" => "David is a cloud architect and backend developer who teaches cloud computing and DevOps practices.",
                    "credentials" => ["AWS Certified", "DevOps Specialist"],
                    "courses" => ["Cloud Computing Fundamentals", "DevOps with Docker & Kubernetes", "Backend Development"]
                ]
            ];

            foreach ($instructors as $index => $instructor) {
                echo "<div class='instructor-card'>";
                echo "<img src='{$instructor['photo']}' alt='{$instructor['name']}' class='instructor-photo'>";
                echo "<div class='instructor-info'>";
                echo "<h2>" . htmlspecialchars($instructor['name']) . "</h2>";
                echo "<p>" . htmlspecialchars($instructor['bio']) . "</p>";
                echo "<div class='badges'>" . implode('', array_map(fn($cred) => "<span>" . htmlspecialchars($cred) . "</span>", $instructor['credentials'])) . "</div>";
                echo "<h4>Courses by {$instructor['name']}:</h4>";
                echo "<ul class='courses'>" . implode('', array_map(fn($course) => "<li>" . htmlspecialchars($course) . "</li>", $instructor['courses'])) . "</ul>";
                echo "<button onclick=\"messageInstructor($index)\">Message Instructor</button>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>

        <h1>Student Progress</h1>
        <div class="progress-section">
            <select id="courseFilter" onchange="filterProgress()">
                <option value="">All Courses</option>
                <?php
                $allCourses = array_merge(...array_column($instructors, 'courses'));
                $uniqueCourses = array_unique($allCourses);
                foreach ($uniqueCourses as $course) {
                    echo "<option value='" . htmlspecialchars($course) . "'>" . htmlspecialchars($course) . "</option>";
                }
                ?>
            </select>

            <table id="progressTable">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Progress</th>
                        <th>Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Sample student progress data (replace with database query later)
                    $studentProgress = [
                        ["name" => "Aminul", "course" => "HTML Basics", "progress" => "80%", "score" => "75/100", "status" => "Completed"],
                        ["name" => "Mahim", "course" => "Python Programming", "progress" => "50%", "score" => "45/100", "status" => "In Progress"],
                        ["name" => "Sara", "course" => "Introduction to AI", "progress" => "90%", "score" => "85/100", "status" => "Completed"],
                        ["name" => "Tom", "course" => "DevOps with Docker & Kubernetes", "progress" => "30%", "score" => "25/100", "status" => "In Progress"]
                    ];

                    foreach ($studentProgress as $progress) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($progress['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($progress['course']) . "</td>";
                        echo "<td>" . htmlspecialchars($progress['progress']) . "</td>";
                        echo "<td>" . htmlspecialchars($progress['score']) . "</td>";
                        echo "<td>" . htmlspecialchars($progress['status']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="../assets/js/instructor.js"></script>
    <script>
        function filterProgress() {
            const filter = document.getElementById('courseFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#progressTable tbody tr');

            rows.forEach(row => {
                const course = row.cells[1].textContent.toLowerCase();
                row.style.display = filter === '' || course.includes(filter) ? '' : 'none';
            });
        }

        // Ensure the initial filter is applied
        document.addEventListener('DOMContentLoaded', filterProgress);
    </script>
</body>
</html>
