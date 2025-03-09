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
                <div class="login-title">WELCOME BACK</div>
                <div class="login-subtitle">Login to your account.</div>
            </div>

            <form id="LoginForm">
                
                <div class="form-group">
                    <label for="Username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="Username" class="form-input" placeholder="Enter your username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="Password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="Password" class="form-input" placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe">Remember me</label>
                </div>

                <button type="submit" class="login-button">Login to GameDay</button>
            </form>

            <div class="forgot-password">
                <a href="signup.php">Don't have an Account? Sign Up</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 GAME DAY. All Rights Reserved.</p>
    </div>
</body>
</html>