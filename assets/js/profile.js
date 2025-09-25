function showSection(sectionId) {
  const sections = document.querySelectorAll('.section');
  sections.forEach(sec => sec.style.display = "none");

  const activeSection = document.getElementById(sectionId);
  if (activeSection) {
    activeSection.style.display = "block";
  }
}


function saveProfile(event) {
  event.preventDefault();
  alert("Profile saved! (connect backend update here)");
  showSection('view');
}


function previewAvatar(event) {
  const reader = new FileReader();
  reader.onload = function(){
    const output = document.getElementById('avatarPreview');
    output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
}


function zoomAvatar(value) {
  document.getElementById('avatarPreview').style.transform = `scale(${value})`;
}


function applyAvatar() {
  alert("Avatar set successfully! (backend upload needed)");
}


function resetAvatar() {
  const img = document.getElementById('avatarPreview');
  img.src = "https://via.placeholder.com/150";
  img.style.transform = "scale(1)";
  document.getElementById('avatarFile').value = "";
  document.getElementById('zoom').value = 1;
}