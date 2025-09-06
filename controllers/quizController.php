<?php
include __DIR__.'/../model/quizModel.php';

class QuizController {
    private $model;

    public function __construct() {
        $this->model = new QuizModel();
    }

    public function showQuiz($course = 'HTML') {
        $questions = $this->model->getQuestionsByCourse($course);
        include __DIR__.'/../view/quiz.php';
    }

    public function showCourseSelection() {
        $courses = $this->model->getCourses();
        include __DIR__.'/../view/courseSelection.php';
    }
}
?>