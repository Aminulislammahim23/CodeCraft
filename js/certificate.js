function generatePreview() {
      const name = document.getElementById('studentName').value;
      const date = document.getElementById('completionDate').value;
      document.getElementById('nameDisplay').textContent = name || '[Your Name]';
      document.getElementById('dateDisplay').textContent = date || '[Date]';
    }

    function downloadCertificate() {
      alert("PDF generation feature requires backend support or JavaScript libraries.");
    }

    function shareLink() {
      const dummyLink = "https://yourplatform.com/certificate/user123";
      navigator.clipboard.writeText(dummyLink);
      alert("Shareable link copied to clipboard: " + dummyLink);
    }