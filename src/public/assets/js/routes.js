import PageLoader from './modules/PageLoader.js';

export default function setupRoutes() {
    // Set active on nav item
    function updateActiveNav(route) {
		const navItems = document.querySelectorAll('#nav-items .nav-item');
		navItems.forEach(item => {
			const href = item.getAttribute('href');
			// Marker element som aktiv hvis ruten matcher eksakt
			if (route === href) {
				item.classList.add('active');
			} else {
				item.classList.remove('active');
			}
		});
	}


	// Middleware to update nav before route-change
    page((ctx, next) => {
        updateActiveNav(ctx.path);
        next();
    });


    // Routes
    page('/', () => {
        document.querySelector('#main').innerHTML = '<p>Welcome! Click a link to view content.</p>';
    });

    page('/books', () => {
        htmx.ajax('GET', '/api/books', '#main');
    });

    page('/movies', () => {
        htmx.ajax('GET', '/api/movies', '#main');
    });

    page('/components', () => {
        document.querySelector('#main').innerHTML = `
            <h2>Component Testing</h2>
            <div id="file-drop-zone" class="drop-zone">
                <p>Drag and drop files here, or click to select files</p>
            </div>
        `;
        import('./modules/FileUploader.js').then(({ default: FileUploader }) => {
            new FileUploader('file-drop-zone', '/api/upload');
        });
    });

    page('/help', () => {
        PageLoader.loadPage('/pages/help.html', 'main');
    });

	page('/settings', () => {
        document.querySelector('#main').innerHTML = `<h2>Settings</h2>`;
    });

	page('/me', () => {
        document.querySelector('#main').innerHTML = `<h2>My profile</h2>`;
    });

    // Start Page.js
    page();
}
