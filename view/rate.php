<?php
    session_start();
    if(!isset($_COOKIE['status'])){
        header('location: login.php?error=badrequest');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Course Ratings - CodeCraft</title>
<link rel="stylesheet" href="../assets/css/rate.css">
</head>

<header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
                <li><a href="../index.html">Home</a></li>
                <li><a href="../view/enrollment.php">Enroll</a></li>
                <li><a href="../view/progress.php">Progress</a></li>
                <li><a href="../view/forum.html">Forums</a></li>
                <li><a href="../view/faq.html">FAQ</a></li>
            </ul>
        </nav>  
    </header>
<body>

<h1>Course Ratings</h1>

<label for="filter">Filter courses by minimum rating:</label>
<select id="filter">
  <option value="0">All</option>
  <option value="1">1 Star & Up</option>
  <option value="2">2 Stars & Up</option>
  <option value="3">3 Stars & Up</option>
  <option value="4">4 Stars & Up</option>
  <option value="5">5 Stars</option>
</select>

<div id="courses-container">
  <div class="course-card" data-rating="0">
    <h3>HTML Basics</h3>
    <div class="stars" data-course="0">
      <span data-value="1">&#9733;</span>
      <span data-value="2">&#9733;</span>
      <span data-value="3">&#9733;</span>
      <span data-value="4">&#9733;</span>
      <span data-value="5">&#9733;</span>
    </div>
    <textarea placeholder="Write a review (optional)"></textarea>
    <button onclick="submitReview(0)">Submit Review</button>
    <p>Average Rating: <span class="avg-rating">0</span> Stars</p>
  </div>

  <div class="course-card" data-rating="0">
    <h3>CSS Fundamentals</h3>
    <div class="stars" data-course="1">
      <span data-value="1">&#9733;</span>
      <span data-value="2">&#9733;</span>
      <span data-value="3">&#9733;</span>
      <span data-value="4">&#9733;</span>
      <span data-value="5">&#9733;</span>
    </div>
    <textarea placeholder="Write a review (optional)"></textarea>
    <button onclick="submitReview(1)">Submit Review</button>
    <p>Average Rating: <span class="avg-rating">0</span> Stars</p>
  </div>
</div>

<script src="../assets/js/rate.js"></script>

</body>
</html>
