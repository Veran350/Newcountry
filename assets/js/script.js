// assets/js/script.js

document.addEventListener('DOMContentLoaded', () => {
    // Smooth scrolling for internal links
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const target = document.getElementById(targetId);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Show a temporary notification when any form is submitted
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', () => {
            const notification = document.createElement('div');
            notification.textContent = 'Processing your request...';
            notification.className = 'notification';
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 2000);
        });
    });
});
