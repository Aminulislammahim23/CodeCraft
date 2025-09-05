document.addEventListener('DOMContentLoaded', function () {
 
    const courseDataElement = document.getElementById('course-data');
    const courseData = courseDataElement ? JSON.parse(courseDataElement.textContent) : [];

    const courseProgressList = document.getElementById('course-progress-list');

    
    const moduleTemplates = {
        html: [
            { name: 'Introduction to HTML', status: 'Not Started' },
            { name: 'HTML Tags and Elements', status: 'Not Started' },
            { name: 'Forms and Inputs', status: 'Not Started' },
            { name: 'HTML5 Semantic Tags', status: 'Not Started' }
        ],
        css: [
            { name: 'Introduction to CSS', status: 'Not Started' },
            { name: 'Selectors and Properties', status: 'Not Started' },
            { name: 'Flexbox', status: 'Not Started' },
            { name: 'CSS Grid', status: 'Not Started' }
        ],
        js: [
            { name: 'Introduction to JavaScript', status: 'Not Started' },
            { name: 'Variables and Data Types', status: 'Not Started' },
            { name: 'Functions and Scope', status: 'Not Started' },
            { name: 'DOM Manipulation', status: 'Not Started' }
        ],
        cplusplus: [
            { name: 'Introduction to C++', status: 'Not Started' },
            { name: 'Variables and Data Types', status: 'Not Started' },
            { name: 'Control Structures', status: 'Not Started' },
            { name: 'Functions', status: 'Not Started' }
        ],
        sql: [
            { name: 'Introduction to SQL', status: 'Not Started' },
            { name: 'Basic Queries', status: 'Not Started' },
            { name: 'Joins and Relationships', status: 'Not Started' },
            { name: 'Aggregations', status: 'Not Started' }
        ],
        csharp: [
            { name: 'Introduction to C#', status: 'Not Started' },
            { name: 'Variables and Types', status: 'Not Started' },
            { name: 'Control Flow', status: 'Not Started' },
            { name: 'Methods', status: 'Not Started' }
        ]
    };

  
    courseProgressList.innerHTML = '';

    if (courseData.length > 0) {
        courseData.forEach(course => {
            const card = document.createElement('div');
            card.className = 'course-progress-card';

          
            const modules = moduleTemplates[course.id] || [];

            let modulesHtml = '';
            modules.forEach(module => {
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
    } else {
        courseProgressList.innerHTML = '<p>No courses enrolled yet. <a href="../view/enrollment.php">Enroll now!</a></p>';
    }
});
