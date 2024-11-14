export default class ThemeToggle {
    constructor(iconId = 'theme-icon', themePath = '/assets/css/', defaultTheme = 'light') {
        this.themePath = themePath;
        this.defaultTheme = defaultTheme;
        this.currentTheme = localStorage.getItem('theme') || defaultTheme;
        this.icon = document.getElementById(iconId);
        this.iconOn = '<i class="fa-regular fa-fw fa-sun"></i>';
        this.iconOff = '<i class="fa-regular fa-fw fa-moon"></i>';

        // Initialize theme on load
        this.init();
    }

    init() {
        // Set the current theme with a small delay for smooth initialization
        setTimeout(() => {
            this.setTheme(this.currentTheme);
        }, 200);
    }

    toggle() {
        // Toggle between themes
        this.currentTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(this.currentTheme);
    }

    setTheme(theme) {
        // Update theme in localStorage
        localStorage.setItem('theme', theme);

        // Update the CSS theme file
        document.getElementById("css-theme").setAttribute("href", `${this.themePath}${theme}.css`);

        // Update elements with Bootstrap color schemes
        const elementsToToggle = document.querySelectorAll('[data-bs-color-scheme]');
        elementsToToggle.forEach(el => {
            el.setAttribute('data-bs-color-scheme', theme);
        });

        // Update table classes
        const tables = document.querySelectorAll('.table');
        tables.forEach(table => {
            if (theme === 'dark') {
                table.classList.remove('table-light');
                table.classList.add('table-dark');
            } else {
                table.classList.remove('table-dark');
                table.classList.add('table-light');
            }
        });

        // Update the theme icon
        if (theme === 'dark') {
            this.icon.innerHTML = this.iconOff;
        } else {
            this.icon.innerHTML = this.iconOn;
        }

        // Update button classes
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            if (theme === 'dark') {
                button.classList.remove('btn-light');
                button.classList.add('btn-dark');
            } else {
                button.classList.remove('btn-dark');
                button.classList.add('btn-light');
            }
        });
    }
}
