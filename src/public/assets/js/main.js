import ThemeToggler from './modules/ThemeToggle.js';
import SidebarToggler from './modules/SidebarToggler.js';
import setupRoutes from './routes.js';

document.addEventListener('DOMContentLoaded', () => {
    // Init ThemeToggler
    const themeToggler = new ThemeToggler('theme-icon');

	// Legg til klikkhendelse for å toggling
    document.getElementById('theme-icon').addEventListener('click', (e) => {
        e.preventDefault(); // Hindre standard handling for <a> element
        themeToggler.toggle();
    });

	// Init SidebarToggler
    new SidebarToggler('toggle-sidebar', 'nav-wrapper');

    // Setup routes
    setupRoutes();
});
