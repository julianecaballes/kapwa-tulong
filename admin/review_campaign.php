<?php
session_start();
require_once '../backend/config/connect.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$campaign_id = $_GET['id'];

// Get campaign details
$stmt = $conn->prepare("
    SELECT 
        c.*,
        u.username as creator_name,
        u.email as creator_email,
        COALESCE(
            (SELECT SUM(amount) FROM donations WHERE campaign_id = c.campaign_id), 
            0
        ) as raised_amount
    FROM campaigns c
    JOIN users u ON c.created_by = u.id
    WHERE c.campaign_id = ?
");

$stmt->bind_param("i", $campaign_id);
$stmt->execute();
$campaign = $stmt->get_result()->fetch_assoc();

// Handle status updates
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $new_status = $_POST['action'];
    $feedback = isset($_POST['feedback']) ? $_POST['feedback'] : '';
    
    $update_stmt = $conn->prepare("
        UPDATE campaigns 
        SET status = ?, admin_feedback = ? 
        WHERE campaign_id = ?
    ");
    
    $update_stmt->bind_param("ssi", $new_status, $feedback, $campaign_id);
    
    if($update_stmt->execute()) {
        // Send email notification to campaign creator
        $to = $campaign['creator_email'];
        $subject = "Campaign Status Update - " . $campaign['title'];
        $message = "Your campaign status has been updated to: " . ucfirst($new_status);
        if($feedback) {
            $message .= "\n\nAdmin Feedback: " . $feedback;
        }
        mail($to, $subject, $message);
        
        header("Location: dashboard.php?success=1");
        exit();
    }
}
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
                <h2>Admin Panel</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="dashboard.php" class="active"><i class='bx bxs-dashboard'></i> Dashboard</a></li>
                <li><a href="campaigns.php"><i class='bx bx-campaign'></i> Campaigns</a></li>
                <li><a href="categories.php"><i class='bx bx-category'></i> Categories</a></li>
                <li><a href="users.php"><i class='bx bx-user'></i> Users</a></li>
                <li><a href="settings.php"><i class='bx bx-cog'></i> Settings</a></li>
                <li><a href="logout.php"><i class='bx bx-log-out'></i> Logout</a></li>
            </ul>
        </nav>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                </div>
            </header>

            <div class="campaign-review">
                <h2>Review Campaign: <?php echo htmlspecialchars($campaign['title']); ?></h2>
                
                <div class="campaign-details">
                    <div class="detail-row">
                        <label>Creator:</label>
                        <span><?php echo htmlspecialchars($campaign['creator_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <label>Target Amount:</label>
                        <span>₱<?php echo number_format($campaign['target_amount'], 2); ?></span>
                    </div>
                    <div class="detail-row">
                        <label>Description:</label>
                        <div class="description-content">
                            <?php echo $campaign['description']; ?>
                        </div>
                    </div>
                    <!-- Add more campaign details -->
                </div>

                <form method="POST" class="review-form">
                    <div class="form-group">
                        <label>Feedback (optional):</label>
                        <textarea name="feedback" rows="4"></textarea>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="submit" name="action" value="approved" class="btn-approve">Approve Campaign</button>
                        <button type="submit" name="action" value="rejected" class="btn-reject">Reject Campaign</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>