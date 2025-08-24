const quizData = [
  {
    question: "Which of the following is a JavaScript data type?",
    options: ["String", "HTML", "CSS", "XML"],
    answer: "String",
    explanation: "String is a basic data type in JavaScript for text."
  },
  {
    question: "Which symbol is used for comments in JS?",
    options: ["//", "/* */", "<!-- -->", "#"],
    answer: "//",
    explanation: "Single-line comments in JavaScript use //."
  },
  {
    question: "What does '===' mean in JS?",
    options: ["Equal value & type", "Assign value", "Equal value only", "Not equal"],
    answer: "Equal value & type",
    explanation: "=== checks for strict equality: value and type must match."
  }
];

let currentQuiz = 0;
let score = 0;
let timeLeft = 60;
let timer;

function loadQuiz() {
  if(currentQuiz >= quizData.length) {
    showResult();
    return;
  }

  const quiz = quizData[currentQuiz];
  const quizDiv = document.getElementById('quiz');
  quizDiv.innerHTML = `<div class="question">${quiz.question}</div><div class="options">${quiz.options.map(o => `<button onclick="selectAnswer('${o}')">${o}</button>`).join('')}</div>`;
}

function selectAnswer(selected) {
  const quiz = quizData[currentQuiz];
  let msg = '';
  if(selected === quiz.answer) {
    score++;
    msg = `✅ Correct! Explanation: ${quiz.explanation}`;
  } else {
    msg = `❌ Incorrect! Explanation: ${quiz.explanation}`;
  }
  alert(msg);
  currentQuiz++;
  loadQuiz();
}

function showResult() {
  clearInterval(timer);
  const resultDiv = document.getElementById('result');
  resultDiv.style.display = 'block';
  resultDiv.innerHTML = `<h3>Quiz Completed!</h3><p>Your Score: ${score} / ${quizData.length}</p>`;
  if(score < quizData.length) {
    resultDiv.innerHTML += `<button id="retakeBtn" onclick="retakeQuiz()">Retake Quiz</button>`;
  }
}

function retakeQuiz() {
  currentQuiz = 0;
  score = 0;
  timeLeft = 60;
  document.getElementById('result').style.display = 'none';
  startTimer();
  loadQuiz();
}

function startTimer() {
  document.getElementById('time').innerText = timeLeft;
  timer = setInterval(() => {
    timeLeft--;
    document.getElementById('time').innerText = timeLeft;
    if(timeLeft <= 0) {
      clearInterval(timer);
      alert("Time's up!");
      showResult();
    }
  }, 1000);
}

// Initialize
loadQuiz();
startTimer();