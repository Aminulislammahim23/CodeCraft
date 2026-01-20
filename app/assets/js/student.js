function showSection(sectionId, event) {
    const targetSection = document.getElementById(sectionId);
    if (!targetSection) return;
    if (history.pushState) {
        history.pushState(null, null, '#' + sectionId);
    } else {
        location.hash = '#' + sectionId;
    }
    document.querySelectorAll('.section').forEach(sec => {
        sec.classList.remove('active');
    });
    targetSection.classList.add('active');
    document.querySelectorAll('.menu li').forEach(li => {
        li.classList.remove('active');
    });
    if (event && event.target) {
        let clickedElement = event.target;
        if (clickedElement.tagName !== 'LI') {
            clickedElement = clickedElement.closest('li');
        }
        if (clickedElement) {
            clickedElement.classList.add('active');
        }
    }
}
window.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash.substring(1);
    if (hash && document.getElementById(hash)) {
        showSection(hash);
        const menuLinks = document.querySelectorAll('.menu li a');
        menuLinks.forEach(link => {
            if (link.getAttribute('onclick') && link.getAttribute('onclick').includes(hash)) {
                document.querySelectorAll('.menu li').forEach(li => li.classList.remove('active'));
                link.parentElement.classList.add('active');
            }
        });
    } else {
        const firstMenuItem = document.querySelector('.menu li');
        if (firstMenuItem && !document.querySelector('.menu li.active')) {
            firstMenuItem.classList.add('active');
        }
    }
});