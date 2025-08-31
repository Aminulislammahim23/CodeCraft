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
      document.getElementById("timer").style.display = "block";

      loadQuiz();
      startTimer();
    }

    function loadQuiz() {
  const quizDiv = document.getElementById("quiz");
  quizDiv.innerHTML = "";
  quizData.forEach((q, i) => {
    let qDiv = document.createElement("div");
    qDiv.innerHTML = `
      <p>${i + 1}. ${q.question}</p>
      ${q.options.map((opt, idx) =>
        `<label><input type="radio" name="q${i}" value="${idx}"> ${opt}</label><br>`
      ).join("")}
    `;
    quizDiv.appendChild(qDiv);
  });
  document.getElementById("submitBtn").style.display = "inline-block";
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
    if (!document.querySelector(`input[name="q${i}"]:checked`)) {
      alert("⚠ Please answer all questions before submitting!");
      return false;
    }
  }
  return true;
}

    // Hook validation into form submit
document.addEventListener("DOMContentLoaded", () => {
  loadQuiz();
  document.getElementById("quizForm").addEventListener("submit", function(e) {
    if (!validateQuiz()) {
      e.preventDefault(); // stop submission
    }
  });
});