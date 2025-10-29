<?php
session_start();
include '../backend/config/connect.php';

if(isset($_POST['admin_login'])) {
    $username = $_POST['username'];
    $password = MD5($_POST['password']); // Using MD5 temporarily
    
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Kapwa Tulong</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <a href="../views/index.html" class="back-btn">
        <i class='bx bx-arrow-back'></i>
        Back
    </a>
    <div class="container">
        <div class="form-box login">
            <form method="POST" class="login-form">
                <div class="logo-container">
                    <img src="../assets/images/logo.png" alt="Kapwa Tulong Logo" class="logo">
                </div>
                <h1>Admin Login</h1>
                <h6>Log in to access the admin dashboard.</h6>
                <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
                
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                
                <div class="input-box">
                    <input type="password" name="password" id="adminPassword" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                    <i class='bx bx-hide toggle-password' onclick="togglePassword('adminPassword', this)"></i>
                </div>
                
                <button type="submit" name="admin_login" class="btn">Login</button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
</body>
</html>