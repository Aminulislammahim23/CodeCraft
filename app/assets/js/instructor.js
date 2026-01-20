function showSection(section) {
    console.log("showSection called with:", section);
    try {
        hideAllSections();
        document.querySelectorAll(".menu li").forEach(li => {
            li.classList.remove("active");
        });
        const targetElement = document.getElementById(section + "Section") || document.getElementById(section);
        console.log("Looking for element:", section + "Section" , "or", section);
        console.log("Element found:", targetElement);
        if (section === "dashboard") {
            document.getElementById("dashboardSection").style.display = "grid";
            document.querySelector(".menu li:nth-child(1)").classList.add("active");
        } else if (section === "courses") {
            document.getElementById("courseSection").style.display = "block";
            document.querySelector(".menu li:nth-child(2)").classList.add("active");
        } else if (section === "lessons") {
            document.getElementById("lessonsSection").style.display = "block";
            document.querySelector(".menu li:nth-child(3)").classList.add("active");
        } else if (section === "addcourses") {
            document.getElementById("addcoursesSection").style.display = "block";
            document.querySelector(".menu li:nth-child(2)").classList.add("active");
            resetForms();
        } else if (section === "updatecoursesSection") {
            document.getElementById("updatecoursesSection").style.display = "block";
            document.querySelector(".menu li:nth-child(2)").classList.add("active");
            resetForms();
        } else if (section === "deletecoursesSection") {
            document.getElementById("deletecoursesSection").style.display = "block";
            document.querySelector(".menu li:nth-child(2)").classList.add("active");
            resetForms();
        } else if (section === "addlessonSection") {
            document.getElementById("addlessonSection").style.display = "block";
            document.querySelector(".menu li:nth-child(4)").classList.add("active");
            resetForms();
        } else if (section === "updateUsersSection") {
            document.getElementById("updateUsersSection").style.display = "block";
            document.querySelector(".menu li:nth-child(2)").classList.add("active");
            resetForms();
        } else if (section === "terminateUsersSection") {
            document.getElementById("terminateUsersSection").style.display = "block";
            document.querySelector(".menu li:nth-child(2)").classList.add("active");
            resetForms();
        } else if (section === "profile") {
            document.getElementById("profileSection").style.display = "block";
            document.querySelector(".menu li:nth-child(3)").classList.add("active");
        } else if (section === "settings") {
            document.getElementById("settingsSection").style.display = "block";
            document.querySelector(".menu li:nth-child(4)").classList.add("active");
        } else if (section === "logout") {
            logoutAdmin();
        } else {
            const genericElement = document.getElementById(section + "Section") || document.getElementById(section);
            if (genericElement) {
                genericElement.style.display = "block";
                const menuItems = document.querySelectorAll(".menu li");
                if (menuItems.length > 1) {
                    menuItems[1].classList.add("active");
                } else if (menuItems.length > 0) {
                    menuItems[0].classList.add("active");
                }
            }
        }
    } catch (error) {
        console.error("Error in showSection:", error);
        document.getElementById("dashboardSection").style.display = "grid";
    }
}
function hideAllSections() {
    const sections = document.querySelectorAll(".section");
    sections.forEach(sec => {
        if (!sec.classList.contains("main") && sec.id) {
            sec.style.display = "none";
        }
    });
}
function resetForms() {
    document.querySelectorAll("form").forEach(f => f.reset());
}
document.addEventListener('DOMContentLoaded', function() {
    hideAllSections();
    showSection("dashboard");
});
function logoutAdmin() {
    if (confirm("Are you sure you want to logout?")) {
        window.location.href = "../controller/logout.php";
    }
}
function addCourse() {
    window.location.href = "../../controllers/courseController/addCourse.php";
}
function updateCourse() {
    window.location.href = "../../controllers/courseController/updateCourse.php";
}
function deleteCourse() {
    window.location.href = "../../controllers/courseController/deleteCourse.php";
}
function addLesson() {
    event.preventDefault();
    const formData = new FormData(document.getElementById('addLessonForm'));
    fetch('../../controllers/lessonController/addLesson.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Lesson added successfully!');
            document.getElementById('addLessonForm').reset();
            location.reload();
        } else {
            alert('Error adding lesson: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the lesson');
    });
}
function editLesson(id) {
    alert('Edit functionality would be implemented here for lesson ID: ' + id);
}
function deleteLesson(id) {
    if(confirm('Are you sure you want to delete this lesson?')) {
        fetch('../../controllers/lessonController/deleteLesson.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Lesson deleted successfully!');
                location.reload();
            } else {
                alert('Error deleting lesson: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the lesson');
        });
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const lessonForm = document.getElementById('addLessonForm');
    if(lessonForm) {
        lessonForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addLesson();
        });
    }
    const searchCourseBtn = document.getElementById('searchCourseBtn');
    if(searchCourseBtn) {
        searchCourseBtn.addEventListener('click', searchCourseToUpdate);
    }
    const searchCourseDeleteBtn = document.getElementById('searchCourseDeleteBtn');
    if(searchCourseDeleteBtn) {
        searchCourseDeleteBtn.addEventListener('click', searchCourseToDelete);
    }
});
function searchCourseToUpdate() {
    const searchTerm = document.getElementById('searchCourse').value.trim();
    if (!searchTerm) {
        alert('Please enter a course ID or title to search.');
        return;
    }
    fetch('../../controllers/searchCourse.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'query=' + encodeURIComponent(searchTerm) + '&instructor_id=' + encodeURIComponent(getInstructorId())
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.course) {
            document.getElementById('update_course_id').value = data.course.id;
            document.getElementById('update_course_title').value = data.course.title;
            document.getElementById('update_course_description').value = data.course.description || '';
            document.getElementById('update_category_id').value = data.course.category_id;
            document.getElementById('update_difficulty').value = data.course.difficulty;
            document.getElementById('update_duration').value = data.course.duration;
            document.getElementById('update_price').value = parseFloat(data.course.price).toFixed(2);
            document.getElementById('update_rating').value = parseFloat(data.course.rating).toFixed(1);
            alert('Course found! Fill in the details and click Update Course.');
        } else {
            alert('Course not found or you do not have permission to update this course.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while searching for the course.');
    });
}
function searchCourseToDelete() {
    const searchTerm = document.getElementById('searchCourseDelete').value.trim();
    if (!searchTerm) {
        alert('Please enter a course ID or title to search.');
        return;
    }
    fetch('../../controllers/searchCourse.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'query=' + encodeURIComponent(searchTerm) + '&instructor_id=' + encodeURIComponent(getInstructorId())
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.course) {
            document.getElementById('delete_course_id').value = data.course.id;
            document.getElementById('delete_course_title').value = data.course.title;
            document.getElementById('delete_course_category').value = data.course.category_name || 'N/A';
            document.getElementById('delete_course_difficulty').value = data.course.difficulty;
            document.getElementById('delete_course_price').value = '$' + parseFloat(data.course.price).toFixed(2);
            alert('Course found! Click Delete Course to remove it.');
        } else {
            alert('Course not found or you do not have permission to delete this course.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while searching for the course.');
    });
}
function getInstructorId() {
    const userIdElement = document.querySelector('[data-user-id]');
    if (userIdElement) {
        return userIdElement.getAttribute('data-user-id');
    }
    return sessionStorage.getItem('userId') || 0;
}