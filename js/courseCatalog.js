function searchCertificate() {
  const input = document.getElementById('searchInput').value.trim().toLowerCase();


  const dummyCertificate = {
    course_id: "CS101",
    user_id: "user_456",
    certificate_id: "cert_123456",
    date_issued: "2025-07-20",
    download_link: "https://example.com/certificates/cert_123456.pdf"
  };


  if (input === "cs101" || input === "user_456") {
    document.getElementById('courseId').textContent = dummyCertificate.course_id;
    document.getElementById('userId').textContent = dummyCertificate.user_id;
    document.getElementById('certificateId').textContent = dummyCertificate.certificate_id;
    document.getElementById('dateIssued').textContent = dummyCertificate.date_issued;
    document.getElementById('downloadLink').href = dummyCertificate.download_link;

    document.getElementById('certificateDisplay').style.display = 'block';
  } else {
    alert("Certificate not found. Try with 'cs101' or 'user_456' for demo.");
    document.getElementById('certificateDisplay').style.display = 'none';
  }
}
