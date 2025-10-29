<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | Sign Up</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../assets/css/login_signup.css">
</head>

<body>
    <a href="../../views/index.html" class="back-btn">
        <i class='bx bx-arrow-back'></i>
        Back
    </a>
    <div class="container">
        <div class="form-box login">
            <form method="POST" action="login_process.php">
                <div class="img-container"></div>
                <h1>Log in</h1>
                <h6>Log in to continue making an impact.</h6>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="loginPassword" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                    <i class='bx bx-hide toggle-password' onclick="togglePassword('loginPassword', this)"></i>
                </div>
                <div class="forgot-link-remember">
                    <input type="checkbox" name="Remember Pass" value="Remember Pass"> Keep me logged in
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" name="login" class="btn">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-google' ></i></a>
                </div>
            </form>
        </div>


        <div class="form-box sign-up">
            <form method="POST" action="signup.php">
                <h1>Gumawa ng Account</h1>
                <h6>Sign up to make an impact.</h6>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="signupPassword" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                    <i class='bx bx-hide toggle-password' onclick="togglePassword('signupPassword', this)"></i>
                </div>
                <p class="conditions">By clicking the Sign Up button below, you agree to the Tulong Kapwa's Terms of Service and acknowledge the Privacy Notice.</p>
                <button type="signup" name="signup" class="btn">Sign up</button>
                <p>or Sign up with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-google' ></i></a>
                </div>
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Welcome muli!</h1>
                <p>Don't have an account?</p>
                <button class="btn sign-up-btn">Sign up</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Hello, Welcome!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>
    <script src="../../assets/js/signup.js"></script>
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