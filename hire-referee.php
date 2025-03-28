<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Hire a Referee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/hire-referee.css">
</head>
<body>
<?php include 'header.php'; 
include 'db.php';

// Fetch referees with their user names from the database
$sql = "SELECT r.ref_id, r.ref_location, r.charges, r.yrs_exp, r.ref_pic, u.user_name 
        FROM referee r
        JOIN user u ON r.referee_email = u.email";
$result = $conn->query($sql);

// Check for query errors
if ($result === false) {
    die("Database error: " . $conn->error);
}
?>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Professional Referees</h1>
            <p>Find and hire experienced referees for your matches and tournaments</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search referees by name...">
            </div>
        </div>

        <!-- Referees Grid -->
        <div class="referees-wrapper">
            <!-- Main Content Grid -->
            <div class="main-content">
                <?php 
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                <div class="referee-card">
                    <div class="referee-image">
                        <img src="<?php echo htmlspecialchars($row['ref_pic']); ?>" alt="Referee">
                    </div>
                    <div class="referee-info">
                        <div class="referee-name"><?php echo htmlspecialchars($row['user_name']); ?></div>
                        <div class="referee-experience"><?php echo htmlspecialchars($row['yrs_exp']); ?> years of experience</div>
                        <div class="referee-badges">
                            <span class="badge">Location: <?php echo htmlspecialchars($row['ref_location']); ?></span>
                        </div>
                        <div class="price">₹<?php echo htmlspecialchars($row['charges']); ?> per match</div>
                        <button class="hire-button" onclick="window.location.href='refereebook.php?ref_id=<?php echo $row['ref_id']; ?>'">HIRE NOW</button>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<div class='no-results'>No referees found</div>";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <!-- Popup Overlay -->
    <div class="popup-overlay">
        <div class="popup-content">
            <!-- Popup content will be dynamically inserted here -->
        </div>
    </div>

    <script>
        // Enhanced search functionality
        const searchInput = document.querySelector('.search-container input');
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const refereeCards = document.querySelectorAll('.referee-card');
            
            refereeCards.forEach(card => {
                const refereeName = card.querySelector('.referee-name').textContent.toLowerCase();
                const badges = Array.from(card.querySelectorAll('.badge')).map(badge => badge.textContent.toLowerCase());
                const experience = card.querySelector('.referee-experience').textContent.toLowerCase();
                
                const matches = refereeName.includes(searchTerm) || 
                              badges.some(badge => badge.includes(searchTerm)) ||
                              experience.includes(searchTerm);
                
                card.style.display = matches ? 'flex' : 'none';
            });
        });

        // Popup functionality
        document.addEventListener('DOMContentLoaded', function() {
            const popupOverlay = document.querySelector('.popup-overlay');
            const popupContent = document.querySelector('.popup-content');
            
            // Add event listeners to all hire buttons
            const hireButtons = document.querySelectorAll('.hire-button');
            hireButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Get the parent referee card
                    const refereeCard = this.closest('.referee-card');
                    
                    // Extract referee details
                    const refereeName = refereeCard.querySelector('.referee-name').textContent;
                    
                    // Find the location from badges
                    const badges = refereeCard.querySelectorAll('.badge');
                    let refereeLocation = '';
                    badges.forEach(badge => {
                        if(badge.textContent.includes('Location:')) {
                            refereeLocation = badge.textContent;
                        }
                    });
                    
                    const refereePrice = refereeCard.querySelector('.price').textContent;
                    
                    // Get contact from data attribute
                    const refereeContact = refereeCard.getAttribute('data-contact');
                    
                    // Populate popup content
                    popupContent.innerHTML = `
                        <div class="popup-header">
                            <h2>Referee Details</h2>
                            <button class="close-popup"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="popup-body">
                            <div class="popup-detail">
                                <strong>Name:</strong> ${refereeName}
                            </div>
                            <div class="popup-detail">
                                <strong>Location:</strong> ${refereeLocation.replace('Location: ', '')}
                            </div>
                            <div class="popup-detail">
                                <strong>Price:</strong> ${refereePrice}
                            </div>
                            <div class="popup-detail">
                                <strong>Contact:</strong> ${refereeContact || 'Contact information not available'}
                            </div>
                        </div>
                        <div class="popup-footer">
                            <a href="tel:${refereeContact}" class="call-button">
                                <i class="fas fa-phone"></i> Call Referee
                            </a>
                        </div>
                    `;
                    
                    // Show popup
                    popupOverlay.style.display = 'flex';
                    
                    // Handle close button
                    const closeButton = popupContent.querySelector('.close-popup');
                    closeButton.addEventListener('click', function() {
                        popupOverlay.style.display = 'none';
                    });
                });
            });
            
            // Close when clicking outside
            popupOverlay.addEventListener('click', function(e) {
                if (e.target === popupOverlay) {
                    popupOverlay.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>