function showSection(sectionId) {
      document.querySelectorAll('.section').forEach(sec => sec.style.display = 'none');
      document.getElementById(sectionId).style.display = 'block';
    }

    function addCourse() {
      alert("New course added (demo)!");
    }