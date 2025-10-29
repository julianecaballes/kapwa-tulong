// Show/hide modal
function showModal() {
    document.getElementById('submissionModal').classList.add('active');
}

function hideModal() {
    document.getElementById('submissionModal').classList.remove('active');
}

// Show different views
function showView(view) {
    // Hide all views
    ['reviewView', 'approvedView', 'rejectedView'].forEach(id => {
        document.getElementById(id).style.display = 'none';
    });

    // Show selected view
    document.getElementById(view + 'View').style.display = 'block';
}

// Copy link functionality
function copyLink() {
    const link = 'https://example.com/campaign/123';
    navigator.clipboard.writeText(link)
        .then(() => {
            alert('Link copied to clipboard!');
        })
        .catch(() => {
            alert('Failed to copy link');
        });
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        hideModal();
    }
} 