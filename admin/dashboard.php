<?php
session_start();
include '../backend/config/connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get statistics
$stats = [
    'pending' => $conn->query("SELECT COUNT(*) FROM campaigns WHERE status = 'pending'")->fetch_row()[0],
    'approved' => $conn->query("SELECT COUNT(*) FROM campaigns WHERE status = 'approved'")->fetch_row()[0],
    'rejected' => $conn->query("SELECT COUNT(*) FROM campaigns WHERE status = 'rejected'")->fetch_row()[0],
    'completed' => $conn->query("SELECT COUNT(*) FROM campaigns WHERE status = 'completed'")->fetch_row()[0]
];

// Get recent campaigns
$recent_campaigns = $conn->query("
    SELECT 
        c.*,
        u.username as creator_name,
        COALESCE((SELECT SUM(amount) FROM donations WHERE campaign_id = c.campaign_id), 0) as raised_amount
    FROM campaigns c 
    JOIN users u ON c.created_by = u.id 
    ORDER BY c.created_at DESC 
    LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kapwa Tulong</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="admin-dashboard">
    <div class="admin-container">
        <nav class="admin-sidebar">
            <div class="admin-logo">
                <img src="../assets/images/logo.png" alt="Kapwa Tulong Logo" class="logo">
                <h2>Admin Panel</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="dashboard.php" class="active"><i class='bx bxs-dashboard'></i> Dashboard</a></li>
                <li><a href="campaigns.php"><i class='bx bx-campaign'></i> Campaigns</a></li>
                <li><a href="categories.php"><i class='bx bx-category'></i> Categories</a></li>
                <li><a href="users.php"><i class='bx bx-user'></i> Users</a></li>
                <li><a href="settings.php"><i class='bx bx-cog'></i> Settings</a></li>
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><i class='bx bx-log-out'></i> Logout</a></li>
            </ul>
        </nav>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card pending">
                    <h3>Pending Campaigns</h3>
                    <p><?php echo $stats['pending']; ?></p>
                </div>
                <div class="stat-card approved">
                    <h3>Approved Campaigns</h3>
                    <p><?php echo $stats['approved']; ?></p>
                </div>
                <div class="stat-card rejected">
                    <h3>Rejected Campaigns</h3>
                    <p><?php echo $stats['rejected']; ?></p>
                </div>
                <div class="stat-card completed">
                    <h3>Completed Campaigns</h3>
                    <p><?php echo $stats['completed']; ?></p>
                </div>
            </div>

            <section class="recent-campaigns">
                <h2>Recent Campaigns</h2>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Creator</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Target Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($campaign = $recent_campaigns->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($campaign['title']); ?></td>
                                <td><?php echo htmlspecialchars($campaign['creator_name']); ?></td>
                                <td><span class="status-badge <?php echo $campaign['status']; ?>"><?php echo ucfirst($campaign['status']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($campaign['created_at'])); ?></td>
                                <td>₱<?php echo number_format($campaign['target_amount'], 2); ?></td>
                                <td>
                                    <a href="review_campaign.php?id=<?php echo $campaign['campaign_id']; ?>" class="btn-review">Review</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>