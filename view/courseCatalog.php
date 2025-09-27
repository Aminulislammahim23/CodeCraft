
<?php
    session_start();
    if(!isset($_SESSION['status']) && !isset($_COOKIE['status'])){
    header('location: login.php?error=badrequest');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Course Catalog - CodeCraft</title>
  <link rel="stylesheet" href="../assets/css/courseCatalog.css">
 
</head>
<body>
  <header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
               <li><a href="../index.html">Home</a></li>
                <li><a href="../pages/courseCatalog.html">Courses</a></li>
                <li><a href="../pages/forum.html" class="active">Forum</a></li>
                <li><a href="../pages/progress.html">Progress</a></li>
                <li><a href="../pages/login.html">Account</a></li>
            </ul>
        </nav>

        <div class="notification-container">
          <div class="bell" id="bell">
            🔔
            <span id="unreadCount" class="count">3</span>
          </div>
          <div class="dropdown" id="dropdown">
            <h3>Notifications</h3>
            <ul id="notificationList">
              <!-- JS will load notifications here -->
            </ul>
            <button id="markAllRead">Mark all as read</button>
    
            <!-- Notification settings inside same dropdown -->
            <div class="settings-section">
              <h4>Notification Settings</h4>
              <label>
                <input type="checkbox" id="emailToggle" checked>
                Email Notifications
              </label><br>
              <label>
                <input type="checkbox" id="pushToggle" checked>
                Push Notifications
              </label>
            </div>
          </div>
        </div>
    
    </header>


  </div>
  <section class="hero">
   <h1> Explore Our Courses</h1>
   <p>Upgrade your skills with our expert-taught courses</p>
  </section>
   <div class="search-container">
    <input type="text" id="searchInput" name="searchInput" placeholder="Search by course name...">
    <button onclick="searchCertificate()">Search</button>
  </div>

  <div class="container">
    <!-- Filters Section -->
    <aside class="filters">
      <h3>Filter Courses</h3>
      <label for="category">Category</label>
      <select id="category">
        <option>All</option>
        <option>Frontend</option>
        <option>Backend</option>
        <option>Programming</option>
      </select>

      <label for="level">Difficulty</label>
      <select id="level">
        <option>All</option>
        <option>Beginner</option>
        <option>Intermediate</option>
        <option>Advanced</option>
      </select>

      <label for="duration">Duration</label>
      <select id="duration">
        <option>All</option>
        <option>Short (0-5 hrs)</option>
        <option>Medium (5-15 hrs)</option>
        <option>Long (15+ hrs)</option>
      </select>
    </aside>

    <!-- Course Gallery -->
    <main class="courses" id="course-list">
      <!-- HTML Course -->
      <div class="course-card" data-category="Frontend" data-level="Beginner" data-duration="Short">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/61/HTML5_logo_and_wordmark.svg" alt="HTML Course">
        <div class="course-info">
          <h4>Learn HTML</h4>
          <p>Instructor: Mahim</p>
          <div class="rating">⭐⭐⭐⭐☆</div>
          <button class="details-btn" data-course="html">View Details</button>
          <button class="preview-btn" data-video="https://www.w3schools.com/html/mov_bbb.mp4">Preview</button>
        </div>
      </div>

      <!-- CSS Course -->
      <div class="course-card" data-category="Frontend" data-level="Beginner" data-duration="Medium">
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d5/CSS3_logo_and_wordmark.svg" alt="CSS Course">
        <div class="course-info">
          <h4>Master CSS</h4>
          <p>Instructor: Mahim</p>
          <div class="rating">⭐⭐⭐⭐⭐</div>
          <button class="details-btn" data-course="css">View Details</button>
          <button class="preview-btn" data-video="https://www.w3schools.com/html/mov_bbb.mp4">Preview</button>
        </div>
      </div>

      <!-- JavaScript Course -->
      <div class="course-card" data-category="Frontend" data-level="Intermediate" data-duration="Medium">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png" alt="JavaScript Course">
        <div class="course-info">
          <h4>JavaScript Essentials</h4>
          <p>Instructor: Mahim</p>
          <div class="rating">⭐⭐⭐⭐⭐</div>
          <button class="details-btn" data-course="javascript">View Details</button>
          <button class="preview-btn" data-video="https://www.w3schools.com/html/mov_bbb.mp4">Preview</button>
        </div>
      </div>

      <!-- C++ Course -->
      <div class="course-card" data-category="Programming" data-level="Intermediate" data-duration="Long">
        <img src="https://upload.wikimedia.org/wikipedia/commons/1/18/ISO_C%2B%2B_Logo.svg" alt="C++ Course">
        <div class="course-info">
          <h4>C++ Programming</h4>
          <p>Instructor: Mahim</p>
          <p>Duration: 15 hrs | Intermediate</p>
          <div class="rating">⭐⭐⭐⭐⭐</div>
          <button class="details-btn" data-course="cpp">View Details</button>
          <button class="preview-btn" data-video="https://www.w3schools.com/html/mov_bbb.mp4">Preview</button>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal -->
  <div class="modal" id="modal">
    <div class="modal-content" id="modal-content">
      <span class="close-btn" id="close-btn">&times;</span>
      <div id="modal-body"></div>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 CodeCraft. All rights reserved.</p>
  </footer>

  <script src="../assets/js/courseCatalog.js"></script>
</body>


</html>