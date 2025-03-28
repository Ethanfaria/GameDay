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

    session_start();
    include 'header.php'; 
    include 'db.php';

    // Check if referee ID is passed
    if (!isset($_GET['ref_id'])) {
        die("No referee selected");
    }

    $ref_id = $_GET['ref_id'];

    // Fetch referee details
    $sql = "SELECT r.ref_id, r.ref_location, r.charges, r.yrs_exp, r.ref_pic, u.user_name, r.referee_email 
            FROM referee r
            JOIN user u ON r.referee_email = u.email
            WHERE r.ref_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ref_id);  
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Referee not found");
    }

    $referee = $result->fetch_assoc();

    // Fetch all venues for dropdown
    $venue_sql = "SELECT venue_id, venue_nm FROM venue";
    $venue_result = $conn->query($venue_sql);
    $venues = [];
    if ($venue_result->num_rows > 0) {
        while($row = $venue_result->fetch_assoc()) {
            $venues[] = $row;
        }
    }

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $match_date = $_POST['match_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $venue_id = $_POST['match_location'];
        $referee_email = $referee['referee_email'];
        $user_email = $_SESSION['email'] ?? ''; // Assuming user is logged in and email is in session
        
        // Combine start and end time for bk_dur
        $bk_dur = date("g:i A", strtotime($start_time)) . " - " . date("g:i A", strtotime($end_time));
        
        // Insert booking into database
        $insert_sql = "INSERT INTO book (email, venue_id, bk_date, bk_dur, referee_email, status) 
                        VALUES (?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssss", $user_email, $venue_id, $match_date, $bk_dur, $referee_email);
        
        if ($stmt->execute()) {
            echo "<script>alert('Referee request submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error submitting request: " . $conn->error . "');</script>";
        }
    }
    ?>


    <!-- Booking Bento Grid -->
    <div class="content-wrapper">
        <div class="booking-bento-grid">
            <!-- Referee Preview -->
            <div class="bento-referee-preview">
                <div class="referee-image">
                    <img src="<?php echo htmlspecialchars($referee['ref_pic']); ?>" alt="Referee Profile">
                </div>
                <div class="referee-info">
                    <div class="referee-name"><?php echo htmlspecialchars($referee['user_name']); ?></div>
                    <div class="referee-experience"><?php echo htmlspecialchars($referee['yrs_exp']); ?> years of experience</div>
                    <div class="referee-badges">
                        <span class="badge">Location: <?php echo htmlspecialchars($referee['ref_location']); ?></span>
                    </div>
                    <div class="price">₹<?php echo htmlspecialchars($referee['charges']); ?> per match</div>
                </div>
            </div>

            <!-- Booking Form -->
            <form class="bento-booking-form" id="refereeBookingForm" method="POST" action="">
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
                    <select id="match_location" name="match_location" required>
                        <option value="">Select a venue</option>
                        <?php foreach ($venues as $venue): ?>
                            <option value="<?php echo htmlspecialchars($venue['venue_id']); ?>">
                                <?php echo htmlspecialchars($venue['venue_nm']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
        });
    </script>

</body>
</html>