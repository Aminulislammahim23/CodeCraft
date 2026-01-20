<?php
session_start();
require_once '../../models/courseModel.php';
require_once '../../models/userModel.php';

function getAvatarPath($avatarFilename)
{
    $avatar = $avatarFilename ?? 'default.png';
    return "../../assets/uploads/users/avatars/" . htmlspecialchars($avatar);
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
    header("Location: ../auth/login.php");
    exit();
}

$instructorId = $_SESSION['user_id'];
$totalCourses = countCoursesByInstructor($instructorId);
$totalEnrollments = countEnrollmentsByInstructor($instructorId);
$totalStudents = countStudentsByInstructor($instructorId);
$totalRevenue = countRevenueByInstructor($instructorId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/instructor.css">
</head>
<body data-user-id="<?= $_SESSION['user_id']; ?>">
    <?php if (isset($_GET['success'])): 
    $message = '';
    $icon = '';
    switch($_GET['success']) {
        case 'course_added':
            $message = 'Course added successfully!';
            $icon = '✅';
            break;
        case 'course_updated':
            $message = 'Course updated successfully!';
            $icon = '✏️';
            break;
        case 'course_deleted':
            $message = 'Course deleted successfully!';
            $icon = '🗑️';
            break;
        default:
            $message = 'Operation completed successfully!';
            $icon = '✅';
    }
    ?>
    <div id="successMessage" style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); z-index: 1000;">
        <?= $icon ?> <?= htmlspecialchars($message) ?>
    </div>
    <script>
        setTimeout(function() {
            const msg = document.getElementById('successMessage');
            if (msg) {
                msg.style.opacity = '0';
                msg.style.transition = 'opacity 0.5s';
                setTimeout(() => msg.remove(), 500);
            }
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, document.title, url);
        }, 3000);
    </script>
    <?php endif; ?>
    <div class="admin-container">
        
        <aside class="sidebar">
            <img src="../../assets/img/logo.png" class="brand-logo">
            <h2 class="logo">Instructor</h2>
            <ul class="menu">
                <li class="active">
                    <a href="#" onclick="showSection('dashboard')">📊 Dashboard</a>
                </li>
                <li>
                    <a href="#" onclick="showSection('courses')">📚 Courses</a>
                </li>
                <li>
                    <a href="#" onclick="showSection('lessons')">📖 Lessons</a>
                </li>
                <li>
                    <a href="#" onclick="showSection('enrollments')">📦 Enrollments</a>
                </li>
                <li>
                    <a href="#" onclick="showSection('settings')">⚙️ Settings</a>
                </li>
                <li>
                    <a href="../../controllers/logout.php" onclick="showSection('Logout')">🚪 Logout</a>
                </li>
            </ul>
        </aside>
        <main class="main">
            
            <header class="topbar">
                <h1>Dashboard</h1>
                <div class="admin-info">
                    <img src="<?= getAvatarPath($_SESSION['avatar'] ?? null); ?>" 
                         alt="<?= htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?> Avatar" 
                         class="user-avatar"
                         onerror="this.onerror=null; this.src='<?= getAvatarPath('default.png'); ?>';">
                    <span><?= htmlspecialchars($_SESSION['full_name'] ?? 'Instructor'); ?></span>
                </div>
            </header>
            
            <div class="cards section" id="dashboardSection">
                <div class="card">
                    <h3>My Total Courses</h3>
                    <p><?= $totalCourses ?? 0; ?></p>
                </div>
                <div class="card">
                    <h3>My Total Enrollments</h3>
                    <p><?= $totalEnrollments ?? 0; ?></p>
                </div>
                <div class="card">
                    <h3>My Total Students</h3>
                    <p><?= $totalStudents ?? 0; ?></p>
                </div>
                <div class="card">
                    <h3>My Total Revenue</h3>
                    <p>$ <?= $totalRevenue ?? 0; ?></p>
                </div>  
            </div>
            
            <div class="table-section section" id="courseSection">
                <h2>My Courses</h2>
                <div class="btn-container">
                    <button class="button" onclick="showSection('addcourses')">📚 Add Course</button>
                    <button class="button" onclick="showSection('updatecoursesSection')">✏️ Update Course Information</button>
                    <button class="button" onclick="showSection('deletecoursesSection')">🗑️ Delete Course</button>
                </div><br><br>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Course Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Difficulty</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Rating</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $courses = getCoursesByInstructor($instructorId);
                    if (!empty($courses)):
                        foreach ($courses as $course):
                            $category_name = 'N/A';
                            $categories = getCategories();
                            foreach ($categories as $category):
                                if ($category['id'] == $course['category_id']):
                                    $category_name = $category['name'];
                                    break;
                                endif;
                            endforeach;
                            $difficultyColor = match($course['difficulty']) {
                                'Beginner' => '#28a745',
                                'Intermediate' => '#ffc107',
                                'Advanced' => '#dc3545',
                                default => '#6c757d'
                            };
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($course['id']); ?></td>
                            <td>
                                <img src="../../assets/images/courses/<?= htmlspecialchars($course['course_image'] ?? 'default.png'); ?>" 
                                     width="60" 
                                     height="40" 
                                     style="border-radius: 5px; object-fit: cover;"
                                     alt="<?= htmlspecialchars($course['title']); ?>" 
                                     onerror="this.src='../../assets/images/courses/default.png';">
                            </td>
                            <td><strong><?= htmlspecialchars($course['title']); ?></strong></td>
                            <td><?= htmlspecialchars($category_name); ?></td>
                            <td>
                                <span style="padding: 5px 10px; background: <?= $difficultyColor; ?>; color: white; border-radius: 5px; font-size: 12px;">
                                    <?= htmlspecialchars($course['difficulty']); ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($course['duration']); ?></td>
                            <td><strong>$<?= number_format($course['price'], 2); ?></strong></td>
                            <td>
                                <span style="color: #ffc107;">⭐</span> 
                                <?= number_format($course['rating'], 1); ?>/5
                            </td>
                            <td><?= isset($course['created_at']) ? date('Y-m-d', strtotime($course['created_at'])) : 'N/A'; ?></td>
                        </tr>
                    <?php
                        endforeach;
                    else:
                    ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px; color: #999;">No courses found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="table-section section" id="lessonsSection">
                <h2>My Lessons</h2>
                <div class="btn-container">
                    <button class="button" onclick="showSection('addlessonSection')">➕ Add Lesson</button>
                </div><br><br>
                <table border="1" cellpadding="10">
                    <tr>
                        <th>ID</th>
                        <th>Course</th>
                        <th>Title</th>
                        <th>Video URL</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                <?php
                $lessons = [];
                $courses = getCoursesByInstructor($instructorId);
                foreach($courses as $course) {
                    $courseLessons = getLessonsByCourseId($course['id']);
                    foreach($courseLessons as $lesson) {
                        $lesson['course_title'] = $course['title'];
                        $lessons[] = $lesson;
                    }
                }
                if (!empty($lessons)):
                    foreach ($lessons as $lesson):
                ?>
                <tr>
                    <td><?= htmlspecialchars($lesson['id']); ?></td>
                    <td><?= htmlspecialchars($lesson['course_title']); ?></td>
                    <td><?= htmlspecialchars($lesson['title']); ?></td>
                    <td><?= htmlspecialchars($lesson['video_url']); ?></td>
                    <td><?= htmlspecialchars($lesson['lesson_order']); ?></td>
                    <td>
                        <a href="#" onclick="editLesson(<?= $lesson['id']; ?>)">Edit</a> |
                        <a href="#" onclick="deleteLesson(<?= $lesson['id']; ?>)">Delete</a>
                    </td>
                </tr>
                <?php
                    endforeach;
                else:
                ?>
                <tr>
                    <td colspan="6">No lessons found.</td>
                </tr>
                <?php endif; ?>
                </table>                
            </div>
            
            <div class="table-section section" id="enrollmentsSection">
                <h2>My Enrollments</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Enrollment Date</th>
                            <th>Enrollment Status</th>
                            <th>Enrollment Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Enrollment 1</td>
                            <td>Enrollment 1 Status</td>
                            <td>Enrollment 1 Actions</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="table-section section" id="studentsSection">
                <h2>My Students</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Student Email</th>
                            <th>Student Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Student 1</td>
                            <td>Student 1 Email</td>
                            <td>Student 1 Actions</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="table-section section" id="addcoursesSection">
                <h2>Add New Course</h2>
                <form action="../../controllers/courseController/addCourse.php" method="POST" enctype="multipart/form-data">
                    <label for="course_title">Course Title *</label>
                    <input type="text" class="txtStyle" id="course_title" name="title" placeholder="Enter course title" required><br><br>
                    <label for="course_description">Description</label>
                    <textarea class="txtStyle" id="course_description" name="description" rows="4" placeholder="Enter course description..."></textarea><br><br>
                    <label for="category_id">Category *</label>
                    <select class="txtStyle" id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                    <?php
                        $categories = getCategories();
                        foreach ($categories as $category):
                    ?>
                        <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                    </select><br><br>
                    <label for="difficulty">Difficulty *</label>
                    <select class="txtStyle" id="difficulty" name="difficulty" required>
                        <option value="">-- Select Difficulty --</option>
                        <option value="Beginner">🌱 Beginner</option>
                        <option value="Intermediate">🌿 Intermediate</option>
                        <option value="Advanced">🌳 Advanced</option>
                    </select><br><br>
                    <label for="duration">Duration *</label>
                    <input type="text" class="txtStyle" id="duration" name="duration" placeholder="e.g., 4 weeks, 2 months" required><br><br>
                    <label for="price">Price ($) *</label>
                    <input type="number" class="txtStyle" id="price" name="price" value="0" min="0" step="0.01" placeholder="0.00"><br><br>
                    <label for="rating">Rating (0-5)</label>
                    <input type="number" class="txtStyle" id="rating" name="rating" value="0" min="0" max="5" step="0.1" placeholder="0.0"><br><br>
                    <label for="course_image">Course Image (Optional)</label>
                    <input type="file" class="txtStyle" id="course_image" name="course_image" accept="image/jpeg,image/png,image/jpg"><br>
                    <small style="color: #666;">Accepted formats: JPG, PNG (Max: 2MB)</small><br><br>
                    <button type="submit" class="button">✅ Add Course</button>
                    <button type="button" class="button" onclick="showSection('courses')" style="background: #6c757d;">❌ Cancel</button>
                </form>
            </div>
            
            <div class="table-section section" id="updatecoursesSection">
                <h2>Update Course Information</h2>
                
                <form onsubmit="return false;">
                    <label for="searchCourse">🔍 Search Course</label>
                    <input type="text" class="txtStyle" id="searchCourse" placeholder="Search by course ID or title"><br>
                    <button type="button" class="button" id="searchCourseBtn">🔎 Search Course</button><br><br>
                </form>
                <hr style="margin: 20px 0; border: 1px solid #ddd;">
                
                <form action="../../controllers/courseController/updateCourse.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="update_course_id" name="id" value="">
                    <label for="update_course_title">Course Title *</label>
                    <input type="text" class="txtStyle" id="update_course_title" name="title" placeholder="Enter course title" required><br><br>
                    <label for="update_course_description">Description</label>
                    <textarea class="txtStyle" id="update_course_description" name="description" rows="4" placeholder="Enter course description..."></textarea><br><br>
                    <label for="update_category_id">Category *</label>
                    <select class="txtStyle" id="update_category_id" name="category_id" required>
                        <?php
                        $categories = getCategories();
                        foreach ($categories as $category):
                        ?>
                        <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select><br><br>
                    <label for="update_difficulty">Difficulty *</label>
                    <select class="txtStyle" id="update_difficulty" name="difficulty" required>
                        <option value="Beginner">🌱 Beginner</option>
                        <option value="Intermediate">🌿 Intermediate</option>
                        <option value="Advanced">🌳 Advanced</option>
                    </select><br><br>
                    <label for="update_duration">Duration *</label>
                    <input type="text" class="txtStyle" id="update_duration" name="duration" placeholder="e.g., 4 weeks" required><br><br>
                    <label for="update_price">Price ($) *</label>
                    <input type="number" class="txtStyle" id="update_price" name="price" min="0" step="0.01" placeholder="0.00"><br><br>
                    <label for="update_rating">Rating (0-5)</label>
                    <input type="number" class="txtStyle" id="update_rating" name="rating" min="0" max="5" step="0.1" placeholder="0.0"><br><br>
                    <button type="submit" class="button">✅ Update Course</button>
                    <button type="button" class="button" onclick="showSection('courses')" style="background: #6c757d;">❌ Cancel</button>
                </form>
            </div>
            
            <div class="table-section section" id="deletecoursesSection">
                <h2>Delete Course</h2>
                
                <form onsubmit="return false;">
                    <label for="searchCourseDelete">🔍 Search Course</label>
                    <input type="text" id="searchCourseDelete" class="txtStyle" placeholder="Search by course ID or title"><br>
                    <button type="button" class="button" id="searchCourseDeleteBtn">🔎 Search Course</button><br><br>
                </form>
                <hr style="margin: 20px 0; border: 1px solid #ddd;">
                
                <form action="../../controllers/courseController/deleteCourse.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone!')">
                    <input type="hidden" id="delete_course_id" name="id" value="">
                    <label for="delete_course_title">Course Title</label>
                    <input type="text" class="txtStyle" id="delete_course_title" readonly style="background: #f5f5f5;"><br><br>
                    <label for="delete_course_category">Category</label>
                    <input type="text" class="txtStyle" id="delete_course_category" readonly style="background: #f5f5f5;"><br><br>
                    <label for="delete_course_difficulty">Difficulty</label>
                    <input type="text" class="txtStyle" id="delete_course_difficulty" readonly style="background: #f5f5f5;"><br><br>
                    <label for="delete_course_price">Price</label>
                    <input type="text" class="txtStyle" id="delete_course_price" readonly style="background: #f5f5f5;"><br><br>
                    <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin-bottom: 20px;">
                        <strong>⚠️ Warning:</strong> Deleting this course will permanently remove it from the system. This action cannot be undone!
                    </div>
                    <button type="submit" class="button" style="background: #dc3545;">🗑️ Delete Course</button>
                    <button type="button" class="button" onclick="showSection('courses')" style="background: #6c757d;">❌ Cancel</button>
                </form>
            </div>
            
            <div class="table-section section" id="addlessonSection">
                <h2>Add Lesson</h2>
                <form id="addLessonForm">
                    <label for="course_id">Course</label>
                    <select class="txtStyle" id="course_id" name="course_id" required>
                        <option value="">Select Course</option>
                        <?php
                        $courses = getCoursesByInstructor($instructorId);
                        foreach($courses as $course) {
                            echo '<option value="'.$course['id'].'">'.htmlspecialchars($course['title']).'</option>';
                        }
                        ?>
                    </select>
                    <label for="title">Title</label>
                    <input type="text" class="txtStyle" id="title" name="title" required>
                    <label for="video_url">Video URL</label>
                    <input type="text" class="txtStyle" id="video_url" name="video_url">
                    <label for="content">Content</label>
                    <textarea class="txtStyle" id="content" name="content"></textarea>
                    <label for="lesson_order">Order</label>
                    <input type="number" class="txtStyle" id="lesson_order" name="lesson_order" min="0" value="0">
                    <button type="submit" class="button">Add Lesson</button>
                </form>
            </div>
            
            <div class="table-section section" id="logoutSection">
                <h2>Logout</h2>
                <button class="button" onclick="logoutInstructor()">Logout</button>
            </div>
        </main>
    </div>
    <script src="../../assets/js/instructor.js"></script>
</body>
</html>