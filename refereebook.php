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
    <style>select#booking_id {
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 16px 18px;
    width: 100%;
    appearance: none; /* Removes default browser styling */
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg fill='white' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
    background-repeat: no-repeat;
    background-position: right 12px center;
}

select#booking_id option {
    background-color: var(--dark-green);
    color: white;
}

select#booking_id:focus {
    outline: none;
    border-color: var(--neon-green);
    box-shadow: 0 0 15px rgba(185, 255, 0, 0.3);
}</style>
</head>
<body>
    <?php 
    session_start();
    include 'header.php'; 
    include 'db.php';

    if (!isset($_SESSION['user_email'])) {
        header("Location: login.php");
        exit();
    }

    // Check if referee ID is passed
    if (!isset($_GET['ref_id'])) {
        die("No referee selected");
    }

    $ref_id = $_GET['ref_id'];
    $user_email = $_SESSION['user_email'];

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

    // Fetch user's upcoming turf bookings
    $bookings_sql = "SELECT b.booking_id, b.bk_date, b.bk_dur, v.venue_nm, v.venue_id 
                    FROM book b 
                    JOIN venue v ON b.venue_id = v.venue_id 
                    WHERE b.email = ? 
                    AND b.referee_email IS NULL 
                    AND b.bk_date >= CURDATE() 
                    ORDER BY b.bk_date ASC, b.bk_dur ASC";
    $stmt = $conn->prepare($bookings_sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $bookings_result = $stmt->get_result();
    $user_bookings = [];
    
    if ($bookings_result->num_rows > 0) {
        while($row = $bookings_result->fetch_assoc()) {
            $user_bookings[] = $row;
        }
    }

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $booking_id = $_POST['booking_id'];
        $referee_email = $referee['referee_email'];
        
        // Update booking with referee information
        $update_sql = "UPDATE book SET referee_email = ? WHERE booking_id = ? AND email = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sss", $referee_email, $booking_id, $user_email);
        
        if ($stmt->execute()) {
            echo "<script>alert('Referee request submitted successfully!'); window.location.href='userdashboard.php';</script>";
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

            <?php if (count($user_bookings) > 0): ?>
                <!-- Booking Form -->
                <form class="bento-booking-form" id="refereeBookingForm" method="POST" action="">
                    <h3>Select a Booked Turf</h3>
                    <p>You can only book a referee for matches you've already booked.</p>
                    
                    <div class="form-group">
                        <label for="booking_id">Select your booked match:</label>
                        <select id="booking_id" name="booking_id" required>
                            <option value="">Select a booked match</option>
                            <?php foreach ($user_bookings as $booking): ?>
                                <option value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                                    <?php echo htmlspecialchars($booking['venue_nm']); ?> - 
                                    <?php echo htmlspecialchars(date('d M Y', strtotime($booking['bk_date']))); ?>, 
                                    <?php echo htmlspecialchars($booking['bk_dur']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="booking-details" id="bookingDetails">
                        <p>Select a booking to see details</p>
                    </div>

                    <button type="submit" class="book-now-button">Request Referee</button>
                </form>
            <?php else: ?>
                <div class="no-bookings-message">
                    <h3>No Turf Bookings Found</h3>
                    <p>You need to book a turf before you can request a referee.</p>
                    <a href="grounds.php" class="btn book-turf-btn">Book a Turf First</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingSelect = document.getElementById('booking_id');
            const bookingDetails = document.getElementById('bookingDetails');
            
            if (bookingSelect) {
                bookingSelect.addEventListener('change', function() {
                    if (this.value) {
                        const selectedOption = this.options[this.selectedIndex];
                        const bookingText = selectedOption.text;
                        const refereeCharge = <?php echo json_encode(htmlspecialchars($referee['charges'])); ?>;
                        
                        bookingDetails.innerHTML = `
                            <p><strong>Selected Match:</strong> ${bookingText}</p>
                            <p><strong>Referee Charge:</strong> ₹${refereeCharge}</p>
                            <p><strong>Referee:</strong> ${<?php echo json_encode(htmlspecialchars($referee['user_name'])); ?>}</p>
                        `;
                    } else {
                        bookingDetails.innerHTML = '<p>Select a booking to see details</p>';
                    }
                });
            }
        });
    </script>
</body>
</html>