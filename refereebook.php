<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Book Referee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/refereebook.css">
</head>
<body>
    <?php 
    include 'header.php'; 
    include 'db.php';

    // Check if referee ID is passed
    if (!isset($_GET['ref_id'])) {
        die("No referee selected");
    }

    $ref_id = intval($_GET['ref_id']);

    // Fetch referee details
    $sql = "SELECT ref_id, ref_name, ref_location, charges, yrs_exp, ref_pic FROM referee WHERE ref_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ref_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Referee not found");
    }

    $referee = $result->fetch_assoc();
    ?>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Book Your Referee</h1>
            <p>Confirm booking details for your match</p>
        </div>
    </div>

    <!-- Booking Bento Grid -->
    <div class="content-wrapper">
        <div class="booking-bento-grid">
            <!-- Referee Preview -->
            <div class="bento-referee-preview">
                <div class="referee-image">
                    <img src="<?php echo htmlspecialchars($referee['ref_pic']); ?>" alt="Referee Profile">
                </div>
                <div class="referee-info">
                    <div class="referee-name"><?php echo htmlspecialchars($referee['ref_name']); ?></div>
                    <div class="referee-experience"><?php echo htmlspecialchars($referee['yrs_exp']); ?> years of experience</div>
                    <div class="referee-badges">
                        <span class="badge">Location: <?php echo htmlspecialchars($referee['ref_location']); ?></span>
                    </div>
                    <div class="price">₹<?php echo htmlspecialchars($referee['charges']); ?> per match</div>
                </div>
            </div>

            <!-- Booking Form -->
            <form class="bento-booking-form" id="refereeBookingForm" method="POST" action="process-booking.php">
                <input type="hidden" name="ref_id" value="<?php echo $referee['ref_id']; ?>">
                
                <div class="form-group">
                    <label for="match_date">Match Date</label>
                    <input type="date" id="match_date" name="match_date" required>
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="time" id="start_time" name="start_time" required>
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="time" id="end_time" name="end_time" required>
                </div>

                <div class="form-group">
                    <label for="match_location">Match Venue</label>
                    <input type="text" id="match_location" name="match_location" placeholder="Enter match venue" required>
                </div>

                <button type="submit" class="book-now-button">Request Referee</button>
            </form>
        </div>
    </div>

    <script>
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('match_date').setAttribute('min', today);

        // Form validation
        document.getElementById('refereeBookingForm').addEventListener('submit', function(e) {
            const matchDate = new Date(document.getElementById('match_date').value);
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;

            // Date validation
            if (matchDate < new Date()) {
                alert('Please select a future date for the match.');
                e.preventDefault();
                return;
            }

            // Time validation
            if (startTime >= endTime) {
                alert('End time must be after start time.');
                e.preventDefault();
                return;
            }

            // Phone number validation (basic)
            const phoneInput = document.getElementById('contact_phone');
            const phoneRegex = /^[6-9]\d{9}$/;
            if (!phoneRegex.test(phoneInput.value)) {
                alert('Please enter a valid 10-digit Indian mobile number.');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>