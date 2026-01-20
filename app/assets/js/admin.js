function showSection(section) {
    hideAllSections();
    document.querySelectorAll(".menu li").forEach(li => {
        li.classList.remove("active");
    });
    if (section === "dashboard") {
        document.getElementById("dashboardSection").style.display = "grid";
        document.querySelector(".menu li:nth-child(1)").classList.add("active");
    }
    if (section === "users") {
        document.getElementById("usersSection").style.display = "block";
        document.querySelector(".menu li:nth-child(2)").classList.add("active");
    }
    if (section === "courses") {
        document.getElementById("courseSection").style.display = "block";
        document.querySelector(".menu li:nth-child(3)").classList.add("active");
    }
    if (section === "addcoursesSection") {
        document.getElementById("addcoursesSection").style.display = "block";
        document.querySelector(".menu li:nth-child(3)").classList.add("active");
        resetForms();
    }
    if (section === "updatecoursesSection") {
        document.getElementById("updatecoursesSection").style.display = "block";
        document.querySelector(".menu li:nth-child(3)").classList.add("active");
        resetForms();
    }
    if (section === "deletecoursesSection") {
        document.getElementById("deletecoursesSection").style.display = "block";
        document.querySelector(".menu li:nth-child(3)").classList.add("active");
        resetForms();
    }
    if (section === "addUsersSection") {
        document.getElementById("addUsersSection").style.display = "block";
        document.querySelector(".menu li:nth-child(2)").classList.add("active");
        resetForms();
    }
    if (section === "updateUsersSection") {
        document.getElementById("updateUsersSection").style.display = "block";
        document.querySelector(".menu li:nth-child(2)").classList.add("active");
        resetForms();
    }
    if (section === "terminateUsersSection") {
        document.getElementById("terminateUsersSection").style.display = "block";
        document.querySelector(".menu li:nth-child(2)").classList.add("active");
        resetForms();
    }
    if (section === "profile") {
        document.getElementById("profileSection").style.display = "block";
        document.querySelector(".menu li:nth-child(4)").classList.add("active");
    }
    if (section === "settings") {
        document.getElementById("settingsSection").style.display = "block";
        document.querySelector(".menu li:nth-child(5)").classList.add("active");
    }
    if (section === "logout") {
        logoutAdmin();
    }
}
function hideAllSections() {
    document.querySelectorAll(".section").forEach(sec => {
        sec.style.display = "none";
    });
}
function resetForms() {
    document.querySelectorAll("form").forEach(f => f.reset());
}
function logoutAdmin() {
    if (confirm("Are you sure you want to logout?")) {
        window.location.href = "../controller/logout.php";
    }
}
window.addEventListener('DOMContentLoaded', function() {
    hideAllSections();
    showSection("dashboard");
});
document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.getElementById("searchBtn");
    if (searchBtn) {
        searchBtn.addEventListener("click", function () {
            const query = document.getElementById("searchUser").value.trim();
            if (query === "") {
                alert("Please enter email or name");
                return;
            }
            fetch("../../controllers/searchUser.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "query=" + encodeURIComponent(query)
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok (' + response.status + ')');
                return response.json().catch(err => response.text().then(txt => { throw new Error('Invalid JSON response:\n' + txt); }));
            })
            .then(data => {
                console.log('Parsed JSON:', data);
                if (data.status === "found") {
                    const setIf = (id, val) => { const el = document.getElementById(id); if (el) el.value = (val !== undefined && val !== null) ? val : ''; else console.warn('Missing element:', id); };
                    setIf("user_id", data.user.id);
                    setIf("full_name", data.user.full_name);
                    setIf("email", data.user.email);
                    setIf("role", data.user.role);
                    setIf("password", "");
                    alert("User found! You can now update the information.");
                } else {
                    alert("User not found");
                    ['user_id','full_name','email','password'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.value = '';
                    });
                    const roleEl = document.getElementById('role'); if (roleEl) roleEl.value = 'student';
                }
            })
            .catch(error => {
                console.error("Search error:", error);
                alert('Search failed: ' + error.message);
            });
        });
    }
    const searchTerminateBtn = document.getElementById("searchTerminateBtn");
    if (searchTerminateBtn) {
        searchTerminateBtn.addEventListener("click", function () {
            const query = document.getElementById("searchUserTerminate").value.trim();
            if (query === "") {
                alert("Please enter email, name, or user ID");
                return;
            }
            fetch("../../controllers/searchUser.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "query=" + encodeURIComponent(query)
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok (' + response.status + ')');
                return response.json().catch(err => response.text().then(txt => { throw new Error('Invalid JSON response:\n' + txt); }));
            })
            .then(data => {
                console.log('Parsed JSON:', data);
                if (data.status === "found") {
                    document.getElementById("terminate_user_id").value = data.user.id || '';
                    document.getElementById("terminate_full_name").value = data.user.full_name || '';
                    document.getElementById("terminate_email").value = data.user.email || '';
                    document.getElementById("terminate_role").value = data.user.role || '';
                    alert("User found! You can now terminate this user.");
                } else {
                    alert("User not found");
                    document.getElementById("terminate_user_id").value = '';
                    document.getElementById("terminate_full_name").value = '';
                    document.getElementById("terminate_email").value = '';
                    document.getElementById("terminate_role").value = '';
                }
            })
            .catch(error => {
                console.error("Search error:", error);
                alert('Search failed: ' + error.message);
            });
        });
    }
    const searchCourseBtn = document.getElementById("searchCourseBtn");
    if (searchCourseBtn) {
        searchCourseBtn.addEventListener("click", function () {
            const query = document.getElementById("searchCourse").value.trim();
            if (query === "") {
                alert("Please enter course ID or title");
                return;
            }
            fetch("../../controllers/searchCourse.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "query=" + encodeURIComponent(query)
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json().catch(err => response.text().then(txt => { throw new Error('Invalid JSON: ' + txt); }));
            })
            .then(data => {
                if (data.status === "found") {
                    document.getElementById("update_course_id").value = data.course.id || '';
                    document.getElementById("update_course_title").value = data.course.title || '';
                    document.getElementById("update_course_description").value = data.course.description || '';
                    document.getElementById("update_category_id").value = data.course.category_id || '1';
                    document.getElementById("update_difficulty").value = data.course.difficulty || 'Beginner';
                    document.getElementById("update_duration").value = data.course.duration || '';
                    document.getElementById("update_price").value = data.course.price || '0';
                    document.getElementById("update_rating").value = data.course.rating || '0';
                    alert("Course found! You can now update the information.");
                } else {
                    alert("Course not found");
                    document.getElementById("update_course_id").value = '';
                    document.getElementById("update_course_title").value = '';
                    document.getElementById("update_course_description").value = '';
                    document.getElementById("update_duration").value = '';
                    document.getElementById("update_price").value = '0';
                    document.getElementById("update_rating").value = '0';
                }
            })
            .catch(error => {
                console.error("Search error:", error);
                alert('Search failed: ' + error.message);
            });
        });
    }
    const searchCourseDeleteBtn = document.getElementById("searchCourseDeleteBtn");
    if (searchCourseDeleteBtn) {
        searchCourseDeleteBtn.addEventListener("click", function () {
            const query = document.getElementById("searchCourseDelete").value.trim();
            if (query === "") {
                alert("Please enter course ID or title");
                return;
            }
            fetch("../../controllers/searchCourse.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "query=" + encodeURIComponent(query)
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json().catch(err => response.text().then(txt => { throw new Error('Invalid JSON: ' + txt); }));
            })
            .then(data => {
                if (data.status === "found") {
                    document.getElementById("delete_course_id").value = data.course.id || '';
                    document.getElementById("delete_course_title").value = data.course.title || '';
                    document.getElementById("delete_course_category").value = data.course.category_id || '';
                    document.getElementById("delete_course_difficulty").value = data.course.difficulty || '';
                    document.getElementById("delete_course_price").value = '$' + (data.course.price || '0');
                    alert("Course found! You can now delete it.");
                } else {
                    alert("Course not found");
                    document.getElementById("delete_course_id").value = '';
                    document.getElementById("delete_course_title").value = '';
                    document.getElementById("delete_course_category").value = '';
                    document.getElementById("delete_course_difficulty").value = '';
                    document.getElementById("delete_course_price").value = '';
                }
            })
            .catch(error => {
                console.error("Search error:", error);
                alert('Search failed: ' + error.message);
            });
        });
    }
});