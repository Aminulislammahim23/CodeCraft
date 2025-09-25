<?php
session_start();
if (!isset($_COOKIE['status'])) {
    header('location: ../view/login.php?error=badrequest');
}
    require_once('../controller/auth.php');
     checkRole(['instructor']);   // only instructor can access

    require_once('../model/userMod.php');
    $users = getAllUser();
    $instructors = getAllInstructors();
    $students = getAllStudents();

    $id = $_SESSION['user_id'];
    $userbyID = getUserById($id);

    $avatarPath = !empty($userbyID['avatar']) ? $userbyID['avatar'] : "assets/uploads/imgP/default.jpg";

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
  <title>Instructor Dashboard</title>
  <link rel="stylesheet" href="../assets/css/adminDash.css">
</head>
<body>
  <header>
        <div class="logo">CodeCraft
          <h1>Welcome Instructor!! <?=$_SESSION['username']?></h1>
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
    <div style="display:flex; align-items:center; margin-bottom:20px;">
       <img src="../<?= htmlspecialchars($avatarPath) ?>" width="100" height="60" style="border-radius:50%">
    <h2 style="font-size:18px; margin:0;">Instructor Dashboard</h2>
    </div>
    <a href="#" onclick="showSection('courses')">📚 My Courses</a>
    <a href="#" onclick="showSection('instructor')">📝 Assignments</a>
    <a href="#" onclick="showSection('students')">👥 Students</a>
    <a href="#" onclick="showSection('revenue')">📥 Revenue</a>
    <a href="#" onclick="showSection('messages')">✉ Messages</a>
    <a href="#" onclick="showSection('export')">live class</a>
    <a href="profile.php">⚙ Profile</a>
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

   <div class="card section" id="messages" style="display:none;">
    <h3>Messages</h3>

    <!-- Custom Receiver Dropdown -->
    <div id="receiverDropdown" style="position:relative; margin-bottom:50px;">
        <div id="selectedReceiver" style="padding:5px; border:1px solid #ccc; cursor:pointer; display:flex; align-items:center; width:250px; border-radius:5px;">
            <img id="selectedAvatar" src="assets/uploads/imgP/default.jpg" style="width:30px; height:30px; border-radius:50%; margin-right:10px;">
            <span id="selectedName">Select Receiver</span>
        </div>
        <div id="receiverList" style="position:absolute; top:35px; left:0; border:1px solid #ccc; background:#fff; width:100%; max-height:200px; overflow-y:auto; display:none; z-index:1000;">
            <?php foreach($users as $u): ?>
                <div class="receiverOption" data-id="<?= $u['id'] ?>" style="padding:5px; display:flex; align-items:center; cursor:pointer; border-bottom:1px solid #eee;">
                    <img src="<?= $u['avatar'] ?: 'assets/uploads/imgP/default.jpg' ?>" style="width:30px; height:30px; border-radius:50%; margin-right:10px;">
                    <span><?= htmlspecialchars($u['username']) ?> (<?= $u['role'] ?>)</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Chat Box -->
    <div id="chat_box" style="border:1px solid #ccc; height:400px; overflow-y:auto; padding:10px; background:#f0f0f0; border-radius:10px;"></div>

    <!-- Message Input -->
    <div style="margin-top:10px; display:flex;">
        <input type="text" id="messageInput" placeholder="Type a message..." style="flex:1; padding:10px; border-radius:20px; border:1px solid #ccc;">
        <button id="sendBtn" style="padding:10px 20px; margin-left:5px; border-radius:20px; border:none; background:#4CAF50; color:white; cursor:pointer;">Send</button>
    </div>
</div>

<script>
const sender_id = <?= $_SESSION['user_id'] ?>;
let receiver_id = null;

// Dropdown toggle
const selected = document.getElementById('selectedReceiver');
const list = document.getElementById('receiverList');

selected.addEventListener('click', () => {
    list.style.display = list.style.display === 'none' ? 'block' : 'none';
});
// Select receiver
document.querySelectorAll('.receiverOption').forEach(option => {
    option.addEventListener('click', () => {
        receiver_id = option.getAttribute('data-id');
        const avatar = option.querySelector('img').src;
        const name = option.querySelector('span').innerText;

        document.getElementById('selectedAvatar').src = avatar;
        document.getElementById('selectedName').innerText = name;

        list.style.display = 'none';
        loadMessages();
    });
});
// Load messages
function loadMessages() {
    if(!receiver_id) return;

    fetch(`../controller/get_messages.php?user1=${sender_id}&user2=${receiver_id}`)
        .then(res => res.json())
        .then(data => {
            const chatBox = document.getElementById('chat_box');
            chatBox.innerHTML = '';

            data.forEach(msg => {
                const isSender = msg.sender_id == sender_id;
                const bubble = document.createElement('div');
                bubble.style.display = 'flex';
                bubble.style.marginBottom = '10px';
                bubble.style.justifyContent = isSender ? 'flex-end' : 'flex-start';
                
                const avatar = document.createElement('img');
                avatar.src = '../' + (msg.sender_avatar || 'assets/uploads/imgP/default.jpg');
                avatar.style.width = '35px';
                avatar.style.height = '35px';
                avatar.style.borderRadius = '50%';
                avatar.style.marginRight = isSender ? '0' : '10px';
                avatar.style.marginLeft = isSender ? '10px' : '0';

                const text = document.createElement('div');
                text.innerHTML = `<b>${msg.sender}</b><br>${msg.message}<br><small style="color:#888;">${msg.sent_at}</small>`;
                text.style.padding = '8px 12px';
                text.style.borderRadius = '15px';
                text.style.maxWidth = '60%';
                text.style.background = isSender ? '#4CAF50' : '#fff';
                text.style.color = isSender ? '#fff' : '#000';
                text.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';

                if(isSender){
                    bubble.appendChild(text);
                    bubble.appendChild(avatar);
                } else {
                    bubble.appendChild(avatar);
                    bubble.appendChild(text);
                }

                chatBox.appendChild(bubble);
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        });
}
// Send message
document.getElementById('sendBtn').addEventListener('click', () => {
    const msg = document.getElementById('messageInput').value.trim();
    if(!msg || !receiver_id) return;

    fetch('../controller/send_message.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: `sender_id=${sender_id}&receiver_id=${receiver_id}&message=${encodeURIComponent(msg)}`
    }).then(() => {
        document.getElementById('messageInput').value = '';
        loadMessages();
    });
});

setInterval(loadMessages, 2000);
</script>

    <script src="../assets/js/adminDash.js"></script>
</body>
</html>