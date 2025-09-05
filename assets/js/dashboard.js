
function getCookie(name) {
  const cname = name + "=";
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(';');
  for (let c of ca) {
    while (c.charAt(0) === ' ') c = c.substring(1);
    if (c.indexOf(cname) === 0) return c.substring(cname.length, c.length);
  }
  return "";
}


document.getElementById("lastScore").textContent =
  getCookie("lastQuizScore") ? getCookie("lastQuizScore") + " points" : "No quiz taken yet";


let quizHistory = JSON.parse(localStorage.getItem("quizHistory")) || [];
let historyList = document.getElementById("quizHistory");

if (quizHistory.length === 0) {
  historyList.innerHTML = "<li>No quizzes completed yet.</li>";
} else {
  quizHistory.forEach(q => {
    let li = document.createElement("li");
    li.textContent = `${q.course} → ${q.score} / ${q.total}`;
    historyList.appendChild(li);
  });
}