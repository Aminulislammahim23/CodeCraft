<?php
    session_start();
    if (!isset($_COOKIE['status'])) {
        header('location: login.php?error=unauthorized');
        exit();
    }

    require_once('../controller/auth.php');
    checkRole(['admin']);   // Only admin can access
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Data Export - Admin Panel</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

  <header>
    <div class="logo">CodeCraft</div>
    <nav>
      <ul>
        <li><a href="admin.php">🏠 Dashboard</a></li>
        <li><a href="dataExport.php" class="active">📥 Data Export</a></li>
        <li><a href="../controller/logout.php">🔓 Logout</a></li>
      </ul>
    </nav>
  </header>

  <main class="export-container">
    <h2>📥 Export Annual Revenue Data</h2>
    <p>Select a year and download the corresponding revenue report as a CSV file.</p>

    <form action="../controller/exportRevenue.php" method="POST" class="export-form">
      <label for="year">Select Year:</label>
      <select name="year" id="year" required>
          <option value="" disabled selected>-- Choose Year --</option>
          <option value="2023">2023</option>
          <option value="2024">2024</option>
          <option value="2025">2025</option>
      </select>

      <br><br>

      <button type="submit" name="export" value="csv">⬇ Download CSV</button>
    </form>
  </main>

  <footer>
    <p>&copy; <?= date("Y") ?> CodeCraft. All rights reserved.</p>
  </footer>

</body>
</html>
