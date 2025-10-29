
// Smooth scroll functionality
document.getElementById('scrollToSecond').addEventListener('click', function() {
    document.getElementById('second-section').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
});

// Progress bar functionality
function updateProgressBar(raised, goal) {
    const progressBar = document.querySelector('.progress-bar');
    const percentage = (raised / goal) * 100;
    progressBar.style.width = percentage + '%';
}

// Initialize progress bar with current values
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in class to elements
    const animatedElements = document.querySelectorAll('.hero h1, .hero p, .hero button, .features, .fundraiser-content');
    
    animatedElements.forEach(element => {
        element.classList.add('fade-in');
    });

    updateProgressBar(1500, 50000); // Set progress bar to 3%
});

// Image modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeBtn = document.querySelector('.close-modal');
    const featureCards = document.querySelectorAll('.feature-card img');

    // Open modal
    featureCards.forEach(card => {
        card.addEventListener('click', function() {
            modalImg.src = this.src;
            modal.style.display = 'flex';
            // Use setTimeout to ensure display: flex is applied before adding active class
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
        });
    });

    // Close modal functions
    function closeModal() {
        modal.classList.remove('active');
        // Wait for transition to complete before hiding modal
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Close modal with button
    closeBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
}); 