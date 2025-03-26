<?php
session_start();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store tournament data in session
    $_SESSION['tournament_id'] = $_POST['tr_id'];
    $_SESSION['tournament_name'] = $_POST['tr_name'];
    $_SESSION['tournament_venue'] = $_POST['venue_nm'];
    $_SESSION['tournament_location'] = $_POST['location'];
    $_SESSION['tournament_date'] = $_POST['start_date'];
    $_SESSION['tournament_fee'] = $_POST['entry_fee'];
    $_SESSION['venue_id'] = $_POST['venue_id'];
    
    // Redirect to tournaments page if accessed directly
    header("Location: tournregispage.php?tr_id=" . $_SESSION['tournament_id']);
    exit();
}
?>
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
    <link rel="stylesheet" href="CSS\main.css">
    <link rel="stylesheet" href="CSS\tournament.css">
</head>
<body>
    <?php 
    include 'header.php';
    include 'db.php';  // Include the database connection

    // Get current date for comparison
    $current_date = date('Y-m-d');

    // Query to fetch only tournaments that have not started yet with venue details
    $sql = "SELECT t.tr_id, t.tr_name, t.img_url, t.start_date, t.entry_fee, v.venue_nm, v.location, v.venue_id 
            FROM tournaments t 
            LEFT JOIN venue v ON t.venue_id = v.venue_id
            WHERE t.start_date > '$current_date'";
    $result = $conn->query($sql);
    ?>
    
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
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $tr_id = $row['tr_id'];
                    $tr_name = $row['tr_name'];
                    $venue_nm = $row['venue_nm'];
                    $location = $row['location'];
                    $start_date = $row['start_date'];
                    $entry_fee = $row['entry_fee'];
                    $venue_id = $row['venue_id'];
            ?>
            <div class="tournament-card">
                <img src="<?php echo htmlspecialchars($row['img_url']); ?>" alt="<?php echo htmlspecialchars($tr_name); ?>" class="tournament-image">
                <div class="tournament-info">
                    <div class="tournament-name"><?php echo htmlspecialchars($tr_name); ?></div>
                    <div class="tournament-details">
                        <div class="tournament-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($venue_nm) . ', ' . htmlspecialchars($location); ?>
                        </div>
                        <div class="tournament-detail">
                            <i class="fas fa-calendar-alt"></i>
                            Starts: <?php echo date('d M Y', strtotime($start_date)); ?>
                        </div>
                        <div class="tournament-detail">
                            <i class="fas fa-money-bill-wave"></i>
                            Entry Fee: ₹<?php echo htmlspecialchars($entry_fee); ?>
                        </div>
                    </div>
                    <div class="tournament-actions">
                        <button class="register-button" onclick="registerForTournament('<?php echo $tr_id; ?>', '<?php echo $tr_name; ?>', '<?php echo $venue_nm; ?>', '<?php echo $location; ?>', '<?php echo $start_date; ?>', '<?php echo $entry_fee; ?>', '<?php echo $venue_id; ?>')">Register Now</button>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p class='no-tournaments'>No upcoming tournaments available at the moment. Check back later!</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function registerForTournament(trId, trName, venueName, location, startDate, entryFee, venueId) {
            // Create a form element
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'tournament.php';
            form.style.display = 'none';
            
            // Create input fields for tournament data
            const fields = {
                'tr_id': trId,
                'tr_name': trName,
                'venue_nm': venueName,
                'location': location,
                'start_date': startDate,
                'entry_fee': entryFee,
                'venue_id': venueId
            };
            
            // Add fields to form
            for (const [key, value] of Object.entries(fields)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
            
            // Add form to document and submit
            document.body.appendChild(form);
            form.submit();
        }

        // Rest of the existing JavaScript for hero slider remains the same
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