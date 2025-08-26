const faqs = [
  {
    question: "How do I enroll in a course?",
    answer: "Click 'Enroll Now' on the course page and complete the registration steps.",
    category: "Enrollment"
  },
  {
    question: "What payment methods are accepted?",
    answer: "We accept Credit/Debit cards and PayPal for all paid courses.",
    category: "Payments"
  },
  {
    question: "I forgot my password. What should I do?",
    answer: "Click 'Forgot Password' on the login page to reset your password via email.",
    category: "Technical"
  },
  {
    question: "Can I get a refund for a paid course?",
    answer: "Refunds are available within 7 days of purchase if less than 30% of the course is completed.",
    category: "Payments"
  }
];

const faqList = document.getElementById('faqList');
const searchInput = document.getElementById('faqSearch');

function renderFaqs(filter = "") {
  faqList.innerHTML = "";
  const filteredFaqs = faqs.filter(f => f.question.toLowerCase().includes(filter.toLowerCase()));
  filteredFaqs.forEach((f, index) => {
    const div = document.createElement('div');
    div.className = "faq-item";
    div.innerHTML = `<strong>${f.question}</strong>
                     <div class="faq-answer" id="answer-${index}">${f.answer}</div>`;
    div.addEventListener('click', () => {
      const answerDiv = document.getElementById(`answer-${index}`);
      answerDiv.style.display = answerDiv.style.display === "block" ? "none" : "block";
    });
    faqList.appendChild(div);
  });
}

// Initial render
renderFaqs();

// Search functionality
searchInput.addEventListener('input', (e) => {
  renderFaqs(e.target.value);
});

// Support button
document.getElementById('supportBtn').addEventListener('click', () => {
  const message = prompt("Please describe your issue. Our support team will contact you shortly.");
  if(message) alert("Support ticket submitted. We'll get back to you soon!");
});
