<?php
session_start();

if (isset($_SESSION['status'])) {
    setcookie('status', true, time()  300, '/');
    header('location: ../views/auth/login.php');
    session_unset();
    session_destroy();
}

$successMsg = "";
$errorMsg = "";
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'user_updated') {
        $successMsg = "✅ User information updated successfully.";
    } elseif ($_GET['success'] === 'user_created') {
        $successMsg = "✅ User created successfully.";
    } elseif ($_GET['success'] === 'user_terminated') {
        $successMsg = "✅ User terminated successfully.";
    } elseif ($_GET['success'] === 'course_added') {
        $successMsg = "✅ Course added successfully.";
    } elseif ($_GET['success'] === 'course_updated') {
        $successMsg = "✅ Course updated successfully.";
    } elseif ($_GET['success'] === 'course_deleted') {
        $successMsg = "✅ Course deleted successfully.";
    }
}
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'user_not_found') {
        $errorMsg = "❌ User not found!";
    } elseif ($_GET['error'] === 'update_failed') {
        $errorMsg = "❌ Failed to update user. Please try again.";
    } elseif ($_GET['error'] === 'email_exists') {
        $errorMsg = "❌ Email already exists!";
    } elseif ($_GET['error'] === 'empty_fields') {
        $errorMsg = "❌ All fields are required!";
    } elseif ($_GET['error'] === 'registration_failed') {
        $errorMsg = "❌ Failed to create user. Please try again.";
    } elseif ($_GET['error'] === 'terminate_failed') {
        $errorMsg = "❌ Failed to terminate user. Please try again.";
    } elseif ($_GET['error'] === 'cannot_delete_self') {
        $errorMsg = "❌ You cannot delete your own account!";
    } elseif ($_GET['error'] === 'invalid_user_id') {
        $errorMsg = "❌ Invalid user ID!";
    } elseif ($_GET['error'] === 'empty_course_fields') {
        $errorMsg = "❌ Please fill all required course fields!";
    } elseif ($_GET['error'] === 'course_add_failed') {
        $errorMsg = "❌ Failed to add course. Please try again.";
    } elseif ($_GET['error'] === 'course_update_failed') {
        $errorMsg = "❌ Failed to update course. Please try again.";
    } elseif ($_GET['error'] === 'course_delete_failed') {
        $errorMsg = "❌ Failed to delete course. Please try again.";
    }
}
require_once('../../models/userModel.php');
require_once('../../models/courseModel.php');
require_once('../../models/paymentModel.php');

function getAvatarPath($avatarFilename) {
    $avatar = $avatarFilename ?? 'default.png';
    return "../../assets/uploads/users/avatars/" . htmlspecialchars($avatar);
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../view/login.php");
    exit();
}

$totalUsers = countUsers() ?? 0;
$totalCourses = countCourses() ?? 0;
$totalEnrollments = countTotalEnrollments() ?? 0;
$monthlyRevenue = getMonthlyRevenue() ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CodeCraft Admin Panel</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    
    <aside class="sidebar">
        <img src="../../assets/img/logo.png" class="brand-logo">
        <h2 class="logo">Admin</h2>
        <ul class="menu">
            <li class="active">
                <a href="#" onclick="showSection('dashboard')">📊 Dashboard</a>
            </li>
            <li>
                <a href="#" onclick="showSection('users')">👨‍🎓 Users</a>
            </li>
            <li>
                    <a href="#" onclick="showSection('courses')">📚 Courses</a>
            </li>
            <li>
                <a href="#" onclick="showSection('profile')">👤 Profile</a>
            </li>
            <li>
                <a href="#" onclick="showSection('settings')">⚙️ Settings</a>
            </li>
            <li>
                <a href="../../controllers/logout.php" onclick="showSection('logout')">🚪 Logout</a>
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
                <span><?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?></span>
            </div>
        </header>
        
        <?php if ($successMsg): ?>
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #c3e6cb;">
                <?= $successMsg; ?>
            </div>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #f5c6cb;">
                <?= $errorMsg; ?>
            </div>
        <?php endif; ?>
        
        <div class="cards section" id="dashboardSection">
            <div class="card">
                <h3>Total Users</h3>
                <p><?= $totalUsers; ?></p>
            </div>
            <div class="card">
                <h3>Total Courses</h3>
                <p><?= $totalCourses; ?></p>
            </div>
            <div class="card">
                <h3>Total Enrolled Courses</h3>
                <p><?= $totalEnrollments; ?></p>
            </div>
            <div class="card">
                <h3>Monthly Revenue</h3>
                <p>$ <?= number_format($monthlyRevenue, 2); ?></p>
            </div>
        </div>
        
        <div class="table-section section" id="usersSection" style="display:none;">
            <h2>All Users</h2>
            <div class="btn-container">
                <button class="button" onclick="showSection('addUsersSection')">👥 Add User</button>
                <button class="button" onclick="showSection('updateUsersSection')">👤 Update User Information</button>
                <button class="button" onclick="showSection('terminateUsersSection')">🚫 Terminate User</button>
            </div><br><br>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $users = getAllusers();
                if (!empty($users)):
                    foreach ($users as $user):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td>
                            <img src="<?= getAvatarPath($user['avatar'] ?? 'default.png'); ?>" 
                                 width="40" 
                                 height="40" 
                                 style="border-radius: 50%; object-fit: cover;"
                                 alt="<?= htmlspecialchars($user['full_name']); ?>" 
                                 onerror="this.src='<?= getAvatarPath('default.png'); ?>';">
                        </td>
                        <td><?= htmlspecialchars($user['full_name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><span style="padding: 5px 10px; background: <?= $user['role'] === 'admin' ? '#dc3545' : ($user['role'] === 'instructor' ? '#ffc107' : '#28a745'); ?>; color: white; border-radius: 5px; font-size: 12px;"><?= htmlspecialchars(ucfirst($user['role'])); ?></span></td>
                        <td><?= isset($user['created_at']) ? date('Y-m-d', strtotime($user['created_at'])) : 'N/A'; ?></td>
                    </tr>
                <?php
                    endforeach;
                else:
                ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #999;">No users found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-section section" id="addUsersSection" style="display:none;">
            <h2>Add New User</h2>
            <form action="../../controllers/addCheck.php" method="POST" enctype="multipart/form-data">
                <label for="add_full_name">Full Name *</label>
                <input type="text" class="txtStyle" id="add_full_name" name="full_name" placeholder="Enter full name" required><br><br>
                <label for="add_email">Email *</label>
                <input type="email" class="txtStyle" id="add_email" name="email" placeholder="Enter email address" required><br><br>
                <label for="add_password">Password *</label>
                <input type="password" class="txtStyle" id="add_password" name="password" placeholder="Enter password" required><br><br>
                <label for="add_role">Role *</label>
                <select id="add_role" class="txtStyle" name="role" required>
                    <option value="student">Student</option>
                    <option value="instructor">Instructor</option>
                    <option value="admin">Admin</option>
                </select><br><br>
                <label for="add_avatar">Avatar (Optional)</label>
                <input type="file" class="txtStyle" id="add_avatar" name="avatar" accept="image/jpeg,image/png,image/jpg,image/gif"><br>
                <small style="color: #666;">Accepted formats: JPG, PNG, GIF</small><br><br>
                <button type="submit" class="button">✅ Add User</button>
                <button type="button" class="button" onclick="showSection('users')" style="background: #6c757d;">❌ Cancel</button>
            </form>
        </div>
        <div class="table-section section" id="updateUsersSection" style="display:none;">
            <h2>Update User Information</h2>
            <form onsubmit="return false;">
                <label for="searchUser">Search User</label>
                <input type="text" class="txtStyle" id="searchUser" placeholder="Search by email or name"><br>
                <button type="button" class="button" id="searchBtn">Search User</button><br><br>
            </form>
            <form action="../../controllers/updateUser.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="user_id" name="user_id" value="">
                    <label for="update_full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="txtStyle" required><br><br>
                    <label for="update_email">Email</label>
                    <input type="email" id="email" name="email" class="txtStyle" required><br><br>
                    <label for="update_password">Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password" class="txtStyle"><br><br>
                    <label for="update_role">Role</label>
                    <select id="role" name="role" class="txtStyle">
                        <option value="student">Student</option>
                        <option value="instructor">Instructor</option>
                    </select><br><br>
                    <label for="update_avatar">Avatar</label>
                    <input type="file" id="avatar" class="txtStyle" name="avatar" accept="image/*"><br><br>
                    <button type="submit" class="button">✅ Update User</button>
                    <button type="button" class="button" onclick="showSection('users')" style="background: #6c757d;">❌ Cancel</button>
            </form> 
        </div>
        <div class="table-section section" id="terminateUsersSection" style="display:none;">
            <h2>Terminate User</h2>
            
            <form onsubmit="return false;">
                <label for="searchUserTerminate">Search User</label>
                <input type="text" id="searchUserTerminate" class="txtStyle" placeholder="Search by email, name, or user ID"><br>
                <button type="button" class="button" id="searchTerminateBtn">Search User</button><br><br>
            </form>
            
            <form action="../../controllers/terminateUser.php" method="POST" onsubmit="return confirm('Are you sure you want to terminate this user? This action cannot be undone!');">
                <input type="hidden" id="terminate_user_id" name="user_id" value="">
                <label for="terminate_full_name">Full Name</label>
                <input type="text" class="txtStyle" id="terminate_full_name" name="full_name" readonly><br><br>
                <label for="terminate_email">Email</label>
                <input type="email" class="txtStyle" id="terminate_email" name="email" readonly><br><br>
                <label for="terminate_role">Role</label>
                <input type="text" class="txtStyle" id="terminate_role" name="role" readonly><br><br>
                <button type="submit" class="button" style="background: #dc3545;">🗑️ Terminate User</button>
                <button type="button" class="button" onclick="showSection('users')" style="background: #6c757d;">❌ Cancel</button>
            </form>
        </div>
        <div class="table-section section" id="profileSection" style="display:none;">
            <h2>My Profile</h2>
            <div class="profile-container">
                <div class="profile-avatar">
                    <img src="<?= getAvatarPath($_SESSION['avatar'] ?? null); ?>" 
                         alt="<?= htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?> Avatar" 
                         class="profile-img"
                         onerror="this.onerror=null; this.src='<?= getAvatarPath('default.png'); ?>';">
                </div>
                <div class="profile-info">
                    <p><strong>Full Name:</strong> <?= htmlspecialchars($_SESSION['full_name'] ?? 'N/A'); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email'] ?? 'N/A'); ?></p>
                    <p><strong>Role:</strong> <?= htmlspecialchars(ucfirst($_SESSION['role'] ?? 'N/A')); ?></p>
                    <p><strong>User ID:</strong> <?= htmlspecialchars($_SESSION['user_id'] ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="table-section section" id="courseSection" style="display:none;">
            <h2>All Courses</h2>
            <div class="btn-container">
                <button class="button" onclick="showSection('addcoursesSection')">📚 Add Course</button>
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
                $courses = getAllCourses();
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
            
            <div class="table-section section" id="addcoursesSection" style="display:none;">
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
            
            <div class="table-section section" id="updatecoursesSection" style="display:none;">
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
            
            <div class="table-section section" id="deletecoursesSection" style="display:none;">
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
            
        <div id="settingsSection" class="section" style="display:none;">
            <h2>Settings</h2>
            <form action="../controller/updateSettings.php" method="POST">
                <label for="site_name">Site Name</label>
                <input type="text" class="txtStyle" id="site_name" name="site_name" required>
                <button type="submit" class="button" value="save">Save</button>
                <button type="submit" class="button" value="exit">Exit</button>
            </form>
        </div>
    </main>
</div>
<script src="../../assets/js/admin.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        if (alerts.length > 0) {
            setTimeout(function() {
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                });
            }, 4000);
        }
    });
</script>
</body>
</html>