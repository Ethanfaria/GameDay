<?php
// If session not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION["user_email"]) && !empty($_SESSION["user_email"]);
}

// Function to get user type
function getUserType() {
    if (isset($_SESSION["user_type"]) && !empty($_SESSION["user_type"])) {
        return $_SESSION["user_type"];
    }
    return null; // Return null if user type is not set
}

// Function to check if user is admin
function isAdmin() {
    return getUserType() === "admin";
}

// Function to check if user is normal user
function isNormalUser() {
    return getUserType() === "normal";
}
?>

<!-- Navigation Bar -->
<nav class="navbar">
    <a href="#" class="logo">GAME DAY</a>
    <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>
    <div class="nav-links">
        <a href="homepage.php">Home</a>
        <a href="grounds.php">Grounds</a>
        <a href="academy.php">Academy</a>
        <a href="tournament.php">Tournament</a>
        <a href="hire-referee.php">Referee</a>
        <a href="about.php">About</a>
        <a href="faq.php">FAQ</a>
        
        <?php if (isLoggedIn()): ?>
        <?php if (isAdmin()): ?>
            <a href="admin-dashboard.php"><i class="fa-solid fa-circle-user acc-icon"></i></a>
        <?php else: ?>
            <a href="userdashboard.php"><i class="fa-solid fa-circle-user acc-icon"></i></a>
        <?php endif; ?>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php" class="cta-button">Get started</a>
        <?php endif; ?>
    </div>
</nav>