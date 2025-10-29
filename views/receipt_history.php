<?php
require_once '../backend/config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation and Receipt History</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .receipt-history-container {
            max-width: 1200px;
            margin: 100px auto;
            padding: 20px;
        }

        .receipt-history-title {
            color: #117864;
            margin-bottom: 30px;
            text-align: center;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .receipt-table th {
            background: #117864;
            color: white;
            padding: 15px;
            text-align: left;
        }

        .receipt-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .receipt-table tr:last-child td {
            border-bottom: none;
        }

        .receipt-table tr:hover td {
            background: #f8f8f8;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-done {
            background: #d4edda;
            color: #155724;
        }

        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background: #117864;
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }

        .action-btn:hover {
            background: #0d5c4d;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #117864;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: #0d5c4d;
        }
    </style>
</head>
<body>
    <!-- Include your navigation bar here -->
    <nav class="navbar">
        <div class="content-wrapper">
            <div class="nav-left">
                <img src="../assets/images/kapwatulong.png" alt="Kapwa Tulong Logo" class="logo light-logo">
                <img src="../assets/images/kapwadark.png" alt="Kapwa Tulong Logo Dark" class="logo dark-logo">
            </div>
            <div class="nav-right">
                <a href="#categories-section" class="nav-link">Categories</a>
                <a href="#about-section" class="nav-link">About</a>
                <a href="#contact-section" class="nav-link">Contact Us</a>
                <div class="btn-group">
                    <a href="create_campaign.html" class="create-btn">CREATE?</a>
                    <a href="donation.html" class="donate-btn">DONATE</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="receipt-history-container">
        <a href="donation-form.html" class="back-btn">
            <i class='bx bx-arrow-back'></i>
            Back to Donation
        </a>
        
        <h1 class="receipt-history-title">Donation and Receipt History</h1>
        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Campaign</th>
                    <th>Date</th>
                    <th>Donation Amount</th>
                    <th>Transaction ID</th>
                    <th>Status</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM donations ORDER BY donation_date DESC");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $formattedAmount = '₱ ' . number_format($row['donation_amount'], 2);
                        $statusClass = strtolower($row['status']) == 'pending' ? 'status-pending' : 'status-done';
                        
                        echo "<tr>
                                <td>{$row['campaign_name']}</td>
                                <td>" . date('Y-m-d', strtotime($row['donation_date'])) . "</td>
                                <td>{$formattedAmount}</td>
                                <td>{$row['transaction_id']}</td>
                                <td><span class='status-badge {$statusClass}'>{$row['status']}</span></td>
                                <td>
                                    <a href='view_receipt.php?id={$row['transaction_id']}' class='action-btn'>
                                        View/Download
                                    </a>
                                </td>
                            </tr>";
                    }
                } catch(PDOException $e) {
                    echo "<tr><td colspan='6'>Error fetching receipt history</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Navbar scroll behavior
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html> 