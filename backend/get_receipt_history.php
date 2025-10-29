<?php
require_once 'config/database.php';

try {
    $stmt = $pdo->query("SELECT * FROM donations ORDER BY donation_date DESC");
    $receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($receipts);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching receipt history']);
}
?> 