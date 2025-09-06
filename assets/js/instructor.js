// assets/js/instructor.js
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
    },
    {
        name: "Clara Evans",
        photo: "https://i.pravatar.cc/150?img=3",
        bio: "Clara is a data scientist with expertise in Machine Learning, AI, and Data Visualization.",
        credentials: ["PhD in AI", "Machine Learning Expert"],
        courses: ["Introduction to AI", "Machine Learning", "Data Visualization"]
    },
    {
        name: "David Lee",
        photo: "https://i.pravatar.cc/150?img=4",
        bio: "David is a cloud architect and backend developer who teaches cloud computing and DevOps practices.",
        credentials: ["AWS Certified", "DevOps Specialist"],
        courses: ["Cloud Computing Fundamentals", "DevOps with Docker & Kubernetes", "Backend Development"]
    }
];

// This part is handled by PHP now, but kept for reference
/*
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
*/

function messageInstructor(index) {
    const instructor = instructors[index];
    const msg = prompt(`Send a message to ${instructor.name}:`);
    if (msg) {
        alert(`Your message has been sent to ${instructor.name}:\n"${msg}"`);
        // Add AJAX call to send message to server if needed
    }
}
