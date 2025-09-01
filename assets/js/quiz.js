const quizData = [
  {
    question: "What does HTML stand for?",
    options: ["Hyper Text Markup Language", "High Text Machine Language", "Hyper Transfer Markup Language"],
    answer: 0,
    explanation: "HTML stands for Hyper Text Markup Language."
  },
  {
    question: "Which language is used for styling web pages?",
    options: ["HTML", "CSS", "Python"],
    answer: 1,
    explanation: "CSS is used for styling web pages."
  },
  {
    question: "What does JS stand for?",
    options: ["Java Source", "JavaScript", "Just Script"],
    answer: 1,
    explanation: "JS is short for JavaScript."
  }
];

let timeLeft = 30;
let timer;

function startQuiz() {
  document.getElementById("startScreen").style.display = "none";
  document.getElementById("quiz").style.display = "block";
  document.getElementById("submitBtn").style.display = "inline-block";
  document.getElementById("scoreBox").innerHTML = "";
  loadQuiz();
  startTimer();
}


function loadQuiz() {                                                       //Load quiz questions
  const quizDiv = document.getElementById("quiz");
  quizDiv.innerHTML = "";

  quizData.forEach((q, i) => {
    const qDiv = document.createElement("div");
    qDiv.classList.add("question-block");
    qDiv.innerHTML = `
      <p><strong>${i + 1}. ${q.question}</strong></p>
      ${q.options.map((opt, idx) =>
        `<label><input type="radio" name="q${i}" value="${idx}"> ${opt}</label><br>`
      ).join("")}
      <hr>
    `;
    quizDiv.appendChild(qDiv);
  });
}


function startTimer() {                                                         //Timer function
  timeLeft = 30;
  document.getElementById("timer").textContent = `Time Left: ${timeLeft}s`;
  timer = setInterval(() => {
    timeLeft--;
    document.getElementById("timer").textContent = `Time Left: ${timeLeft}s`;
    if (timeLeft <= 0) {
      clearInterval(timer);
      submitQuiz();
    }
  }, 1000);
}


function validateQuiz() {
  for (let i = 0; i < quizData.length; i++) {
    if (!document.querySelector(`input[name="q${i}"]:checked`)) {
      alert("⚠ Please answer all questions before submitting!");
      return false;
    }
  }
  return true;
}


function submitQuiz() {
  clearInterval(timer);

  if (!validateQuiz()) return;

  let score = 0;
  const resultBox = document.getElementById("scoreBox");
  resultBox.innerHTML = "";

  quizData.forEach((q, i) => {
    const selected = parseInt(document.querySelector(`input[name="q${i}"]:checked`).value);
    if (selected === q.answer) score++;

  
    resultBox.innerHTML += `
      <div class="result-question">
        <p><strong>Q${i+1}: ${q.question}</strong></p>
        <p>Your answer: ${q.options[selected]}</p>
        <p>Correct answer: ${q.options[q.answer]}</p>
        <p>Explanation: ${q.explanation}</p>
        <hr>
      </div>
    `;
  });


  resultBox.innerHTML = `<h3>Your Score: ${score} / ${quizData.length}</h3>` + resultBox.innerHTML;


  document.getElementById("submitBtn").style.display = "none";
  document.getElementById("restartBtn").style.display = "inline-block";
}


function retakeQuiz() {
  clearInterval(timer);
  document.getElementById("quiz").innerHTML = "";
  document.getElementById("scoreBox").innerHTML = "";
  document.getElementById("submitBtn").style.display = "inline-block";
  document.getElementById("startScreen").style.display = "block";
  document.getElementById("timer").textContent = "Time Left: 30s";
}
