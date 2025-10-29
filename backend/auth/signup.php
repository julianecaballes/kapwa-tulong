<?php
include '../config/connect.php';

if(isset($_POST['signup'])){ 
    $username = $_POST['username']; 
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $checkUsername = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($checkUsername);

    if($result === FALSE) {
        die("Error in checking email: " . $conn->error);
    }

    if($result->num_rows > 0){
        echo "Username Already Exists!";
    } else {
        $insertQuery = "INSERT INTO users(username, email, password) VALUES ('$username', '$email', '$password')";
        if($conn->query($insertQuery) === TRUE){
            echo "Account created successfully!";
            header("Location: login_signup.php"); 
            exit();
        } else {
            echo "Insert Error: " . $conn->error;
        }
    }
}
?>
