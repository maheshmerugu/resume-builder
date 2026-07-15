
import Alpine from 'alpinejs';
import resumeForm from './resume-form';

window.Alpine = Alpine;
window.resumeForm = resumeForm;

document.addEventListener('alpine:init', () => {
    Alpine.data('appShell', () => ({
        sidebarOpen: false,
        dark: false,

        init() {
            this.dark = document.documentElement.classList.contains('dark');
        },

        toggleSidebar() {
            this.sidebarOpen = ! this.sidebarOpen;
        },

        closeSidebar() {
            this.sidebarOpen = false;
        },

        toggleTheme() {
            this.dark = ! this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        },
    }));
});

Alpine.start();
