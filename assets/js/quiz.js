let timeLeft = 30;
let timer;

function startQuiz() {
  document.getElementById("startScreen").style.display = "none";
  document.getElementById("quiz").style.display = "block";
  document.getElementById("submitBtn").style.display = "inline-block";

  loadQuiz();
  startTimer();
}

function loadQuiz() {
  const quizDiv = document.getElementById("quiz");
  quizDiv.innerHTML = "";

  quizData.forEach((q, i) => {
    const qDiv = document.createElement("div");
    qDiv.classList.add("question-block");
    qDiv.innerHTML = `
      <p><strong>${i + 1}. ${q.question}</strong></p>
      <label><input type="radio" name="answers[${i}]" value="${q.q1}"> ${q.q1}</label><br>
      <label><input type="radio" name="answers[${i}]" value="${q.q2}"> ${q.q2}</label><br>
      <label><input type="radio" name="answers[${i}]" value="${q.q3}"> ${q.q3}</label><br>
      <label><input type="radio" name="answers[${i}]" value="${q.q4}"> ${q.q4}</label><br>
      <hr>
    `;
    quizDiv.appendChild(qDiv);
  });
}

function startTimer() {
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
    if (!document.querySelector(`input[name="answers[${i}]"]:checked`)) {
      alert("⚠ Please answer all questions before submitting!");
      return false;
    }
  }
  return true;
}

function submitQuiz() {
  clearInterval(timer);
  if (!validateQuiz()) return;
  document.getElementById("quizForm").submit();
}

function retakeQuiz() {
  clearInterval(timer);
  // Simply reload the page for a fresh quiz start
  window.location.reload();
}
