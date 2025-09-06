let notifications = [
    "New course available: Web Development",
    "Assignment deadline tomorrow",
    "Your instructor replied to your query"
  ];
  
  const bell = document.getElementById("bell");
  const dropdown = document.getElementById("dropdown");
  const list = document.getElementById("notificationList");
  const unreadCount = document.getElementById("unreadCount");
  const markAllRead = document.getElementById("markAllRead");
  
  const emailToggle = document.getElementById("emailToggle");
  const pushToggle = document.getElementById("pushToggle");
  
  // Show notifications
  function showNotifications() {
    list.innerHTML = "";
    notifications.forEach(note => {
      let li = document.createElement("li");
      li.textContent = note;
      li.classList.add("unread");
      list.appendChild(li);
    });
    unreadCount.textContent = notifications.length;
  }
  
  // Toggle dropdown
  bell.onclick = () => {
    dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
  };
  
  // Mark all as read
  markAllRead.onclick = () => {
    list.querySelectorAll("li").forEach(li => li.classList.remove("unread"));
    unreadCount.style.display = "none";
  };
  
  // Handle toggles
  emailToggle.onchange = () => {
    alert("Email Notifications: " + (emailToggle.checked ? "ON" : "OFF"));
  };
  
  pushToggle.onchange = () => {
    alert("Push Notifications: " + (pushToggle.checked ? "ON" : "OFF"));
  };
  
  // Initial load
  showNotifications();
  