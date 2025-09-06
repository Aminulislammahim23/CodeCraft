<?php
include __DIR__.'/../model/mydb.php';

class QuizModel {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    // Fetch all questions for a specific course
    public function getQuestionsByCourse($course) {
        $stmt = $this->conn->prepare("SELECT q.* 
                                            FROM questions q
                                            JOIN quiz c ON q.quiz_id = c.id
                                    WHERE c.course = ?");

        $stmt->bind_param("s", $course);
        $stmt->execute();
        $result = $stmt->get_result();

        $questions = [];
        while($row = $result->fetch_assoc()) {
            $questions[] = [
                'question' => $row['question'],
                'options' => [$row['q1'],$row['q2'],$row['q3'],$row['q4']],
                'answer' => $row['answer']
            ];
        }
        return $questions;
    }

    // Optional: fetch all courses
    public function getCourses() {
        $result = $this->conn->query("SELECT * FROM quiz");
        $courses = [];
        while($row = $result->fetch_assoc()) {
            $courses[] = $row['course'];
        }
        return $courses;
    }
}
?>
