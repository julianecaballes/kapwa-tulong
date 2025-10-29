<?php
require_once 'config/database.php';

header('Content-Type: application/json');

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Prepare SQL statement
    $sql = "INSERT INTO donations (
        campaign_name,
        donation_amount,
        transaction_id,
        campaign_organizer,
        donation_date,
        payment_method,
        is_anonymous,
        status
    ) VALUES (?, ?, ?, ?, NOW(), ?, ?, 'Pending')";
    
    $stmt = $pdo->prepare($sql);
    
    // Execute with values
    $success = $stmt->execute([
        $data['campaign_name'],
        $data['donation_amount'],
        $data['transaction_id'],
        $data['campaign_organizer'],
        $data['payment_method'],
        $data['is_anonymous']
    ]);
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Donation saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving donation']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 