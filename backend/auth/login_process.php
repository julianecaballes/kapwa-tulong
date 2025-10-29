<?php
session_start();
include '../config/connect.php';

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result === FALSE) {
        die("Error in login: " . $conn->error);
    }

    if($result->num_rows == 1) {
        // Login successful
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        header("Location: ../home.html");
        exit();
    } else {
        // Login failed
        echo "<script>alert('Invalid username or password!'); window.location.href = 'login_signup.php';</script>";
    }
} else {
    echo "<script>
        alert('Login form not submitted properly');
        window.location.href='login_signup.php';
    </script>";
}
?> 

