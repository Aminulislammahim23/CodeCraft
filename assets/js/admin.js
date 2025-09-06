function showSection(id){
    const sections = document.querySelectorAll('.section');
    sections.forEach(sec => sec.style.display = 'none');

    const target = document.getElementById(id);
    if(target) target.style.display = 'block';
}

// page load default show dashboard
document.addEventListener('DOMContentLoaded', () => {
    showSection('dashboard');
});

    function addCourse() {
      alert("New course added (demo)!");
    }

    function addInstructor() {
      alert("New instructor added (demo)!");
    }


    function showSection(id){
    const sections = document.querySelectorAll('.section');
    sections.forEach(sec => sec.style.display = 'none');

    const target = document.getElementById(id);
    if(target){
        target.style.display = 'block';
    }
}