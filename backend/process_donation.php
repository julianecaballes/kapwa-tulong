<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("INSERT INTO donations (
            campaign_name, 
            donation_amount, 
            transaction_id, 
            campaign_organizer, 
            donation_date,
            donor_name,
            is_anonymous,
            payment_method,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['campaign_name'],
            $data['amount'],
            $data['transaction_id'],
            $data['campaign_organizer'],
            date('Y-m-d H:i:s'),
            $data['is_anonymous'] ? NULL : $data['donor_name'],
            $data['is_anonymous'],
            $data['payment_method'],
            'Pending'
        ]);

        echo json_encode(['success' => true, 'message' => 'Donation recorded successfully']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error recording donation']);
    }
}
?> 