// Handle page transitions
document.addEventListener('DOMContentLoaded', function() {
    // Add transition for all internal links
    document.querySelectorAll('a').forEach(link => {
        if (link.href.includes(window.location.origin)) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.href;
                
                // Add exit animation class only to main content
                const contents = document.querySelectorAll('main, section:not(.navbar), .fundraisers-container');
                contents.forEach(content => {
                    if (!content.classList.contains('navbar')) {
                        content.classList.add('page-transition');
                    }
                });
                
                // Wait for animation to complete before changing page
                setTimeout(() => {
                    window.location.href = target;
                }, 500);
            });
        }
    });

    // Handle browser back button
    window.addEventListener('popstate', function() {
        const contents = document.querySelectorAll('main, section:not(.navbar), .fundraisers-container');
        contents.forEach(content => {
            if (!content.classList.contains('navbar')) {
                content.classList.add('page-exit');
            }
        });
    });
}); 