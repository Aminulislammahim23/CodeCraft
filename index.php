<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeCraft Learning Platform | Online Education & Training</title>
    <link rel="stylesheet" href="app/assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        
        <div class="left">
            <div>
                <div class="top-bar">
                    <div class="logo">
                        <i class="fas fa-graduation-cap icon"></i>
                        <span>CodeCraft</span>
                    </div>
                    <button class="btn">
                        <a href="app/views/auth/register.php">Join Now</a>
                    </button>
                </div>
                <div class="content">
                    <h1 class="title">Learn with <span>CodeCraft</span> Platform</h1>
                    <p class="subtitle">Comprehensive e-learning platform to master programming, web development, and digital skills</p>
                    <div class="offer-heading">
                        <h3>Learning Features</h3>
                    </div>
                    <div class="offer-boxes">
                        <div class="offer-card">
                            <i class="fas fa-laptop-code icon"></i>
                            <span>Coding</span>
                        </div>
                        <div class="offer-card">
                            <i class="fas fa-book icon"></i>
                            <span>Courses</span>
                        </div>
                        <div class="offer-card">
                            <i class="fas fa-certificate icon"></i>
                            <span>Certificates</span>
                        </div>
                        <div class="offer-card">
                            <i class="fas fa-users icon"></i>
                            <span>Community</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <a href="#">Terms & Conditions</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Support</a>
            </div>
        </div>
        
        <div class="right">
            <div class="image-box">
                <img src="app/assets/img/dashboard-preview.png" alt="Admin Dashboard Preview" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'400\'><rect width=\'100%\' height=\'100%\' fill=\'%23f0f0f0\'/><text x=\'50%\' y=\'50%\' font-family=\'Arial\' font-size=\'16\' fill=\'%23666\' text-anchor=\'middle\'>Dashboard Preview</text></svg>'">
                <div class="tag tag1">Interactive Lessons</div>
                <div class="tag tag2">Live Projects</div>
                <div class="tag tag3">Expert Instructors</div>
            </div>
            <h2 class="tagline">Transform Your Skills with Our Interactive Learning Platform</h2>
            <div class="logo-row">
                <i class="fas fa-user-shield fa-2x"></i>
                <i class="fas fa-shield-alt fa-2x"></i>
                <i class="fas fa-lock fa-2x"></i>
                <i class="fas fa-cloud fa-2x"></i>
                <i class="fas fa-sync-alt fa-2x"></i>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.offer-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1)';
                    this.style.transition = 'transform 0.3s ease';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>