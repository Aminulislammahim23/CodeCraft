let timeLeft = 30;
let timer;

function startQuiz() {
    const startScreen = document.getElementById("startScreen");
    const quiz = document.getElementById("quiz");
    const submitBtn = document.getElementById("submitBtn");
    const timerDisplay = document.getElementById("timer");

    if (!startScreen || !quiz || !submitBtn || !timerDisplay) {
        console.error("Required elements not found!");
        return;
    }

    startScreen.style.display = "none";
    quiz.style.display = "block";
    submitBtn.style.display = "inline-block";
    timerDisplay.style.display = "block";

    loadQuiz();
    startTimer();
}

function loadQuiz() {
    const quizDiv = document.getElementById("quiz");
    if (!quizDiv) {
        console.error("Quiz div not found!");
        return;
    }
    quizDiv.innerHTML = "";
    if (!quizData || quizData.length === 0) {
        quizDiv.innerHTML = "<p>No questions available. Contact support.</p>";
        return;
    }
    quizData.forEach((q, i) => {
        let qDiv = document.createElement("div");
        qDiv.className = "question-block"; // Match CSS
        qDiv.innerHTML = `
            <p class="question">${i + 1}. ${q.question}</p>
            <div class="options">${q.options.map((opt, idx) => 
                `<label><input type="radio" name="q${i}" value="${idx}"> ${opt}</label><br>`
            ).join("")}</div>
        `;
        quizDiv.appendChild(qDiv);
    });
}

function startTimer() {
    timeLeft = 30;
    const timerDisplay = document.getElementById("timer");
    if (!timerDisplay) {
        console.error("Timer element not found!");
        return;
    }
    timerDisplay.textContent = `Time Left: ${timeLeft}s`;
    clearInterval(timer);
    timer = setInterval(() => {
        timeLeft--;
        timerDisplay.textContent = `Time Left: ${timeLeft}s`;
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
    if (validateQuiz() || timeLeft <= 0) {
        const form = document.getElementById("quizForm");
        if (!form) {
            console.error("Form not found!");
            return;
        }
        const answers = {};
        quizData.forEach((q, i) => {
            const selected = document.querySelector(`input[name="q${i}"]:checked`);
            answers[i] = selected ? selected.value : null;
        });
        const formData = new FormData(form);
        formData.append("answers", JSON.stringify(answers));
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById("scoreBox").innerHTML = data;
            clearInterval(timer);
        })
        .catch(error => console.error('Error:', error));
    }
}

function restartQuiz() {
    document.getElementById("startScreen").style.display = "block";
    document.getElementById("quiz").style.display = "none";
    document.getElementById("submitBtn").style.display = "none";
    document.getElementById("timer").style.display = "none";
    document.getElementById("scoreBox").innerHTML = "";
    clearInterval(timer);
    timeLeft = 30;
}

document.addEventListener("DOMContentLoaded", () => {
    // Optional: Preload quiz if desired
    // startQuiz(); // Uncomment if you want auto-start
});