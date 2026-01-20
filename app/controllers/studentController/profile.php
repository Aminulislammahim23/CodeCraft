<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/asset_helper.php';
require_once __DIR__ . '/../../models/userModel.php';
require_once __DIR__ . '/../../models/courseModel.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: " . controller_url('auth/login.php'));
    exit();
}
$user = getUserById($_SESSION['user_id']);
$enrolledCourses = getEnrolledCourses($_SESSION['user_id']);
$completedCourses = getCompletedCourses($_SESSION['user_id']);
$overallProgress = getOverallProgress($_SESSION['user_id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    if (empty($fullName) || empty($email)) {
        $errorMsg = "Full name and email are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email format!";
    } else {
        $result = updateUserInfo($_SESSION['user_id'], $fullName, $email, $phone, $bio);
        if ($result) {
            $_SESSION['full_name'] = $fullName;
            $_SESSION['email'] = $email;
            $successMsg = "Profile updated successfully!";
            $user = getUserById($_SESSION['user_id']);
        } else {
            $errorMsg = "Failed to update profile!";
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $passwordErrorMsg = "All password fields are required!";
    } elseif ($newPassword !== $confirmPassword) {
        $passwordErrorMsg = "New passwords do not match!";
    } elseif (strlen($newPassword) < 6) {
        $passwordErrorMsg = "New password must be at least 6 characters!";
    } else {
        $storedPassword = getUserPassword($_SESSION['user_id']);
        if (!password_verify($currentPassword, $storedPassword)) {
            $passwordErrorMsg = "Current password is incorrect!";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $result = updateUserPassword($_SESSION['user_id'], $hashedPassword);
            if ($result) {
                $passwordSuccessMsg = "Password updated successfully!";
            } else {
                $passwordErrorMsg = "Failed to update password!";
            }
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024;
    if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileType = $_FILES['avatar']['type'];
        if ($fileSize > $maxSize) {
            $avatarErrorMsg = "File size exceeds 2MB limit!";
        } elseif (!in_array($fileType, $allowedTypes)) {
            $avatarErrorMsg = "Only JPG, PNG, and GIF files are allowed!";
        } else {
            $uploadDir = __DIR__ . '/../../assets/uploads/users/avatars/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = $_SESSION['user_id'] . '_' . time() . '.' . $extension;
            $destination = $uploadDir . $newFileName;
            if (move_uploaded_file($tmpName, $destination)) {
                $result = updateUserAvatar($_SESSION['user_id'], $newFileName);
                if ($result) {
                    $_SESSION['avatar'] = $newFileName;
                    $avatarSuccessMsg = "Avatar updated successfully!";
                    $user = getUserById($_SESSION['user_id']);
                } else {
                    $avatarErrorMsg = "Failed to update avatar in database!";
                }
            } else {
                $avatarErrorMsg = "Failed to upload file!";
            }
        }
    } else {
        $avatarErrorMsg = "File upload error!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings | Student Dashboard</title>
    <link rel="stylesheet" href="<?php echo css_url('student.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-header h1 {
            color: #4f46e5;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .profile-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .profile-tab {
            padding: 12px 20px;
            background: #e2e8f0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .profile-tab.active {
            background: #4f46e5;
            color: white;
        }
        .profile-tab:hover:not(.active) {
            background: #cbd5e1;
        }
        .profile-section {
            display: none;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        .profile-section.active {
            display: block;
        }
        .profile-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .profile-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .profile-card h3 {
            color: #1e293b;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .avatar-upload {
            text-align: center;
            margin-bottom: 20px;
        }
        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 15px;
            border: 4px solid #e2e8f0;
        }
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .avatar-input {
            margin-top: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4f46e5;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #4f46e5;
            color: white;
        }
        .btn-primary:hover {
            background: #4338ca;
        }
        .btn-secondary {
            background: #64748b;
            color: white;
        }
        .btn-secondary:hover {
            background: #475569;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #4f46e5;
        }
        .stat-card .label {
            color: #64748b;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .profile-container {
                padding: 10px;
            }
            .profile-tabs {
                flex-direction: column;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        
        <aside class="sidebar">
            <img src="<?php echo img_url('logo.png'); ?>" class="brand-logo">
            <h2 class="logo">Welcome to CodeCraft</h2>
            <ul class="menu">
                <li>
                    <a href="<?php echo view_url('student/dashboard.php'); ?>">📊 Dashboard</a>
                </li>
                <li>
                    <a href="<?php echo view_url('student/dashboard.php#courses'); ?>">📚 Courses</a>
                </li>
                <li>
                    <a href="<?php echo view_url('student/dashboard.php#enrollments'); ?>">📦 Enrollments</a>
                </li>
                <li>
                    <a href="<?php echo controller_url('studentController/invoices.php'); ?>">📄 Invoices</a>
                </li>
                <li class="active">
                    <a href="#">👤 Profile</a>
                </li>
                <li>
                    <a href="<?php echo controller_url('logout.php'); ?>">🚪 Logout</a>
                </li>
            </ul>
        </aside>
        <main class="main">
            
            <header class="topbar">
                <h1><i class="fas fa-user-circle"></i> Profile Settings</h1>
                <div class="student-info">
                    <span><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Student'); ?></span>
                </div>
            </header>
            <div class="profile-container">
                <div class="profile-header">
                    <h1><i class="fas fa-user-edit"></i> Manage Your Profile</h1>
                    <p>Update your personal information and preferences</p>
                </div>
                
                <div class="profile-tabs">
                    <button class="profile-tab active" data-tab="overview">Overview</button>
                    <button class="profile-tab" data-tab="personal">Personal Info</button>
                    <button class="profile-tab" data-tab="password">Change Password</button>
                    <button class="profile-tab" data-tab="avatar">Avatar</button>
                </div>
                
                <div class="profile-section active" id="overview">
                    <div class="profile-overview">
                        <div class="profile-card">
                            <h3><i class="fas fa-user"></i> Personal Information</h3>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>
                            <p><strong>Joined:</strong> <?php echo date('F j, Y', strtotime($user['created_at'] ?? date('Y-m-d'))); ?></p>
                        </div>
                        <div class="profile-card">
                            <h3><i class="fas fa-graduation-cap"></i> Learning Stats</h3>
                            <p><strong>Enrolled Courses:</strong> <?php echo count($enrolledCourses); ?></p>
                            <p><strong>Completed Courses:</strong> <?php echo $completedCourses; ?></p>
                            <p><strong>Overall Progress:</strong> <?php echo round($overallProgress); ?>%</p>
                        </div>
                        <div class="profile-card">
                            <h3><i class="fas fa-image"></i> Avatar</h3>
                            <div class="avatar-preview">
                                <img src="<?php echo UPLOADS_URL . '/users/avatars/' . htmlspecialchars($user['avatar'] ?? 'default.png'); ?>" alt="Profile Avatar">
                            </div>
                            <p>Current avatar image</p>
                        </div>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="value"><?php echo count($enrolledCourses); ?></div>
                            <div class="label">Enrolled Courses</div>
                        </div>
                        <div class="stat-card">
                            <div class="value"><?php echo $completedCourses; ?></div>
                            <div class="label">Completed Courses</div>
                        </div>
                        <div class="stat-card">
                            <div class="value"><?php echo round($overallProgress); ?>%</div>
                            <div class="label">Overall Progress</div>
                        </div>
                        <div class="stat-card">
                            <div class="value"><?php echo date('Y', strtotime($user['created_at'] ?? date('Y-m-d'))); ?></div>
                            <div class="label">Member Since</div>
                        </div>
                    </div>
                </div>
                
                <div class="profile-section" id="personal">
                    <?php if (isset($successMsg)): ?>
                        <div class="alert alert-success">
                            <?php echo $successMsg; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($errorMsg)): ?>
                        <div class="alert alert-error">
                            <?php echo $errorMsg; ?>
                        </div>
                    <?php endif; ?>
                    <h2><i class="fas fa-user-edit"></i> Personal Information</h2>
                    <form method="POST" action="">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <input type="text" id="role" value="<?php echo htmlspecialchars($user['role'] ?? 'student'); ?>" disabled readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>
                
                <div class="profile-section" id="password">
                    <?php if (isset($passwordSuccessMsg)): ?>
                        <div class="alert alert-success">
                            <?php echo $passwordSuccessMsg; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($passwordErrorMsg)): ?>
                        <div class="alert alert-error">
                            <?php echo $passwordErrorMsg; ?>
                        </div>
                    <?php endif; ?>
                    <h2><i class="fas fa-key"></i> Change Password</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-primary">
                            <i class="fas fa-sync-alt"></i> Change Password
                        </button>
                    </form>
                </div>
                
                <div class="profile-section" id="avatar">
                    <?php if (isset($avatarSuccessMsg)): ?>
                        <div class="alert alert-success">
                            <?php echo $avatarSuccessMsg; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($avatarErrorMsg)): ?>
                        <div class="alert alert-error">
                            <?php echo $avatarErrorMsg; ?>
                        </div>
                    <?php endif; ?>
                    <h2><i class="fas fa-image"></i> Update Avatar</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="avatar-upload">
                            <div class="avatar-preview">
                                <img src="<?php echo UPLOADS_URL . '/users/avatars/' . htmlspecialchars($user['avatar'] ?? 'default.png'); ?>" alt="Current Avatar">
                            </div>
                            <p>Current Avatar</p>
                        </div>
                        <div class="form-group">
                            <label for="avatar">Choose New Avatar</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*">
                            <small class="help-text">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Avatar
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <script src="<?php echo js_url('student.js'); ?>"></script>
    <script>
        document.querySelectorAll('.profile-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.profile-section').forEach(s => s.classList.remove('active'));
                tab.classList.add('active');
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
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