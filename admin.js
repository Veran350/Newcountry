// Initialize projects array from localStorage
let projects = JSON.parse(localStorage.getItem('projects')) || [];

// DOM Elements
const projectForm = document.getElementById('projectForm');
const projectsList = document.getElementById('projectsList');

// Form submit handler
projectForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const newProject = {
        id: Date.now(),
        title: document.getElementById('title').value,
        description: document.getElementById('description').value,
        price: parseFloat(document.getElementById('price').value),
        category: document.getElementById('category').value,
        image: document.getElementById('image').value
    };

    projects.push(newProject);
    localStorage.setItem('projects', JSON.stringify(projects));
    renderProjects();
    e.target.reset();
});

// Delete project function
window.deleteProject = (index) => {
    projects.splice(index, 1);
    localStorage.setItem('projects', JSON.stringify(projects));
    renderProjects();
};

// Render projects in admin panel
function renderProjects() {
    projectsList.innerHTML = projects.map((project, index) => `
        <div class="project-card">
            <h3>${project.title}</h3>
            <p>${project.description}</p>
            <button onclick="deleteProject(${index})">Delete</button>
        </div>
    `).join('');
}

// Initial render
document.addEventListener('DOMContentLoaded', renderProjects);
