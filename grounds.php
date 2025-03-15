<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Day - Find Grounds</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\academy-grounds.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    <?php 
    include 'header.php';
    include 'db.php';  // Include the database connection

    // Query to fetch venues
    $sql = "SELECT * FROM venue";
    $result = $conn->query($sql);
    ?>

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
                    <i class="fas fa-chevron-up filter-icon"></i>
                </h3>
                <div class="location-toggle">
                    <button class="location-btn" data-location="all">All</button>
                    <button class="location-btn" data-location="north-goa">Panjim</button>
                    <button class="location-btn" data-location="south-goa">Mapusa</button>
                    <button class="location-btn" data-location="south-goa">Margao</button>
                    <button class="location-btn" data-location="south-goa">Other</button>
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
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Get average rating from venue_reviews table
                        $venue_id = $row['venue_id'];
                        $rating_sql = "SELECT AVG(ratings) as avg_rating FROM venue_reviews WHERE venue_id = '$venue_id'";
                        $rating_result = $conn->query($rating_sql);
                        $rating_row = $rating_result->fetch_assoc();
                        $rating = number_format($rating_row['avg_rating'] ?? 4.5, 1); // Default to 4.5 if no ratings
                ?>
                <div class="academy">
                    <div class="academy-card">
                        <img src="<?php echo htmlspecialchars($row['turf_img']); ?>" alt="<?php echo htmlspecialchars($row['venue_nm']); ?>" style="width: 100%; height: 150px; object-fit: cover;">
                        <div class="top-icons">
                            <div class="rating">
                                <i class="fas fa-star star-icon"></i>
                                <span><?php echo $rating; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="academy-info">
                        <div class="turf-details"><?php echo htmlspecialchars($row['venue_nm']); ?></div>
                        <div class="turf-details"><?php echo htmlspecialchars($row['location']); ?></div>
                        <button class="enroll-button" onclick="window.location.href='turfbooknow.php?venue_id=<?php echo $row['venue_id']; ?>'">₹<?php echo number_format($row['price']);?>/hr</button>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No venues found</p>";
                }
                $conn->close();
                ?>
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

        // Add this to your existing script at the bottom of the page
document.addEventListener('DOMContentLoaded', function() {
    // Location filter functionality
    const locationButtons = document.querySelectorAll('.location-btn');
    
    // Set "All" as default active
    document.querySelector('.location-btn[data-location="all"]').classList.add('active');
    
    locationButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            locationButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get selected location
            const location = this.getAttribute('data-location');
            
            // Filter academies based on location
            filterAcademies(location);
        });
    });
    
    function filterAcademies(location) {
        const academies = document.querySelectorAll('.academy');
        
        academies.forEach(academy => {
            const academyLocation = academy.querySelector('.turf-details:nth-child(2)').textContent.toLowerCase();
            
            if (location === 'all') {
                academy.style.display = 'block';
            } else if (location === 'north-goa' && academyLocation.includes('north goa')) {
                academy.style.display = 'block';
            } else if (location === 'south-goa' && academyLocation.includes('south goa')) {
                academy.style.display = 'block';
            } else {
                academy.style.display = 'none';
            }
        });
    }
});
    </script>
</body>
</html>