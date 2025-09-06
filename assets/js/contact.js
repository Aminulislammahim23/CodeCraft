const form = document.getElementById("contactForm");
const error = document.getElementById("error");
const captchaLabel = document.getElementById("captchaLabel");
const captchaInput = document.getElementById("captcha");

let captchaAnswer = 0;

// Function to generate a random addition CAPTCHA
function generateCaptcha() {
  const a = Math.floor(Math.random() * 10) + 1; // 1 to 10
  const b = Math.floor(Math.random() * 10) + 1; // 1 to 10
  captchaAnswer = a + b;
  captchaLabel.textContent = ` What is ${a} + ${b}?`;
}

// Call it on page load
generateCaptcha();

form.onsubmit = function(e) {
  e.preventDefault(); // Prevent form submission

  // Get form values
  const name = form.name.value.trim();
  const email = form.email.value.trim();
  const subject = form.subject.value.trim();
  const message = form.message.value.trim();
  const captcha = parseInt(form.captcha.value.trim());

  // Validate CAPTCHA
  if (captcha !== captchaAnswer) {
    error.textContent = "CAPTCHA answer is incorrect!";
    generateCaptcha(); // regenerate a new CAPTCHA
    captchaInput.value = "";
    return;
  }

  // If all fields are filled
  if (name && email && subject && message) {
    error.textContent = "";
    alert(`Thank you, ${name}! Your message has been submitted.\nA confirmation email has been sent to ${email}.`);

    // Reset form and generate new CAPTCHA
    form.reset();
    generateCaptcha();
  } else {
    error.textContent = "Please fill in all fields!";
  }
};
