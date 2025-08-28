<?php include("../db.php"); ?>

<h2>Add Question</h2>
<form method="POST">
    <label>Course:</label>
    <select name="course_id">
        <?php
        $result = $conn->query("SELECT * FROM courses");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['course_name']}</option>";
        }
        ?>
    </select><br><br>

    <label>Question:</label><br>
    <textarea name="question" required></textarea><br><br>

    <label>Option A:</label><input type="text" name="option_a" required><br>
    <label>Option B:</label><input type="text" name="option_b" required><br>
    <label>Option C:</label><input type="text" name="option_c" required><br>

    <label>Correct Option:</label>
    <select name="correct_option">
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
    </select><br><br>

    <label>Explanation:</label><br>
    <textarea name="explanation"></textarea><br><br>

    <button type="submit" name="save">Save Question</button>
</form>

<?php
if (isset($_POST['save'])) {
    $course_id = $_POST['course_id'];
    $q = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $ans = $_POST['correct_option'];
    $exp = $_POST['explanation'];

    $sql = "INSERT INTO questions (course_id, question, option_a, option_b, option_c, correct_option, explanation)
            VALUES ('$course_id', '$q', '$a', '$b', '$c', '$ans', '$exp')";
    if ($conn->query($sql)) {
        echo "✅ Question added successfully!";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>
