<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\about.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
<?php include 'header.php'; ?>

    <div class="bento-grid">
        <div class="bento-item about-us">
            <h1>About Us</h1>
            <p>At GameDay, we believe that finding and booking a football or futsal ground should be effortless. Whether you're a weekend warrior, a competitive player, or just looking for a casual match with friends, we’ve got you covered.
                <br><br>Our platform connects players with nearby grounds, making it easy to book slots, join tournaments, hire referees or coaches, and stay updated with upcoming events—all in just a few clicks. Say goodbye to endless calls and scheduling hassles!
                <br><br>With a seamless booking experience, secure payments, and community-driven reviews, GameDay is designed to bring players together and enhance the local football scene.</p>
        </div>
        <div class="bento-item item-testimonial">
            <p class="testimonial-text">"As a tournament organizer, GameDay makes my job so much easier. The booking system, referee hiring, and event listings help me manage everything in one place."</p>
            <p class="testimonial-author">- Arjun Mehta,<br>Tournament Coordinator</p>
        </div>
        <div class="bento-item item-testimonial">
            <p class="testimonial-text">"Game Day has completely transformed how we book our weekly matches. No more phone calls or waiting for confirmations!"</p>
            <p class="testimonial-author">- Alex Chen,<br>Regular Player</p>
        </div>
        <div class="bento-item item-testimonial">
            <p class="testimonial-text">"The community reviews on GameDay helped us find the best grounds, and the secure payment system is a huge plus. It’s a must-have for anyone who plays regularly!"</p>
            <p class="testimonial-author">- Samantha Lobo,<br>Regular Player</p>
        </div>
        <div class="bento-item item-testimonial">
            <p class="testimonial-text">"I run a futsal ground, and since joining GameDay, we’ve seen a huge increase in bookings. The platform connects us with players we never reached before!"</p>
            <p class="testimonial-author">- Kiran Patil,<br>Futsal Ground Owner</p>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="footer-col">
            <div class="footer-logo">GAME DAY</div>
            <p class="footer-text">The #1 platform for booking futsal grounds and finding the best playing experiences in your area.</p>
        </div>

        <div class="footer-col footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="grounds.php">Find Grounds</a></li>
                <li><a href="academy.php">Join Academy</a></li>
                <li><a href="#">Partner With Us</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="faq.php">FAQs</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <div class="contact">
                <h3>Contact Us</h3>
                <div class="contact-info">
                    <p><i class="fas fa-envelope"></i> info@gameday.com</p>
                    <p><i class="fas fa-phone"></i> +1 234 567 8900</p>
                </div>
            </div>
            <div class="social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>