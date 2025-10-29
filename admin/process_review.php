<?php
session_start();
require_once '../backend/config/connect.php';

if (!isset($_SESSION['admin_id']) || !isset($_POST['campaign_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

try {
    $campaign_id = $_POST['campaign_id'];
    $status = $_POST['status'];
    $feedback = $_POST['feedback'] ?? '';

    $stmt = $conn->prepare("
        UPDATE campaigns 
        SET status = ?, 
            admin_feedback = ?,
            reviewed_at = NOW(),
            reviewed_by = ?
        WHERE campaign_id = ?
    ");

    $stmt->bind_param("ssii", $status, $feedback, $_SESSION['admin_id'], $campaign_id);
    
    if ($stmt->execute()) {
        // Get campaign creator's email
        $email_stmt = $conn->prepare("
            SELECT u.email, u.username, c.title 
            FROM campaigns c
            JOIN users u ON c.created_by = u.id
            WHERE c.campaign_id = ?
        ");
        
        $email_stmt->bind_param("i", $campaign_id);
        $email_stmt->execute();
        $result = $email_stmt->get_result()->fetch_assoc();

        // Send email notification
        $to = $result['email'];
        $subject = "Campaign Status Update - " . $result['title'];
        $message = "Dear " . $result['username'] . ",\n\n";
        $message .= "Your campaign '" . $result['title'] . "' has been " . $status . ".\n\n";
        if ($feedback) {
            $message .= "Admin Feedback: " . $feedback . "\n\n";
        }
        $message .= "Best regards,\nKapwa Tulong Team";

        mail($to, $subject, $message);

        echo json_encode(['success' => true, 'message' => 'Campaign status updated successfully']);
    } else {
        throw new Exception('Failed to update campaign status');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 