<?php
session_start();
require_once 'config/connect.php';

// Fetch fundraisers from database
$query = "SELECT f.*, u.name as creator_name 
          FROM fundraisers f 
          LEFT JOIN users u ON f.user_id = u.id 
          WHERE f.status = 'active' 
          ORDER BY f.date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Convert to array for JavaScript
$fundraisers = array();
while($row = mysqli_fetch_assoc($result)) {
    $fundraisers[] = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'category' => $row['category'],
        'image' => $row['image_url'],
        'date' => $row['date'],
        'goal_amount' => $row['goal_amount'],
        'current_amount' => $row['current_amount'],
        'creator_name' => $row['creator_name']
    );
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Fundraisers - Kapwa Tulong</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/all_fundraisers.css">
</head>
<body>
    <!-- Reuse the same navbar from home.html -->
    <nav class="navbar">
        <div class="nav-left">
            <img src="../assets/images/kapwadark.png" alt="Kapwa Tulong Logo" class="logo">
        </div>
        <div class="nav-right">
            <a href="home.html#categories-section" class="nav-link">Categories</a>
            <a href="home.html#about-section" class="nav-link">About</a>
            <a href="home.html#contact-section" class="nav-link">Contact Us</a>
            <div class="btn-group">
                <a href="../views/create_campaign.html" class="create-btn">CREATE?</a>
                <a href="#" class="donate-btn">DONATE</a>
            </div>
            <div class="user-menu">
                <div class="user-icon">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="dropdown-menu">
                    <a href="account.php">Account</a>
                    <a href="notification.php">Notification</a>
                    <a href="receipt.php">Receipt History</a>
                    <a href="manage.php">Manage Account</a>
                    <a href="settings.php">Settings</a>
                    <a href="logout.php">Log out</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="fundraisers-container">
        <a href="home.html" class="back-btn">Back to Homepage</a>
        
        <h1 class="page-title">All Active Fundraisers</h1>
        
        <div class="filters-container">
            <div class="filter-box">
                <label>Filter by Category</label>
                <select id="categoryFilter" class="filter-select">
                    <option value="">All Categories</option>
                    <option value="education">Education</option>
                    <option value="health">Health</option>
                    <option value="disaster">Disaster</option>
                    <option value="animal">Animal</option>
                    <option value="environment">Environment</option>
                    <option value="community">Community</option>
                    <option value="children">Children</option>
                    <option value="mental-health">Mental Health</option>
                    <option value="elderly">Elderly</option>
                    <option value="hunger">Hunger</option>
                    <option value="housing">Housing</option>
                </select>
            </div>
            
            <div class="filter-box">
                <label>Filter by Date</label>
                <input type="date" id="dateFilter" class="filter-date">
            </div>
            
            <button id="clearFilters" class="clear-filters-btn">Clear Filters</button>
        </div>

        <div class="fundraisers-grid">
            <!-- Fundraiser cards will be dynamically populated here -->
        </div>
    </main>

    <!-- Pass PHP data to JavaScript -->
    <script>
        // Convert PHP array to JavaScript array
        const fundraisers = <?php echo json_encode($fundraisers); ?>;
    </script>
    
    <script src="../assets/js/all_fundraisers.js"></script>
</body>
</html> 