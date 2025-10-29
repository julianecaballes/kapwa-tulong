<?php
// Turn off output buffering completely
ob_end_clean();
ob_start();

// Start session and set error reporting
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Turn off HTML error display
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', 'php_errors.log'); // Set error log file

// Include database connection
require_once 'connect.php';

// Set JSON header
header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verify database connection
        if (!$conn || $conn->connect_error) {
            throw new Exception("Database connection failed");
        }

        // Get form data with validation
        $city_province = isset($_POST['city_province']) ? $conn->real_escape_string($_POST['city_province']) : '';
        $zip_code = isset($_POST['zip_code']) ? $conn->real_escape_string($_POST['zip_code']) : '';
        $categories = isset($_POST['categories']) ? $conn->real_escape_string($_POST['categories']) : '';
        $title = isset($_POST['title']) ? $conn->real_escape_string($_POST['title']) : '';
        $description = isset($_POST['description']) ? $conn->real_escape_string($_POST['description']) : '';
        $target_amount = isset($_POST['target_amount']) ? floatval($_POST['target_amount']) : 0;

        // Validate required fields
        $errors = [];
        if (empty($city_province)) $errors[] = "City Province is required";
        if (empty($zip_code)) $errors[] = "Zip Code is required";
        if (empty($categories)) $errors[] = "Categories are required";
        if (empty($title)) $errors[] = "Title is required";
        if (empty($description)) $errors[] = "Description is required";
        if ($target_amount <= 0) $errors[] = "Valid target amount is required";

        if (!empty($errors)) {
            throw new Exception(implode(", ", $errors));
        }

        // Handle image upload
        $image_path = '';
        if (isset($_FILES['campaign_image']) && $_FILES['campaign_image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($_FILES['campaign_image']['type'], $allowed_types)) {
                throw new Exception("Invalid file type. Only JPG, PNG, and GIF are allowed.");
            }
            
            if ($_FILES['campaign_image']['size'] > $max_size) {
                throw new Exception("File size too large. Maximum size is 5MB.");
            }
            
            $upload_dir = '../uploads/campaigns/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['campaign_image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '_' . time() . '.' . $file_extension;
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['campaign_image']['tmp_name'], $target_path)) {
                $image_path = 'uploads/campaigns/' . $file_name;
            } else {
                throw new Exception("Failed to upload image");
            }
        }

        // Get user_id from session
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

        // Prepare and execute SQL statement
        $sql = "INSERT INTO campaigns (
            user_id, title, description, target_amount, 
            city_province, zip_code, categories, campaign_image, 
            status, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("issdssss",
            $user_id,
            $title,
            $description,
            $target_amount,
            $city_province,
            $zip_code,
            $categories,
            $image_path
        );

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();

        // Clear any output and send success response
        ob_clean();
        echo json_encode([
            'success' => true,
            'message' => 'Campaign submitted successfully!'
        ]);

    } else {
        throw new Exception("Invalid request method");
    }

} catch (Exception $e) {
    // Clear any output and send error response
    ob_clean();
    error_log("Campaign creation error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

// Close the connection
if (isset($conn)) {
    $conn->close();
}

// Ensure no more output after this point
exit();
?> 