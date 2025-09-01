<?php
    session_start();
    if(!isset($_COOKIE['status'])){
        header('location: login.php?error=badrequest');
    }

    $errors = $_SESSION['errors'] ?? [];
    $success = $_SESSION['success'] ?? '';
    $studentName = $_SESSION['studentName'] ?? '';
    $completionDate = $_SESSION['completionDate'] ?? '';
    unset($_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Certificate</title>
  <link rel="stylesheet" href="../assets/css/certificate.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
    <header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="../view/enrollment.php">Enroll</a></li>
                <li><a href="../view/progress.php">Progress</a></li>
                <li><a href="../view/forum.html">Forums</a></li>
                <li><a href="../view/faq.html">FAQ</a></li>
            </ul>
        </nav>  
    </header>

  <?php if(!empty($errors)): ?>
    <ul style="color:red;">
    <?php foreach($errors as $err) echo "<li>$err</li>"; ?>
    </ul>
<?php endif; ?>

<?php if($success): ?>
    <p style="color:green;"><?= $success ?></p>
<?php endif; ?>

<form id="certificateForm" method="post" action="validate.php">
    <label>Your Name:</label>
    <input type="text" id="studentName" name="studentName" value="<?= htmlspecialchars($studentName) ?>" required>
    <br><br>
    <label>Completion Date:</label>
    <input type="date" id="completionDate" name="completionDate" value="<?= htmlspecialchars($completionDate) ?>" required>
    <br><br>
    <button type="button" onclick="generatePreview()">Preview</button>
    <button type="button" onclick="generateCertificate()">Generate PDF</button>
    <button type="button" onclick="generateShareLink()">Copy Shareable Link</button>
    <br><br>
    <button type="submit">Submit Form (PHP Validation)</button>
</form>

<hr>

<h3>Certificate Preview:</h3>
<p>Name: <span id="nameDisplay">[Your Name]</span></p>
<p>Date: <span id="dateDisplay">[Date]</span></p>

  <footer>
    <p>&copy; 2025 Online Learning Platform</p>
  </footer>

  <script src="../assets/js/certificate.js"></script>
</body>
</html>