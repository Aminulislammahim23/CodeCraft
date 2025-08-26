document.addEventListener('DOMContentLoaded', function () {
    const courses = [
        {
            title: 'HTML for Beginners',
            progress: 75,
            modules: [
                { name: 'Introduction to HTML', status: 'Completed' },
                { name: 'HTML Tags and Elements', status: 'Completed' },
                { name: 'Forms and Inputs', status: 'In Progress' },
                { name: 'HTML5 Semantic Tags', status: 'Not Started' }
            ],
            resumeLink: '#'
        },
        {
            title: 'CSS for Beginners',
            progress: 40,
            modules: [
                { name: 'Introduction to CSS', status: 'Completed' },
                { name: 'Selectors and Properties', status: 'In Progress' },
                { name: 'Flexbox', status: 'Not Started' },
                { name: 'CSS Grid', status: 'Not Started' }
            ],
            resumeLink: '#'
        },
        {
            title: 'JavaScript for Beginners',
            progress: 100,
            modules: [
                { name: 'Introduction to JavaScript', status: 'Completed' },
                { name: 'Variables and Data Types', status: 'Completed' },
                { name: 'Functions and Scope', status: 'Completed' },
                { name: 'DOM Manipulation', status: 'Completed' }
            ],
            resumeLink: '#'
        }
    ];

    const courseProgressList = document.getElementById('course-progress-list');

    courses.forEach(course => {
        const card = document.createElement('div');
        card.className = 'course-progress-card';

        let modulesHtml = '';
        course.modules.forEach(module => {
            let statusClass = '';
            if (module.status === 'Completed') statusClass = 'status-completed';
            else if (module.status === 'In Progress') statusClass = 'status-in-progress';
            else statusClass = 'status-not-started';

            modulesHtml += `<li>${module.name} <span class="${statusClass}">${module.status}</span></li>`;
        });

        card.innerHTML = `
            <h3>${course.title}</h3>
            <div class="progress-container">
                <div class="progress-circle" style="background: conic-gradient(#4caf50 ${course.progress}%, #e0e0e0 ${course.progress}%)">
                    <span class="progress-text">${course.progress}%</span>
                </div>
            </div>
            <h4>Module Status</h4>
            <ul class="module-status">
                ${modulesHtml}
            </ul>
            <a href="${course.resumeLink}" class="btn-resume">Continue Learning</a>
        `;

        courseProgressList.appendChild(card);
    });
});
