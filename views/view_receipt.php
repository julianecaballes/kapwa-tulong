<?php
require_once '../backend/config/database.php';

if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM donations WHERE transaction_id = ?");
        $stmt->execute([$transaction_id]);
        $donation = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($donation) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .receipt-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .receipt-logo {
            width: 200px;
            margin: 0 auto 20px;
            display: block;
        }

        .receipt-title {
            color: #117864;
            text-align: center;
            margin-bottom: 30px;
        }

        .receipt-details {
            background: rgba(17, 120, 100, 0.1);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(17, 120, 100, 0.2);
            color: #117864;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .print-btn {
            display: block;
            width: 200px;
            margin: 0 auto;
            padding: 12px 24px;
            background: #117864;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(17, 120, 100, 0.2);
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <img src="../assets/images/kapwatulong.png" alt="Kapwa Tulong Logo" class="receipt-logo">
        <h1 class="receipt-title">Donation Receipt</h1>
        
        <div class="receipt-details">
            <div class="detail-row">
                <span>Donation Amount:</span>
                <span>₱ <?php echo number_format($donation['donation_amount'], 2); ?></span>
            </div>
            <div class="detail-row">
                <span>Campaign Name:</span>
                <span><?php echo $donation['campaign_name']; ?></span>
            </div>
            <div class="detail-row">
                <span>Campaign Organizer:</span>
                <span><?php echo $donation['campaign_organizer']; ?></span>
            </div>
            <div class="detail-row">
                <span>Date of Donation:</span>
                <span><?php echo date('F j, Y', strtotime($donation['donation_date'])); ?></span>
            </div>
            <div class="detail-row">
                <span>Transaction ID:</span>
                <span><?php echo $donation['transaction_id']; ?></span>
            </div>
            <div class="detail-row">
                <span>Status:</span>
                <span><?php echo $donation['status']; ?></span>
            </div>
        </div>

        <p style="text-align: center; color: #666; font-size: 12px; margin-bottom: 10px;">
            This receipt is auto-generated and does not require a signature.
        </p>
        <p style="text-align: center; color: #999; font-size: 12px;">
            Copyright © 2025 Kapwa-Tulong Com.
        </p>

        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 20px;">
            <button class="print-btn" onclick="window.print()">Print Receipt</button>
            <a href="receipt_history.php" class="print-btn" style="margin-top: 0;">Back to History</a>
            <a href="../backend/home.html" class="print-btn" style="background: #0d5c4d;">Continue</a>
        </div>
    </div>
</body>
</html>
<?php
        } else {
            echo "Receipt not found.";
        }
    } catch(PDOException $e) {
        echo "Error retrieving receipt.";
    }
} else {
    echo "No receipt ID provided.";
}
?> 