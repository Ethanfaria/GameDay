<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Tournaments</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="tournament.css">
</head>
<body>
<?php include 'header.php'; ?>
    
    <!-- Hero Slider Section -->
    <div class="hero">
        <div class="hero-slider" id="heroSlider">
            <div class="hero-slide">
                <h1 class="hero-text">TOURNAMENTS</h1>
            </div>
            <div class="hero-slide">
                <h1 class="hero-text">SPORTS LEAGUES</h1>
            </div>
            <div class="hero-slide">
                <h1 class="hero-text">CHAMPIONSHIP EVENTS</h1>
            </div>
        </div>
        <div class="slider-controls">
            <button class="slider-btn" id="prevBtn">&#10094;</button>
            <button class="slider-btn" id="nextBtn">&#10095;</button>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Tournament Grid -->
        <div class="tournament-grid">
            <!-- Tournament Cards (Same as previous HTML) -->
            <div class="tournament-card">
                <img src="/api/placeholder/400/250" alt="Tournament" class="tournament-image">
                <div class="tournament-info">
                    <div class="tournament-name">Summer Sports League</div>
                    <div class="tournament-details">
                        <div class="tournament-detail">
                            <i class="fas fa-calendar"></i>
                            15 Jun - 30 Jul 2025
                        </div>
                        <div class="tournament-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            Don Bosco Ground, Panjim
                        </div>
                    </div>
                    <div class="tournament-actions">
                        <button class="register-button" onclick="window.location.href='tournregispage.html'">Register Now</button>
                        <button class="details-button">View Details</button>
                    </div>
                </div>
            </div>
            
            <div class="tournament-card">
                <img src="/api/placeholder/400/250" alt="Tournament" class="tournament-image">
                <div class="tournament-info">
                    <div class="tournament-name">Goa Sports Challenge</div>
                    <div class="tournament-details">
                        <div class="tournament-detail">
                            <i class="fas fa-calendar"></i>
                            1 Aug - 20 Aug 2025
                        </div>
                        <div class="tournament-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            Sports Complex, Margao
                        </div>
                    </div>
                    <div class="tournament-actions">
                        <button class="register-button">Register Now</button>
                        <button class="details-button">View Details</button>
                    </div>
                </div>
            </div>
            
            <div class="tournament-card">
                <img src="/api/placeholder/400/250" alt="Tournament" class="tournament-image">
                <div class="tournament-info">
                    <div class="tournament-name">Urban Sports League</div>
                    <div class="tournament-details">
                        <div class="tournament-detail">
                            <i class="fas fa-calendar"></i>
                            5 Sep - 25 Sep 2025
                        </div>
                        <div class="tournament-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            Panaji Stadium, Goa
                        </div>
                    </div>
                    <div class="tournament-actions">
                        <button class="register-button">Register Now</button>
                        <button class="details-button">View Details</button>
                    </div>
                </div>
            </div>
            
            <div class="tournament-card">
                <img src="/api/placeholder/400/250" alt="Tournament" class="tournament-image">
                <div class="tournament-info">
                    <div class="tournament-name">Autumn Sports Championship</div>
                    <div class="tournament-details">
                        <div class="tournament-detail">
                            <i class="fas fa-calendar"></i>
                            15 Oct - 5 Nov 2025
                        </div>
                        <div class="tournament-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            Local Sports Ground
                        </div>
                    </div>
                    <div class="tournament-actions">
                        <button class="register-button">Register Now</button>
                        <button class="details-button">View Details</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const heroSlider = document.getElementById('heroSlider');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            let currentSlide = 0;
            const slides = heroSlider.children;
            const totalSlides = slides.length;

            function showSlide(index) {
                // Ensure index wraps around
                currentSlide = (index + totalSlides) % totalSlides;
                heroSlider.style.transform = `translateX(-${currentSlide * 100}%)`;
            }

            // Next slide
            nextBtn.addEventListener('click', () => {
                showSlide(currentSlide + 1);
            });

            // Previous slide
            prevBtn.addEventListener('click', () => {
                showSlide(currentSlide - 1);
            });

            // Optional: Auto-slide every 5 seconds
            setInterval(() => {
                showSlide(currentSlide + 1);
            }, 5000);
        });
    </script>
   
   <!-- Footer -->
    <footer>
        <div class="footer-col">
            <div class="footer-logo">GAME DAY</div>
            <p class="footer-text">The #1 platform for booking futsal grounds and finding the best playing experiences in your area.</p>
        </div>

        <div class="footer-col footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Find Grounds</a></li>
                <li><a href="#">Join Academy</a></li>
                <li><a href="#">Partner With Us</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">FAQs</a></li>
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