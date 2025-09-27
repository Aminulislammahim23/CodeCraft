<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Online Learning Platform</title>
  <link rel="stylesheet" href="../assets/css/contact.css">
</head>
<body>
  <header>
    <div class="logo">CodeCraft</div>
    <nav>
        <ul>
           <li><a href="../index.php">Home</a></li>
            <li><a href="../page/login.php">Account</a></li>
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

  <main>
    <h2>Contact Us</h2>
    <form id="contactForm">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="subject">Subject:</label>
      <input type="text" id="subject" name="subject" required>

      <label for="message">Message:</label>
      <textarea id="message" name="message" rows="5" required></textarea>

      <button type="submit">Submit</button>
    </form>
  </main>

  <script src="../assets/js/contact.js"></script>
</body>
</html>
