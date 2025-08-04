function gradeQuiz() {
  const answers = {
    q1: 'c',
    q2: 'a',
  };

  let score = 0;
  let total = Object.keys(answers).length;

  for (let q in answers) {
    const selected = document.querySelector(`input[name="${q}"]:checked`);
    if (selected && selected.value === answers[q]) {
      score++;
    }
  }

  sessionStorage.setItem("quizScore", score);
  sessionStorage.setItem("quizTotal", total);

  window.location.href = "result.html";
}



    
