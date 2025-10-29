<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kapwatulong_db";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die(json_encode(['success' => false, 'error' => 'Database connection failed']));
}
?> 