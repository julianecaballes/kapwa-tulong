<?php
session_start();
require_once '../backend/config/connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get user's campaigns
$user_id = $_SESSION['user_id'];
$campaigns_query = $conn->prepare("
    SELECT 
        c.*,
        COALESCE((SELECT SUM(amount) FROM donations WHERE campaign_id = c.campaign_id), 0) as raised_amount
    FROM campaigns c
    WHERE c.created_by = ?
    ORDER BY c.created_at DESC
");

$campaigns_query->bind_param("i", $user_id);
$campaigns_query->execute();
$campaigns = $campaigns_query->get_result();

// Get user's total stats
$stats_query = $conn->prepare("
    SELECT 
        COUNT(*) as total_campaigns,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_campaigns,
        (SELECT COUNT(DISTINCT campaign_id) FROM donations WHERE campaigns.created_by = ?) as campaigns_with_donations,
        (SELECT SUM(amount) FROM donations d JOIN campaigns c ON d.campaign_id = c.campaign_id WHERE c.created_by = ?) as total_donations
");

$stats_query->bind_param("ii", $user_id, $user_id);
$stats_query->execute();
$stats = $stats_query->get_result()->fetch_assoc();
?>

<!-- Keep your existing HTML header and sidebar -->

<!-- Update the stats cards section -->
<div class="cards-grid">
    <div class="card">
        <div class="card-header">
            <h3>Total Donations</h3>
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </div>
        <div class="card-content">
            <div class="stat">₱<?php echo number_format($stats['total_donations'] ?? 0, 2); ?></div>
            <p class="stat-detail">From <?php echo $stats['campaigns_with_donations']; ?> campaigns</p>
        </div>
    </div>
    <!-- Similar updates for other stat cards -->
</div>

<!-- Update the campaigns table -->
<div class="tab-content active" id="campaigns">
    <div class="table-header">
        <h2>Recent Campaigns</h2>
        <a href="create_campaign.html" class="create-button">
            <svg class="button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Create Campaign
        </a>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Goal</th>
                    <th>Raised</th>
                    <th>Status</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($campaign = $campaigns->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($campaign['title']); ?></td>
                    <td>₱<?php echo number_format($campaign['target_amount'], 2); ?></td>
                    <td>₱<?php echo number_format($campaign['raised_amount'], 2); ?></td>
                    <td><span class="status-badge <?php echo $campaign['status']; ?>"><?php echo ucfirst($campaign['status']); ?></span></td>
                    <td><?php echo date('Y-m-d', strtotime($campaign['end_date'])); ?></td>
                    <td><button class="view-button" onclick="viewCampaign(<?php echo $campaign['campaign_id']; ?>)">View Details</button></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div> 