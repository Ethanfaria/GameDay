<?php
session_start();
include 'db.php';

// Process payment if success button was clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_status'])) {
    if ($_POST['payment_status'] === 'success') {
        // Get booking details from POST/session
        $booking_id = isset($_POST['booking_id']) ? $_POST['booking_id'] : (isset($_SESSION['booking_id']) ? $_SESSION['booking_id'] : null);
        $email = $_SESSION['user_email'];

        // Validate booking_id exists
        if (!isset($booking_id)) {
            $_SESSION['payment_error'] = "Missing required booking details.";
            header("Location: userdashboard.php");
            exit();
        }

        // Update booking status to confirmed
        $update_sql = "UPDATE book SET status = 'confirmed', payment_status = 'paid' WHERE booking_id = ? AND email = ?";
        $update_stmt = $conn->prepare($update_sql);
        
        if ($update_stmt === false) {
            $_SESSION['payment_error'] = "Error preparing statement: " . $conn->error;
            header("Location: userdashboard.php");
            exit();
        }

        $update_stmt->bind_param("is", $booking_id, $email);

        if ($update_stmt->execute()) {
            $_SESSION['payment_successful'] = true;
            header("Location: payment-referee-success.php?booking_id=" . $booking_id);
            exit();
        } else {
            // Database error
            $_SESSION['payment_error'] = 'Database error: ' . $update_stmt->error;
            header('Location: payment-failed-referee.php');
            exit();
        }

        $update_stmt->close();
    } else {
        // Payment failure selected
        header('Location: payment-failed-referee.php');
        exit();
    }
}

// Get booking_id from POST or GET
$booking_id = isset($_POST['booking_id']) ? $_POST['booking_id'] : (isset($_GET['booking_id']) ? $_GET['booking_id'] : null);

// Store booking_id in session for later use
if ($booking_id) {
    $_SESSION['booking_id'] = $booking_id;
}

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "<script>alert('Please log in to complete your payment.');</script>";
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

$user_email = $_SESSION['user_email'];

// Check if we have payment method from previous page
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'upi';

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
$stmt->bind_param("is", $booking_id, $user_email);
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
    <title>GAME DAY - Referee Payment Status</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\payment-ground.css">
    <link rel="stylesheet" href="CSS\payment.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    <div class="payment-result-container">
        <h2>Complete Payment</h2>
        
        <div class="payment-info">
            <p><strong>Referee: <?php echo htmlspecialchars($referee_name); ?></strong></p>
            <p>Venue: <?php echo htmlspecialchars($venue_name); ?></p>
            <p>Date & Time: <?php echo htmlspecialchars($booking_date) . ' | ' . htmlspecialchars($booking_time); ?></p>
            <p><strong>₹<?php echo htmlspecialchars($total_amount); ?></strong></p>
            <span class="payment-method-label"><?php echo ucfirst(htmlspecialchars($payment_method)); ?></span>
        </div>
        
        <p>For demonstration purposes, please select the payment outcome:</p>
        
        <div class="payment-options">
            <form method="POST" action="payment-referee-success.php">
                <input type="hidden" name="payment_status" value="success">
                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                <button type="submit" class="result-button success-button">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Success
                </button>
            </form>
            
            <button class="result-button failure-button" id="failureButton">
                <i class="fas fa-times-circle" style="margin-right: 8px;"></i> Failure
            </button>
        </div>
    </div>
    
    <script>
        // Handle failure button click
        document.getElementById('failureButton').addEventListener('click', function() {
            window.location.href = 'payment-failed-referee.php';
        });
    </script>
</body>
</html>