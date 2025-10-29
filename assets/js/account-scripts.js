// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabTriggers = document.querySelectorAll('.tab-trigger');
    const tabContents = document.querySelectorAll('.tab-content');

    tabTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            // Remove active class from all triggers and contents
            tabTriggers.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked trigger and corresponding content
            trigger.classList.add('active');
            const tabId = trigger.dataset.tab;
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Load campaigns list
    loadCampaigns();
    // Load donations list
    loadDonations();
});

// Example function to load campaigns
function loadCampaigns() {
    const campaignsList = document.querySelector('.campaigns-list');
    // Add your campaign loading logic here
    campaignsList.innerHTML = `
        <div class="list-item">
            <h3>Campaign 1</h3>
            <p>Status: Active</p>
        </div>
        <!-- Add more campaign items -->
    `;
}

// Example function to load donations
function loadDonations() {
    const donationsList = document.querySelector('.donations-list');
    // Add your donations loading logic here
    donationsList.innerHTML = `
        <div class="list-item">
            <h3>Donation to Campaign 1</h3>
            <p>Amount: $50</p>
        </div>
        <!-- Add more donation items -->
    `;
}