// Navbar scroll effect
window.addEventListener('scroll', function() {
    const nav = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});

// Enhanced smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const target = document.querySelector(targetId);
        
        if (target) {
            // Add offset for fixed navbar
            const navHeight = document.querySelector('.navbar').offsetHeight;
            const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navHeight;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// User icon animation
const userIcon = document.querySelector('.user-icon');
userIcon.addEventListener('mouseenter', function() {
    this.style.transform = 'scale(1.1) rotate(5deg)';
    this.style.transition = 'all 0.3s ease';
});

userIcon.addEventListener('mouseleave', function() {
    this.style.transform = 'scale(1) rotate(0deg)';
});

// Slideshow functionality
function startSlideshow() {
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;
    
    slides[0].classList.add('active');
    
    setInterval(() => {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }, 5000);
}

// Counter animation
function animateCounter() {
    const counter = document.getElementById('counter');
    const target = 10203290;
    const duration = 2000;
    const start = 0;
    const increment = target / (duration / 16);
    
    let current = start;
    
    function updateCounter() {
        current += increment;
        if (current >= target) {
            counter.textContent = target.toLocaleString();
            return;
        }
        counter.textContent = Math.floor(current).toLocaleString();
        requestAnimationFrame(updateCounter);
    }
    
    updateCounter();
}

// Start counter animation when element is in view
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounter();
            observer.unobserve(entry.target);
        }
    });
});

// Initialize all animations when page loads
window.addEventListener('load', () => {
    startSlideshow();
    observer.observe(document.querySelector('.donation-counter'));
}); 