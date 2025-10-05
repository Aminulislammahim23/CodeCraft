<?php
    session_start();
    require_once('../controllers/forumController.php');
    $courses = getAllCourses();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeCraft - Forum Discussions</title>
    <link rel="stylesheet" href="../assets/css/forum.css">


</head>
<body>
   <header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="../view/enrollment.html">Enroll</a></li>
                <li><a href="../view/progress.html">Progress</a></li>
                <li><a href="../view/forum.html">Forums</a></li>
                <li><a href="../view/faq.html">FAQ</a></li>
            </ul>
        </nav>  
    </header>

    <div class="container">
        <aside class="sidebar">
            <h3>My Courses</h3>
<ul>
    <?php foreach ($courses as $course): ?>
        <li>
            <a href="#"><i class="fas fa-book"></i> <?= htmlspecialchars($course['course']) ?></a>
        </li>
    <?php endforeach; ?>
</ul>

            
            <h3>Forum Categories</h3>
            <ul>
                <li><a href="#" class="active"><i class="fas fa-comments"></i> All Discussions</a></li>
            
            </ul>
        </aside>

        <main class="content">
            <div class="forum-header">
                <h2>Forum Discussions</h2>
                <button id="newPostBtn" class="btn-primary"><i class="fas fa-plus"></i> New Post</button>
            </div>

            <div class="search-bar">
                <input type="text" placeholder="Search forum...">
                <button><i class="fas fa-search"></i></button>
            </div>

            <div class="forum-list">
                <!-- Forum posts will be loaded here -->
            </div>

            <div class="pagination">
                <button class="btn"><i class="fas fa-chevron-left"></i> Previous</button>
                <span>Page 1 of 5</span>
                <button class="btn">Next <i class="fas fa-chevron-right"></i></button>
            </div>
        </main>
    </div>

    <!-- New Post Modal -->
    <div id="newPostModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Create New Post</h2>
            <form id="newPostForm">
                <div class="form-group">
                    <label for="postTitle">Title</label>
                    <input type="text" id="postTitle" required>
                </div>
                <div class="form-group">
                    <label for="postCourse">Course</label>
                    <select id="postCourse" required>
                    <option value="">Select a course</option>
                    <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['course']) ?></option>
                    <?php endforeach; ?>
                </select>

                </div>
                <div class="form-group">
                    <label for="postContent">Your Question</label>
                    <textarea id="postContent" rows="8" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Post Question</button>
            </form>
        </div>
    </div>

    <!-- Post Detail Modal -->
    <div id="postDetailModal" class="modal">
        <div class="modal-content large">
            <span class="close">&times;</span>
            <div class="post-header">
                <h2 id="postDetailTitle">Loading post...</h2>
                <div class="post-meta">
                    <span class="author"><img src="https://via.placeholder.com/30" alt="User"> <span id="postAuthor">Author</span></span>
                    <span class="course" id="postCourseName">Course</span>
                    <span class="date" id="postDate">Date</span>
                </div>
            </div>
            <div class="post-content" id="postDetailContent">
                Loading content...
            </div>
            
            <div class="replies-section">
                <h3>Replies</h3>
                <div class="replies-list" id="repliesList">
                    <!-- Replies will be loaded here -->
                </div>
                
                <div class="reply-form">
                    <h4>Your Answer</h4>
                    <textarea id="replyContent" rows="4" placeholder="Type your answer here..."></textarea>
                    <button id="submitReply" class="btn-primary">Post Reply</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/forum.js"></script>
</body>
</html>