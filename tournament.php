<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

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
        <div class="pages">
                <button class="btn-no btn-prev">Previous</button>
                <button class="btn-no active">1</button>
                <button class="btn-no">2</button>
                <button class="btn-no">3</button>
                <div class="ellipsis">...</div>
                <button class="btn-no">5</button>
                <button class="btn-no btn-next">Next</button>
            </div>
    </div>
    <?php include 'footer.php'; ?>
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
        document.addEventListener('DOMContentLoaded', function() {
        // Pagination functionality
        const itemsPerPage = 9; // Adjust based on design preference
        const tournamentItems = document.querySelectorAll('.tournament-card');
        const totalItems = tournamentItems.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        let currentPage = 1;
        
        // Get pagination elements
        const prevButton = document.querySelector('.pages .btn-prev');
        const nextButton = document.querySelector('.pages .btn-next');
        const pageButtons = Array.from(document.querySelectorAll('.pages .btn-no:not(.btn-prev):not(.btn-next)'));
        const ellipsis = document.querySelector('.pages .ellipsis');
        
        // Initialize pagination
        function initializePagination() {
            if (totalPages <= 5) {
                // If 5 or fewer pages, show all page numbers
                for (let i = 0; i < pageButtons.length; i++) {
                    if (i < totalPages) {
                        pageButtons[i].textContent = i + 1;
                        pageButtons[i].style.display = 'inline-block';
                    } else {
                        pageButtons[i].style.display = 'none';
                    }
                }
                ellipsis.style.display = 'none';
            } else {
                // More than 5 pages, show first 3, ellipsis, and last page
                pageButtons[0].textContent = '1';
                pageButtons[1].textContent = '2';
                pageButtons[2].textContent = '3';
                ellipsis.style.display = 'inline-block';
                pageButtons[3].textContent = totalPages;
            }
            
            // Hide pagination if there's only one page
            if (totalPages <= 1) {
                document.querySelector('.pages').style.display = 'none';
            } else {
                document.querySelector('.pages').style.display = 'flex';
            }
        }
        
        // Update page display
        function updatePageDisplay() {
            // Hide all items
            tournamentItems.forEach(item => {
                item.style.display = 'none';
            });
            
            // Show items for current page
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
            
            for (let i = startIndex; i < endIndex; i++) {
                if (tournamentItems[i]) {
                    tournamentItems[i].style.display = 'block';
                }
            }
            
            // Update pagination buttons
            pageButtons.forEach(button => {
                button.classList.remove('active');
                if (parseInt(button.textContent) === currentPage) {
                    button.classList.add('active');
                }
            });
            
            // Update prev/next buttons
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
            
            // Update pagination display
            if (totalPages > 5) {
                if (currentPage <= 3) {
                    // Near start, show first 3, ellipsis, and last page
                    pageButtons[0].textContent = '1';
                    pageButtons[1].textContent = '2';
                    pageButtons[2].textContent = '3';
                    ellipsis.style.display = 'inline-block';
                    pageButtons[3].textContent = totalPages;
                } else if (currentPage >= totalPages - 2) {
                    // Near end, show first page, ellipsis, and last 3
                    pageButtons[0].textContent = '1';
                    ellipsis.style.display = 'inline-block';
                    pageButtons[1].textContent = totalPages - 2;
                    pageButtons[2].textContent = totalPages - 1;
                    pageButtons[3].textContent = totalPages;
                } else {
                    // Middle, show first page, ellipsis, current and neighbors, ellipsis, last page
                    pageButtons[0].textContent = '1';
                    ellipsis.style.display = 'inline-block';
                    pageButtons[1].textContent = currentPage - 1;
                    pageButtons[2].textContent = currentPage;
                    pageButtons[3].textContent = currentPage + 1;
                }
            }
        }
        
        // Initial pagination setup
        initializePagination();
        updatePageDisplay();
        
        // Add click event listeners to page buttons
        pageButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.textContent && !isNaN(parseInt(this.textContent))) {
                    currentPage = parseInt(this.textContent);
                    updatePageDisplay();
                }
            });
        });
        
        // Add click event listeners to prev/next buttons
        prevButton.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePageDisplay();
            }
        });
        
        nextButton.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                updatePageDisplay();
            }
        });
    });
    </script>

</body>
</html>