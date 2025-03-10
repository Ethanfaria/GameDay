<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\academy-grounds.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
<?php include 'header.php'; ?>

     <!-- Hero Section -->
     <div class="hero">
        <div class="hero-shadow"></div>
        <h1 class="hero-text">Find. Book. Play</h1>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Filters -->
        <div class="filter bento-item">
            <h2 class="filter-title">Filters</h2>
            
            <!-- Location Filter -->
            <div class="filter-type">
                <h3>
                    Location
                    <i class="fas fa-chevron-up filter-icon" ></i>
                </h3>
                <div>
                    <div class="location-option">
                        <input type="checkbox" id="north-goa">
                        <label for="north-goa" class="location-name">North Goa</label>
                    </div>
                    <div class="location-option">
                        <input type="checkbox" id="south-goa">
                        <label for="south-goa" class="location-name">South Goa</label>
                    </div>
                </div>
            </div>

            <!-- Price Range -->
            <div class="filter-type">
                <h3>
                    Price Range
                    <i class="fas fa-chevron-up filter-icon"></i>
                </h3>
                <input type="range" min="0" max="10000" value="10000" class="price-range">
                <div class="price-values">
                    <span>₹0</span>
                    <span>₹10000</span>
                </div>
            </div>
        </div>

        <!-- Academy Listings -->
        <div style="flex: 1;">
            <!-- Search Bar -->
            <div class="search-bar">
                <div class="search-bar-content">
                    <input type="text" placeholder="Enter name of the academy..." class="search-text">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>

            <!-- Academy Grid -->
            <div class="academy-grid">
                <!-- Academy 1 -->
                <div class="academy">
                    <div class="academy-card">
                        <img src="https://plus.unsplash.com/premium_photo-1684888759266-ce3768052c80?q=80&w=2787&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Elite Academy" style="width: 100%; height: 150px; object-fit: cover;">
                        <div class="top-icons">
                            <div class="rating">
                                <i class="fas fa-star star-icon"></i>
                                <span>4.9</span>
                            </div>
                            <i class="far fa-heart heart-icon"></i>
                        </div>
                    </div>
                    <div class="academy-info">
                        <div class="turf-details">Don Bosco Turf</div>
                        <div class="turf-details">Panjim, Goa</div>
                        
                        <button class="enroll-button" onclick="window.location.href='turfbooknow.html'">₹1000/hr</button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pages">
                <button class="btn-no">Previous</button>
                <button class="btn-no active">1</button>
                <button class="btn-no">2</button>
                <button class="btn-no">3</button>
                <div class="ellipsis">...</div>
                <button class="btn-no">5</button>
                <button class="btn-no">Next</button>
            </div>
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
    <script>
        // JavaScript for enhanced user experience
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            
            mobileMenuBtn.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.navbar') && !event.target.closest('.mobile-menu-btn')) {
                    navLinks.classList.remove('active');
                }
            });

            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    if(this.getAttribute('href') !== '#') {
                        e.preventDefault();
                        document.querySelector(this.getAttribute('href')).scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>