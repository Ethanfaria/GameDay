<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Book Your Futsal Experience</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\homepage.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>

<?php include 'header.php'; ?>

    <!-- Bento Grid Layout -->
    <div class="bento-grid">
        <!-- Header Item -->
        <div class="bento-item item-header">
            <h1>From Click To Kick!</h1>
            <p>Find and book the best futsal grounds near you with just a few clicks</p>
        </div>

        <!-- Booking Item -->
        <div class="bento-item item-booking">
            <h2>Book Your Futsal Experience,<br> Anytime, Anywhere!</h2>
            <a href="#" class="btn">Book Now</a>
        </div>

        <!-- Stats Item -->
        <div class="bento-item item-stats">
            <div class="stats-container">
                <div class="stat-item">
                    <div class="stat-number">200+</div>
                    <div class="stat-label">Grounds</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50k+</div>
                    <div class="stat-label">Players</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100k+</div>
                    <div class="stat-label">Bookings</div>
                </div>
            </div>
        </div>

        <!-- Features Item -->
        <div class="bento-item item-features">
            <h3 class="feature-title">Why Choose Us</h3>
            <ul class="feature-list">
                <li>Instant booking confirmation</li>
                <li>Secure online payments</li>
                <li>Top-rated facilities</li>
                <li>24/7 customer support</li>
            </ul>
        </div>

        <!-- Testimonial Item -->
        <div class="bento-item item-testimonial">
            <p class="testimonial-text">"Game Day has completely transformed how we book our weekly matches. No more phone calls or waiting for confirmations!"</p>
            <p class="testimonial-author">- Alex Chen, Regular Player</p>
        </div>

        <!-- Partner Item -->
        <div class="bento-item item-partner">
            <div class="partner-text">
                <h2>Whistle ready?</h2>
                <p>Referees can now sign up and take charge of the game! Join our platform to get booked for matches, connect with teams, and officiate like a pro</p>
            </div>
            <a href="refereeregis.php" class="btn btn-large">Register Now</a>
        </div>

        <!-- Facilities Item -->
        <div class="bento-item item-facilities">
            <h3 class="feature-title">Top-Notch Facilities</h3>
            <div class="facilities-grid">
                <div class="facility-item">
                    <i class="fas fa-futbol"></i>
                    <span>Premium Turfs</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-shower"></i>
                    <span>Changing Rooms</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-parking"></i>
                    <span>Parking Space</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-lightbulb"></i>
                    <span>Floodlights</span>
                </div>
            </div>
        </div>

        <!-- Partner Item -->
        <div class="bento-item item-partner">
            <div class="partner-text">
                <h2>Partner With Us:<br>List Your Ground or Academy Today!</h2>
                <p>Reach more players, fill your slots, and grow your business effortlessly by joining our platform</p>
            </div>
            <a href="ground-academy-register.php" class="btn btn-large">Register Now</a>
        </div>

        <!-- CTA Item -->
        <div class="bento-item item-cta">
            <h2>Ready to Play?</h2>
            <a href="grounds.php" class="btn btn-large">Get Started Now</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

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
                if (!event.target.closest('.navbar')) {
                    navLinks.classList.remove('active');
                }
            });

            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    if(this.getAttribute('href') !== '#') {
                        document.querySelector(this.getAttribute('href')).scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Animation for stats
            const stats = document.querySelectorAll('.stat-number');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCount(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            stats.forEach(stat => {
                observer.observe(stat);
            });
            
            function animateCount(el) {
                const target = parseInt(el.textContent);
                const suffix = el.textContent.replace(/[0-9]/g, '');
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;
                
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        el.textContent = target + suffix;
                        clearInterval(timer);
                    } else {
                        el.textContent = Math.floor(current) + suffix;
                    }
                }, 16);
            }
            
            // Adding hover effects to bento items
            const bentoItems = document.querySelectorAll('.bento-item');
            bentoItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
        let isLoggedIn = localStorage.getItem("loggedIn") === "true";
        
        let loginBtn = document.querySelector("a[href='login.html']");
        let signupBtn = document.querySelector("a[href='signup.html']");
        let navLinks = document.querySelector(".nav-links");

        // Remove existing "My Profile" button (if any)
        let existingProfileBtn = document.getElementById("profile-btn");
        if (existingProfileBtn) {
            existingProfileBtn.remove();
        }

        if (isLoggedIn) {
            loginBtn.style.display = "none";  // Hide Login Button
            signupBtn.style.display = "none"; // Hide Signup Button

            let profileBtn = document.createElement("button");
            profileBtn.textContent = "My Profile";
            profileBtn.classList.add("cta-button");
            profileBtn.id = "profile-btn";
            profileBtn.onclick = function() {
                document.getElementById("dashboard-popup").style.display = "block"; // Show popup
            };
            navLinks.appendChild(profileBtn);
        } else {
            loginBtn.style.display = "inline-block";  
            signupBtn.style.display = "inline-block";  
        }
    });

    // Function to Reset Session when "Home" is clicked
    function resetSession() {
        localStorage.removeItem("loggedIn");
        location.reload(); // Refresh page to reset login state
    }
	
	
	
	 function closeDashboard() {
        document.getElementById("dashboard-popup").style.display = "none";
    }

    function openSection(section) {
        document.getElementById('edit-profile-section').style.display = 'none';
        document.getElementById('bookings-section').style.display = 'none';
        document.getElementById('support-section').style.display = 'none';
        document.getElementById(section + '-section').style.display = 'block';
    }

    function logout() {
        localStorage.removeItem("loggedIn");
        location.reload(); // Refresh page to reset login state
} 
    function closeDashboard() {
        document.getElementById("dashboard-popup").style.display = "none";
    }

    function logout() {
        localStorage.removeItem("loggedIn");
        location.reload();
    }
    </script>
</body>
</html>