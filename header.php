<?php
// If session not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"]);
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
            <!-- Show account link if logged in -->
            <a href="userdashboard.php"><i class="fa-solid fa-circle-user acc-icon"></i></a>
        <?php else: ?>
            <!-- Show login/signup if not logged in -->
            <a href="login.php">Login</a>
            <a href="signup.php" class="cta-button">Get started</a>
        <?php endif; ?>
    </div>
</nav>