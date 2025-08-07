function gradeQuiz() {
  // Correct answers for each question
  const answers = {
    q1: "c",  q2: "a",  q3: "a",  q4: "b",  q5: "a",
    q6: "a",  q7: "b",  q8: "d",  q9: "a",  q10: "a",
    q11: "b", q12: "c", q13: "a", q14: "b", q15: "d",
    q16: "a", q17: "c", q18: "b", q19: "a", q20: "c",
    q21: "a", q22: "c", q23: "b", q24: "a", q25: "d",
    q26: "b", q27: "a", q28: "c", q29: "b", q30: "a"
  };

  let score = 0;
  let total = Object.keys(answers).length;

  // Check each answer
  for (let key in answers) {
    let selected = document.querySelector(`input[name="${key}"]:checked`);
    if (selected && selected.value === answers[key]) {
      score++;
    }
  }

  // Show result
  alert(`You scored ${score} out of ${total} (${((score / total) * 100).toFixed(2)}%)`);
}
