// assets/js/admin.js
function showSection(sectionId) {
    const sections = document.getElementsByClassName('section');
    for (let section of sections) {
        section.style.display = 'none';
    }
    document.getElementById(sectionId).style.display = 'block';
}

// Initial load
document.addEventListener('DOMContentLoaded', () => {
    showSection('dashboard');
});

// Student Management
function addStudent() {
    alert('Add Student form will open here.');
    // Implement form or modal for adding student
}

function editStudent(button) {
    const row = button.parentElement.parentElement;
    alert(`Edit student: ${row.cells[0].textContent}`);
    // Implement edit logic
}

function deleteStudent(button) {
    if (confirm('Are you sure you want to delete this student?')) {
        const row = button.parentElement.parentElement;
        row.remove();
        alert('Student deleted.');
    }
}

// Instructor Management
function addInstructor() {
    alert('Add Instructor form will open here.');
    // Implement form or modal for adding instructor
}

function editInstructor(button) {
    const row = button.parentElement.parentElement;
    alert(`Edit instructor: ${row.cells[0].textContent}`);
    // Implement edit logic
}

function deleteInstructor(button) {
    if (confirm('Are you sure you want to delete this instructor?')) {
        const row = button.parentElement.parentElement;
        row.remove();
        alert('Instructor deleted.');
    }
}

// Certificate Generation
function generateCertificate() {
    const studentSelect = document.getElementById('studentSelect');
    const courseSelect = document.getElementById('courseSelect');
    const studentName = studentSelect.options[studentSelect.selectedIndex].text;
    const courseName = courseSelect.options[courseSelect.selectedIndex].text;

    if (studentName === '-- Select Student --' || courseName === '-- Select Course --') {
        alert('Please select both a student and a course.');
        return;
    }

    document.getElementById('certStudentName').textContent = studentName;
    document.getElementById('certCourseName').textContent = courseName;
    document.getElementById('certificatePreview').style.display = 'block';
}

function downloadCertificate() {
    alert('Downloading certificate PDF... (Implement PDF generation logic here)');
    // Add logic to generate and download PDF using a library like jsPDF
}

// Profile
function editProfile() {
    alert('Edit profile form will open here.');
    // Implement edit profile logic
}
