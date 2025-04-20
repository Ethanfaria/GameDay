<?php
include 'db.php';

$email = $_GET['user_email'] ?? 'Unknown';
$venue_id = $_GET['venue_id'] ?? 'Unknown';
$booking_date = $_GET['date'] ?? 'Unknown';
$booking_time = $_GET['time'] ?? 'Unknown';

$venue_name = "Unknown Venue";
$venue_price = "Unknown";

if ($venue_id !== 'Unknown') {
    $stmt = $conn->prepare("SELECT venue_nm, price FROM venue WHERE venue_id = ?");
    $stmt->bind_param("s", $venue_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $venue_name = $row['venue_nm'];
        $venue_price = $row['price'];
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Booking Confirmed</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/payment.css">
    <link rel="stylesheet" href="CSS/main.css">
</head>
<body>
    <div class="payment-container">
        <i class="fas fa-check-circle success-icon" style="font-size: 64px; color: var(--neon-green); display: block; text-align: center; margin-bottom: 20px;"></i>
        <h1 class="summary-title">Booking Confirmed!</h1>
        
        <div class="order-summary">
            <div class="summary-row">
                <div class="summary-label">Email ID</div>
                <div class="summary-value"><?php echo htmlspecialchars($email); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Venue</div>
                <div class="summary-value"><?php echo htmlspecialchars($venue_name); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Booking Date</div>
                <div class="summary-value"><?php echo htmlspecialchars($booking_date); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Booking Time</div>
                <div class="summary-value"><?php echo htmlspecialchars($booking_time); ?></div>
            </div>
            <div class="total-row">
                <div class="total-label">Price</div>
                <div class="total-value">₹<?php echo htmlspecialchars($venue_price); ?></div>
            </div>
        </div>
        
        <div class="action-buttons">
                <a href="userdashboard.php" class="action-button primary-button">
                    <i class="fas fa-home"></i> Go to Dashboard
                </a>
                <a href="index.php" class="action-button secondary-button">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
        </div>
    </div>
</body>
</html>