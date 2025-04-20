<?php
session_start();
include 'db.php';

// Check if booking_id is provided
if (!isset($_GET['booking_id'])) {
    echo "<script>alert('Booking ID is missing.');</script>";
    echo "<script>window.location.href = 'userdashboard.php';</script>";
    exit();
}

$booking_id = $_GET['booking_id'];

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get selected payment method
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'upi';
    
    // Update booking status to confirmed
    $update_sql = "UPDATE book SET status = 'confirmed' WHERE booking_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    
    // Check if prepare statement was successful
    if ($update_stmt === false) {
        echo "<script>alert('Database error: " . $conn->error . "');</script>";
        exit();
    }
    
    $update_stmt->bind_param("i", $booking_id);
    $update_result = $update_stmt->execute();
    
    if ($update_result) {
        // Add debug message to confirm update
        echo "<script>console.log('Database updated successfully for booking ID: $booking_id');</script>";
        
        // Set success message in session
        $_SESSION['payment_success'] = "Payment successful! Your referee booking is confirmed.";
        
        // Redirect to success page with booking_id
        header("Location: payment-referee-success.php?booking_id=$booking_id");
        exit();
    } else {
        echo "<script>alert('Payment processing failed: " . $update_stmt->error . "');</script>";
        echo "<script>console.error('SQL Error: " . $update_stmt->error . "');</script>";
    }
}

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
    $total_amount = $referee_charges; // Updated to use referee charges directly as total
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
    <title>GAME DAY - Referee Payment</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <link rel="stylesheet" href="CSS\payment-ground.css">
    <link rel="stylesheet" href="CSS\payment.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    <div class="payment-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <h1 class="summary-title">Order Summary</h1>
        <div class="order-summary">
                <div class="summary-row">
                    <div class="summary-label">Referee</div>
                    <div id="referee-name" class="summary-value"><?php echo htmlspecialchars($referee_name); ?></div>
                </div>
                <div class="summary-row">
                    <div id="booking-type" class="summary-label">Referee Booking</div>
                    <div id="booking-name" class="summary-value"><?php echo htmlspecialchars($venue_name); ?></div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Location</div>
                    <div id="booking-location" class="summary-value"><?php echo htmlspecialchars($venue_location); ?></div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Date & Time</div>
                    <div id="booking-datetime" class="summary-value"><?php echo htmlspecialchars($booking_date) . ' | ' . htmlspecialchars($booking_time); ?></div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Referee Fee</div>
                    <div id="referee-fee" class="summary-value">₹<?php echo htmlspecialchars($referee_charges); ?></div>
                </div>
                <div class="total-row" >
                    <div class="total-label">Total Amount</div>
                    <div id="total-amount" class="total-value">₹<?php echo htmlspecialchars($total_amount); ?></div>
                </div>
        </div>

        <h3 class="payment-section-title">Select Payment Method</h3>
        
        <div class="payment-option" id="upiOption" data-method="upi">
            <div class="payment-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <div class="payment-label">UPI / QR Code</div>
        </div>
        
        <div class="payment-option" id="cardOption" data-method="card">
            <div class="payment-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="payment-label">Credit / Debit Card</div>
        </div>
        
        <div class="payment-option" id="bankingOption" data-method="netbanking">
            <div class="payment-icon">
                <i class="fas fa-university"></i>
            </div>
            <div class="payment-label">Net Banking</div>
        </div>
        
        <!-- UPI Payment Details -->
        <div class="payment-details" id="upiDetails">
            <div class="qr-section">
                <div class="qr-code" id="qrcode">
                    <!-- QR code will be generated here -->
                </div>
                <div class="payment-instructions">
                    <h3>Scan to Pay</h3>
                    <p>Use any UPI app to scan this QR code and make your payment</p>
                    <div class="payment-upi-id">gameday@ybl</div>
                    <p>Or use UPI ID to pay directly through your UPI app</p>
                </div>
            </div>
        </div>

        <!-- Card Payment Form -->
        <div class="payment-details" id="cardDetails">
            <div class="qr-section">
                <div class="payment-instructions">
                    <h3>Card Payment</h3>
                    <p>You will be redirected to secure payment gateway</p>
                </div>
            </div>
        </div>
        
        <!-- Net Banking Form -->
        <div class="payment-details" id="bankingDetails">
            <div class="qr-section">
                <div class="payment-instructions">
                    <h3>Net Banking</h3>
                    <p>You will be redirected to your bank's secure login page</p>
                </div>
            </div>
        </div>

        <form method="POST" action="">
            <input type="hidden" name="payment_method" id="paymentMethod" value="upi">
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
            <button type="submit" class="proceed-button" id="proceedButton">Proceed to Pay</button>
        </form>
    </div>

    <script>
        // Generate QR Code
        function generateQRCode(amount) {
            const qrData = {
                pa: "gameday@ybl",
                pn: "GAME DAY",
                am: amount,
                cu: "INR",
                tn: "Referee Booking Payment"
            };
            
            const upiUrl = `upi://pay?${new URLSearchParams(qrData).toString()}`;
            
            // Clear previous QR code if any
            document.getElementById('qrcode').innerHTML = '';
            
            // Generate new QR code
            new QRCode(document.getElementById('qrcode'), {
                text: upiUrl,
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }

        // Get elements
        const upiOption = document.getElementById('upiOption');
        const cardOption = document.getElementById('cardOption');
        const bankingOption = document.getElementById('bankingOption');
        
        const upiDetails = document.getElementById('upiDetails');
        const cardDetails = document.getElementById('cardDetails');
        const bankingDetails = document.getElementById('bankingDetails');
        const paymentMethod = document.getElementById('paymentMethod');

        // Define the hideAllDetails function
        function hideAllDetails() {
            // Hide all payment details
            upiDetails.style.display = 'none';
            cardDetails.style.display = 'none';
            bankingDetails.style.display = 'none';
            
            // Remove selected class from all options
            upiOption.classList.remove('selected');
            cardOption.classList.remove('selected');
            bankingOption.classList.remove('selected');
        }

        // Show UPI details when UPI option is clicked
        upiOption.addEventListener('click', function() {
            hideAllDetails();
            upiDetails.style.display = 'block';
            upiOption.classList.add('selected');
            paymentMethod.value = 'upi';
        });

        // Show card details when card option is clicked
        cardOption.addEventListener('click', function() {
            hideAllDetails();
            cardDetails.style.display = 'block';
            cardOption.classList.add('selected');
            paymentMethod.value = 'card';
        });
        
        // Show banking details when banking option is clicked
        bankingOption.addEventListener('click', function() {
            hideAllDetails();
            bankingDetails.style.display = 'block';
            bankingOption.classList.add('selected');
            paymentMethod.value = 'netbanking';
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Amount calculation
            const amount = <?php echo json_encode($total_amount); ?>;
            
            // Generate QR code for UPI payment
            generateQRCode(amount);
            
            // Default to UPI option
            upiOption.click();
        });
    </script>
</body>
</html>