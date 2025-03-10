<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Summer Sports League Registration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="tournregis.css">

</head>
<body>
<?php include 'header.php'; ?>

    <div class="page-container">
        <!-- Registration Header -->
        <div class="registration-header">
            <h1 class="registration-title">Summer Sports League</h1>
            <p class="registration-subtitle">Register Your Team for the Ultimate Competition</p>
        </div>

        <!-- Registration Container with Split Layout -->
        <div class="registration-container">
            <!-- Tournament Information Section -->
            <div class="tournament-info">
                <div class="tournament-decoration"></div>
                <div class="tournament-info-content">
                    <div class="tournament-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h2 class="tournament-name">Elite Summer Championship 2025</h2>
                    
                    <div class="tournament-detail">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Location</h3>
                            <p>Riverside Sports Complex</p>
                            <p>123 Stadium Avenue, Central District</p>
                        </div>
                    </div>
                    
                    <div class="tournament-detail">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Tournament Dates</h3>
                            <p>June 15 - August 10, 2025</p>
                            <p>Matches every weekend</p>
                        </div>
                    </div>
                    
                    <div class="tournament-detail">
                        <div class="detail-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Match Times</h3>
                            <p>Saturdays: 9:00 AM - 6:00 PM</p>
                            <p>Sundays: 10:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                    
                    <div class="tournament-detail">
                        <div class="detail-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Entry Fee</h3>
                            <p>$350 per team</p>
                            <p>Early bird registration: $300 (before May 15)</p>
                        </div>
                    </div>
                    
                    <div class="tournament-highlight">
                        <h3 class="highlight-title"><i class="fas fa-star"></i> Tournament Highlight</h3>
                        <p class="hightlight-text">
                            Join our premier summer competition featuring 32 teams competing for a $5,000 grand prize! Each team is guaranteed a minimum of 8 matches throughout the tournament. Professional referees, live-streaming of final matches, and post-tournament celebration party included.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Registration Form Section -->
            <div class="card-container">
                <form class="registration-form" id="registrationForm">
                    <div class="form-decoration"></div>
                    <div class="form-decoration-2"></div>
                    
                    <div class="form-header">
                        <div class="form-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h2 class="form-title">Team Registration</h2>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-users"></i> Team Name</label>
                        <div class="input-group">
                            <input type="text" class="form-input" placeholder="Enter your team name" required>
                            <i class="fas fa-shield-alt trailing-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user-tie"></i> Captain Name</label>
                        <div class="input-group">
                            <input type="text" class="form-input" placeholder="Full name of team captain" required>
                            <i class="fas fa-id-badge trailing-icon"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Captain Phone Number
                            <div class="tooltip">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip-text">We'll use this as the primary contact for all tournament updates</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <input type="tel" class="form-input" placeholder="Captain's contact number" required>
                            <i class="fas fa-mobile-alt trailing-icon"></i>
                        </div>
                    </div>
            
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-phone-alt"></i> Alternate Phone Number</label>
                        <div class="input-group">
                            <input type="tel" class="form-input" placeholder="Alternative contact number">
                            <i class="fas fa-address-book trailing-icon"></i>
                        </div>
                    </div>
            
                    <div class="form-group">
                    <label class="form-label"><i class="fas fa-envelope"></i> Captain Email</label>
                        <div class="input-group">
                            <input type="email" class="form-input" placeholder="Captain's email address" required>
                            <i class="fas fa-at trailing-icon"></i>
                        </div>
                    </div>
            
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-friends"></i> Number of Participants
                            <div class="tooltip">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip-text">Minimum 5, maximum 15 players allowed per team</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-input" min="5" max="15" placeholder="Number of team members" required onchange="updateProgressBar()">
                            <i class="fas fa-users trailing-icon"></i>
                        </div>
                    </div>
            
                    <button type="submit" class="submit-button">Complete Registration</button>        
                </form>
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
    </body>
</html>