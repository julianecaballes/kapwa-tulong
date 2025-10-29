// DOM Elements
const fundraisersGrid = document.querySelector('.fundraisers-grid');
const categoryFilter = document.getElementById('categoryFilter');
const dateFilter = document.getElementById('dateFilter');
const clearFiltersBtn = document.getElementById('clearFilters');

// Check for category parameter in URL
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Set initial category filter if specified in URL
const initialCategory = getUrlParameter('category');
if (initialCategory) {
    categoryFilter.value = initialCategory;
    filterFundraisers(); // Apply the filter immediately
}

// Create fundraiser card
function createFundraiserCard(fundraiser) {
    // Format the date
    const formattedDate = new Date(fundraiser.date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });

    return `
        <div class="fundraiser-card">
            <img src="${fundraiser.image}" alt="${fundraiser.title}" class="fundraiser-image">
            <div class="fundraiser-content">
                <h3 class="fundraiser-title">${fundraiser.title}</h3>
                <p class="fundraiser-description">${fundraiser.description}</p>
                <div class="fundraiser-meta">
                    <span class="category-tag">${fundraiser.category}</span>
                    <span class="fundraiser-date">${formattedDate}</span>
                </div>
                <a href="#" class="donate-btn">Donate Now</a>
            </div>
        </div>
    `;
}

// Filter fundraisers
function filterFundraisers() {
    const selectedCategory = categoryFilter.value;
    const selectedDate = dateFilter.value;

    const filteredFundraisers = fundraisers.filter(fundraiser => {
        const categoryMatch = !selectedCategory || fundraiser.category === selectedCategory;
        const dateMatch = !selectedDate || fundraiser.date === selectedDate;
        return categoryMatch && dateMatch;
    });

    displayFundraisers(filteredFundraisers);
}

// Display fundraisers
function displayFundraisers(fundraisersToShow) {
    fundraisersGrid.innerHTML = fundraisersToShow.map(createFundraiserCard).join('');
}

// Clear filters
function clearFilters() {
    categoryFilter.value = '';
    dateFilter.value = '';
    displayFundraisers(fundraisers);
}

// Event listeners
categoryFilter.addEventListener('change', filterFundraisers);
dateFilter.addEventListener('change', filterFundraisers);
clearFiltersBtn.addEventListener('click', clearFilters);

// Initial display
displayFundraisers(fundraisers);

// User icon animation (reused from home.js)
const userIcon = document.querySelector('.user-icon');
userIcon.addEventListener('mouseenter', function() {
    this.style.transform = 'scale(1.1) rotate(5deg)';
    this.style.transition = 'all 0.3s ease';
});

userIcon.addEventListener('mouseleave', function() {
    this.style.transform = 'scale(1) rotate(0deg)';
});

// Add navbar scroll effect
window.addEventListener('scroll', function() {
    const nav = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
}); 