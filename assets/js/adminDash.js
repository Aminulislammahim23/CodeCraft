function showSection(id){
    const sections = document.querySelectorAll('.section');
    sections.forEach(sec => sec.style.display = 'none');

    const target = document.getElementById(id);
    if(target) target.style.display = 'block';
}