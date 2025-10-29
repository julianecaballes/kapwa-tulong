<?php
include '../backend/config/connect.php';

$username = 'admin';
$password = 'admin123';
$email = 'admin@kapwatulong.com';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Clear existing admin accounts
$conn->query("TRUNCATE TABLE admins");

// Insert new admin account
$stmt = $conn->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashed_password, $email);

if($stmt->execute()) {
    echo "Admin account created successfully!<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
} else {
    echo "Error creating admin account: " . $conn->error;
}

$stmt->close();
$conn->close();
?>