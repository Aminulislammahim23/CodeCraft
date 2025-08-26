const instructors = [
  {
    name: "Alice Johnson",
    photo: "https://i.pravatar.cc/150?img=1",
    bio: "Alice has 10 years of experience teaching Web Development. She specializes in HTML, CSS, and JavaScript.",
    credentials: ["PhD in Computer Science", "Certified Web Developer"],
    courses: ["HTML Basics", "CSS Fundamentals", "JavaScript Essentials"]
  },
  {
    name: "Bob Smith",
    photo: "https://i.pravatar.cc/150?img=2",
    bio: "Bob is a full-stack developer and instructor with a passion for teaching C++ and Python.",
    credentials: ["MSc in Software Engineering", "C++ Expert"],
    courses: ["C++ Fundamentals", "Python Programming"]
  }
];

const container = document.getElementById('instructors-container');

instructors.forEach((inst, index) => {
  const card = document.createElement('div');
  card.className = 'instructor-card';
  card.innerHTML = `
    <img src="${inst.photo}" alt="${inst.name}" class="instructor-photo">
    <div class="instructor-info">
      <h2>${inst.name}</h2>
      <p>${inst.bio}</p>
      <div class="badges">${inst.credentials.map(c => `<span>${c}</span>`).join('')}</div>
      <h4>Courses by ${inst.name}:</h4>
      <ul class="courses">${inst.courses.map(course => `<li>${course}</li>`).join('')}</ul>
      <button onclick="messageInstructor(${index})">Message Instructor</button>
    </div>
  `;
  container.appendChild(card);
});

function messageInstructor(index) {
  const instructor = instructors[index];
  const msg = prompt(`Send a message to ${instructor.name}:`);
  if(msg) alert(`Your message has been sent to ${instructor.name}:\n"${msg}"`);
}
