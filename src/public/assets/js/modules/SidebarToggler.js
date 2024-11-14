export default class SidebarToggler {
    constructor(toggleButtonId = 'toggle-sidebar', navWrapperId = 'nav-wrapper') {
        this.toggleButton = document.getElementById(toggleButtonId);
        this.navWrapper = document.getElementById(navWrapperId);

        if (!this.toggleButton || !this.navWrapper) {
            console.error('Toggle button or nav wrapper not found. Please check your IDs.');
            return;
        }

        this.init();
    }

    init() {
        // Initial state: Only hide sidebar for mobile views
        this.handleResize();

        // Attach click event listener to the toggle button
        this.toggleButton.addEventListener('click', this.toggleNavWrapper.bind(this));

        // Add click event listener to all nav links
        this.addNavLinkListeners();

        // Ensure nav visibility is reset on window resize
        window.addEventListener('resize', this.handleResize.bind(this));
    }

    toggleNavWrapper() {
        if (this.isNavWrapperVisible()) {
            this.hideNavWrapper();
        } else {
            this.showNavWrapper();
        }
    }

    showNavWrapper() {
        this.navWrapper.style.left = '0'; // Slide into view
    }

    hideNavWrapper() {
		const isMobile = window.innerWidth <= 768; // Kun gjelde for mobile skjermer
		if (isMobile) {
			this.navWrapper.style.left = '-100vw'; // Skjuler sidebaren for mobile visninger
		}
	}


    isNavWrapperVisible() {
        // Check if nav-wrapper is visible based on its position
        return this.navWrapper.style.left === '0px';
    }

    addNavLinkListeners() {
        const navLinks = this.navWrapper.querySelectorAll('a.nav-item');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                // Hide nav-wrapper on link click (only for mobile)
                this.hideNavWrapper();
            });
        });
    }

    handleResize() {
        const isMobile = window.innerWidth <= 1200;

        if (!isMobile) {
            // Reset nav-wrapper for desktop views
            this.navWrapper.style.left = '0'; // Always visible
        } else {
            // Hide for mobile views
            this.hideNavWrapper();
        }
    }
}
