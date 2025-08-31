function generatePreview() {
    const name = document.getElementById("studentName").value || "[Your Name]";
    const date = document.getElementById("completionDate").value || "[Date]";
    document.getElementById("nameDisplay").textContent = name;
    document.getElementById("dateDisplay").textContent = date;
}

async function generateCertificate() {
    const { jsPDF } = window.jspdf;
    const studentName = document.getElementById('studentName').value.trim();
    const dateInput = document.getElementById('completionDate').value.trim();
    const courseName = "CodeCraft Online Course";
    const date = dateInput ? new Date(dateInput).toLocaleDateString() : new Date().toLocaleDateString();

    if(!studentName || !courseName) {
        alert("Please enter your name and course name.");
        return;
    }

    const doc = new jsPDF({ orientation: "landscape", unit: "pt", format: "a4" });
    doc.setFillColor(240, 240, 255);
    doc.rect(0, 0, doc.internal.pageSize.width, doc.internal.pageSize.height, 'F');
    doc.setFontSize(36); doc.setTextColor(20, 50, 120); doc.setFont("helvetica", "bold");
    doc.text("Certificate of Completion", doc.internal.pageSize.width/2, 150, {align: "center"});
    doc.setFontSize(28); doc.setTextColor(0,0,0); doc.setFont("helvetica", "normal");
    doc.text(`This certificate is proudly presented to`, doc.internal.pageSize.width/2, 220, {align: "center"});
    doc.setFontSize(32); doc.setFont("helvetica", "bold"); doc.text(studentName, doc.internal.pageSize.width/2, 260, {align: "center"});
    doc.setFontSize(20); doc.setFont("helvetica", "normal"); doc.text(`for successfully completing the course`, doc.internal.pageSize.width/2, 300, {align: "center"});
    doc.setFontSize(24); doc.setFont("helvetica", "bold"); doc.text(courseName, doc.internal.pageSize.width/2, 340, {align: "center"});
    doc.setFontSize(16); doc.setFont("helvetica", "normal"); doc.text(`Date: ${date}`, doc.internal.pageSize.width/2, 400, {align: "center"});
    doc.save(`${studentName}_Certificate.pdf`);
}

function generateShareLink() {
    const name = encodeURIComponent(document.getElementById("studentName").value.trim());
    const date = encodeURIComponent(document.getElementById("completionDate").value.trim());
    if (!name || !date) { alert("Please enter your name and completion date first!"); return; }
    const shareUrl = `${window.location.origin}${window.location.pathname}?name=${name}&date=${date}`;
    navigator.clipboard.writeText(shareUrl).then(() => alert("Shareable link copied!"));
    console.log("Shareable Link:", shareUrl);
}

window.onload = function () {
    const params = new URLSearchParams(window.location.search);
    const name = params.get("name");
    const date = params.get("date");
    if (name) { document.getElementById("studentName").value = decodeURIComponent(name); document.getElementById("nameDisplay").textContent = decodeURIComponent(name); }
    if (date) { document.getElementById("completionDate").value = decodeURIComponent(date); document.getElementById("dateDisplay").textContent = decodeURIComponent(date); }
    document.getElementById("studentName").addEventListener("input", generatePreview);
    document.getElementById("completionDate").addEventListener("input", generatePreview);
};