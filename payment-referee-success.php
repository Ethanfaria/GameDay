<?php
session_start();
include 'db.php';

// Check if payment was successful
if (!isset($_SESSION['payment_successful']) && !isset($_GET['booking_id'])) {
    header("Location: userdashboard.php");
    exit();
}

// Clear the session flag
unset($_SESSION['payment_successful']);

// Get booking ID from GET parameters
$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

// Get user email from session
$email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

// Fetch booking details with referee information
$sql = "SELECT b.*, v.venue_nm, v.location, 
        r.charges as referee_charges, 
        u.user_name as referee_name,
        u.user_ph as referee_phone
        FROM book b
        JOIN venue v ON b.venue_id = v.venue_id
        JOIN referee r ON b.referee_email = r.referee_email
        JOIN user u ON b.referee_email = u.email
        WHERE b.booking_id = ? AND b.email = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $booking_id, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
    
    // Get values for display
    $venue_name = $booking['venue_nm'];
    $venue_location = $booking['location'];
    $booking_date = date('F d, Y', strtotime($booking['bk_date']));
    $booking_time = $booking['bk_dur'];
    $referee_name = $booking['referee_name'];
    $referee_phone = $booking['referee_phone'];
    $referee_charges = $booking['referee_charges'];
    $total_amount = $referee_charges;
    
    // Generate transaction ID
    $transaction_id = strtoupper(bin2hex(random_bytes(8)));
} else {
    echo "<script>alert('Invalid booking or you don\'t have permission to access this booking');</script>";
    echo "<script>window.location.href = 'userdashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Payment Successful</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\payment-ground.css">
    <link rel="stylesheet" href="CSS\payment.css">
    <link rel="stylesheet" href="CSS\main.css">
    <style>
        .success-container {
            text-align: center;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .success-icon {
            font-size: 5rem;
            color: #4CAF50;
            margin-bottom: 1rem;
        }
        
        .success-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #c4ff3c;
        }
        
        .success-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #ffffff;
        }
        
        .booking-details {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 0.5rem;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            flex: 1;
            font-weight: bold;
            color: #a9a9a9;
        }
        
        .detail-value {
            flex: 2;
            color: #ffffff;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .action-button {
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .primary-button {
            background-color: #c4ff3c;
            color: #0a2e1a;
        }
        
        .primary-button:hover {
            background-color: #d1ff4a;
            transform: translateY(-2px);
        }
        
        .secondary-button {
            background-color: transparent;
            color: #c4ff3c;
            border: 1px solid #c4ff3c;
        }
        
        .secondary-button:hover {
            background-color: rgba(196, 255, 60, 0.1);
            transform: translateY(-2px);
        }
        
        .transaction-id {
            font-family: monospace;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            margin: 0 auto;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="success-container">
            <i class="fas fa-check-circle success-icon"></i>
            <h1 class="success-title">Payment Successful!</h1>
            <p class="success-message">Your referee booking has been confirmed.</p>
            <div class="transaction-id">
                Transaction ID: <?php echo $transaction_id; ?>
            </div>
            
            <div class="booking-details">
                <h2 style="color: #c4ff3c; margin-bottom: 1rem;">Booking Details</h2>
                <div class="detail-row">
                    <div class="detail-label">Referee:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($referee_name); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Venue:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($venue_name); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Location:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($venue_location); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking_date); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Time:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking_time); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Amount Paid:</div>
                    <div class="detail-value">₹<?php echo htmlspecialchars($referee_charges); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value" style="color: #4CAF50;">Confirmed</div>
                </div>
            </div>
            
            <p class="success-message">A confirmation has been sent to your registered email address.</p>
            
            <div class="action-buttons">
                <a href="userdashboard.php" class="action-button primary-button">
                    <i class="fas fa-home"></i> Go to Dashboard
                </a>
                <a href="index.php" class="action-button secondary-button">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>