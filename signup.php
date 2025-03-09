<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
<?php
    if(isset($_POST["submit"])){
        $fullname=$_POST["fullname"];
        $email=$_POST["email"];
        $number=$_POST["number"];
        $password=$_POST["password"];
        $passwordRepeat=$_POST["repeat_password"];

        $passwordHash=password_hash($password, PASSWORD_DEFAULT);

        $errors = array();
        if (empty($fullname) OR empty($email) OR empty($number) OR empty($password) OR empty($passwordRepeat)){
            array_push($errors, "All fields are required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            array_push($errors, "Email is not valid");
        }
        if (strlen($password)<8){
            array_push($errors, "Password must be atleast 8 characters long");
        }
        if ($password!==$passwordRepeat){
            array_push($errors, "Password does not match");
        }
        if (count($errors)>0){
            foreach($errors as $error){
                echo "<div class='error'>$error</div>";
            }
        }
    }
    ?>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="#" class="logo">GAME DAY</a>
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-links">
            <a href="homepage.html">Home</a>
            <a href="grounds.html">Grounds</a>
            <a href="academy.html">Academy</a>
            <a href="tournament.html">Tournament</a>
            <a href="about.html">About</a>
            <a href="faq.html">FAQ</a>
            <a href="login.html">Login</a>
            <a href="signup.html" class="cta-button">Get started</a>
        </div>
    </nav>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-shield user-icon"></i>
                <div class="login-title">WELCOME TO GAMEDAY</div>
                <div class="login-subtitle">Sign up and create an account</div>
            </div>

            <form id="LoginForm">
                <div class="form-group">
                    <label for="Username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="Username" class="form-input" name="fullname" placeholder="Enter your username" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="Email" class="form-input" name="email" placeholder="Enter your email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-phone"></i>
                        <input type="text" id="phone" class="form-input" name="number" placeholder="Enter your phone number" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="Password" class="form-input" name=password placeholder="Enter your password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Repeat-Password">Repeat Password</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="Repeat-Password" class="form-input" name=repeat_password placeholder="Enter your password again" required>
                    </div>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe">Remember me</label>
                </div>

                <button type="submit" class="login-button">Sign up to GameDay</button>
            </form>

            <div class="forgot-password">
                <a href="login.php">Already have an account? Login here</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 GAME DAY. All Rights Reserved.</p>
    </div>
</body>
</html>