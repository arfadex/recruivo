// Theme toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('recruivo:theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('recruivo:theme', 'dark');
            }
        });
    }
});

// Initialize theme on page load
(function() {
    try {
        const theme = localStorage.getItem('recruivo:theme');
        // Default to dark mode unless explicitly set to light
        const shouldUseDark = theme !== 'light';
        
        if (shouldUseDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    } catch (e) {
        // Fallback to default theme if localStorage is not available
        document.documentElement.classList.add('dark');
    }
})();

