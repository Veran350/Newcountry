// Initialize projects array from localStorage
let projects = JSON.parse(localStorage.getItem('projects')) || [];

// DOM Elements
const projectsContainer = document.getElementById('projectsContainer');

// Render all projects
function renderProjects() {
    projectsContainer.innerHTML = projects.map(project => `
        <div class="project-card">
            <img src="${project.image}" class="project-media" alt="${project.title}">
            <div class="project-info">
                <h3>${project.title}</h3>
                <p>${project.description}</p>
                <p><strong>Price:</strong> $${project.price.toLocaleString()}</p>
                <p><em>${project.category}</em></p>
            </div>
        </div>
    `).join('');
}

// Initial render when page loads
document.addEventListener('DOMContentLoaded', renderProjects);
