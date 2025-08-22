// FILTER COURSES
    const categoryFilter = document.getElementById("category");
    const levelFilter = document.getElementById("level");
    const durationFilter = document.getElementById("duration");
    const courses = document.querySelectorAll(".course-card");

    function filterCourses() {
      const category = categoryFilter.value;
      const level = levelFilter.value;
      const duration = durationFilter.value;

      courses.forEach(course => {
        const courseCategory = course.getAttribute("data-category");
        const courseLevel = course.getAttribute("data-level");
        const courseDuration = course.getAttribute("data-duration");

        let show = true;

        if (category !== "All" && category !== courseCategory) show = false;
        if (level !== "All" && level !== courseLevel) show = false;
        if (duration !== "All" && duration !== courseDuration) show = false;

        course.style.display = show ? "block" : "none";
      });
    }

    categoryFilter.addEventListener("change", filterCourses);
    levelFilter.addEventListener("change", filterCourses);
    durationFilter.addEventListener("change", filterCourses);

    // MODAL HANDLING
    const modal = document.getElementById("modal");
    const modalBody = document.getElementById("modal-body");
    const closeBtn = document.getElementById("close-btn");

    // View Details
    document.querySelectorAll(".details-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const course = btn.getAttribute("data-course");
        modal.style.display = "flex";
        modalBody.innerHTML = `
          <h2>${course.toUpperCase()} Course Details</h2>
          <p>Welcome to the ${course.toUpperCase()} course. Here you will learn fundamentals, projects, and advanced concepts.</p>
          <ul>
            <li>Introduction</li>
            <li>Core Concepts</li>
            <li>Hands-on Projects</li>
            <li>Final Quiz</li>
          </ul>
        `;
      });
    });

    // Preview
    document.querySelectorAll(".preview-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const videoSrc = btn.getAttribute("data-video");
        modal.style.display = "flex";
        modalBody.innerHTML = `
          <h2>Course Preview</h2>
          <video controls>
            <source src="${videoSrc}" type="video/mp4">
            Your browser does not support video.
          </video>
        `;
      });
    });

    // Close Modal
    closeBtn.addEventListener("click", () => modal.style.display = "none");
    window.addEventListener("click", (e) => { if (e.target === modal) modal.style.display = "none"; });