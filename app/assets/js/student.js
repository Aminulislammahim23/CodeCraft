function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(sec => {
        sec.classList.remove('active');
    });

    document.getElementById(sectionId).classList.add('active');

    document.querySelectorAll('.menu li').forEach(li => {
        li.classList.remove('active');
    });

    event.target.closest('li').classList.add('active');
}