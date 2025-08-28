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
        qDiv.classList.add("question-block");
        qDiv.innerHTML = `
          <p class="question">${i + 1}. ${q.question}</p>
          <div class="options">
            ${q.options.map((opt, idx) =>
              `<label><input type="radio" name="q${i}" value="${idx}"> ${opt}</label>`
            ).join("")}
          </div>
          <div class="explanation" id="exp${i}" style="display:none;"></div>
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

    function submitQuiz() {
      clearInterval(timer);
      let score = 0;

      quizData.forEach((q, i) => {
        let selected = document.querySelector(`input[name="q${i}"]:checked`);
        let expDiv = document.getElementById(`exp${i}`);

        if (selected && parseInt(selected.value) === q.answer) {
          score++;
          expDiv.innerHTML = `<span class="correct">✔ Correct!</span> ${q.explanation}`;
        } else {
          expDiv.innerHTML = `<span class="incorrect">✘ Incorrect.</span> ${q.explanation}`;
        }
        expDiv.style.display = "block";
      });

      document.getElementById("scoreBox").textContent = `Your Score: ${score}/${quizData.length}`;
      document.getElementById("restartBtn").style.display = "inline-block";
      document.getElementById("submitBtn").style.display = "none";
    }



function restartQuiz() {
      document.getElementById("scoreBox").textContent = "";
      document.getElementById("restartBtn").style.display = "none";
      document.getElementById("quiz").style.display = "none";
      document.getElementById("submitBtn").style.display = "none";
      document.getElementById("timer").style.display = "none";
      document.getElementById("startScreen").style.display = "block";
    }