<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

include 'db.php'; // Database connection

// Try to get venue_id from session first, then from GET parameters as fallback
$venue_id = isset($_SESSION['venue_id']) ? $_SESSION['venue_id'] : 
            (isset($_GET['venue_id']) ? $_GET['venue_id'] : '');

$sql = "SELECT * FROM venue WHERE venue_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $venue_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $ground = $result->fetch_assoc();
    
    // Retrieve booking details from session or fall back to available data
    $venue_name = isset($_SESSION['venue_name']) ? $_SESSION['venue_name'] : $ground['venue_nm'];
    $booking_date = isset($_SESSION['booking_date']) ? $_SESSION['booking_date'] : 
                    (isset($_GET['date']) ? $_GET['date'] : 'Date Not Available');
    $booking_time = isset($_SESSION['booking_time']) ? $_SESSION['booking_time'] : 
                    (isset($_GET['time']) ? $_GET['time'] : 'Time Not Available');
    $price = isset($_SESSION['price']) ? $_SESSION['price'] : $ground['price'];
    
    // Store all necessary variables in session
    $_SESSION['venue_id'] = $venue_id;
    $_SESSION['venue_name'] = $venue_name;
    $_SESSION['booking_date'] = $booking_date;
    $_SESSION['booking_time'] = $booking_time;
    $_SESSION['price'] = $price;
} else {
    echo "<p>Ground not found.</p>";
    exit();
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get selected payment method
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'upi';
    
    // Redirect to payment result page
    header("Location: payment-result.php?method=$payment_method");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Payment</title>
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
                <div id="booking-type" class="summary-label">Turf Booking</div>
                <div id="booking-name" class="summary-value"><?php echo htmlspecialchars($venue_name); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Date</div>
                <div id="booking-date" class="summary-value"><?php echo htmlspecialchars($booking_date); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Time</div>
                <div id="booking-time" class="summary-value"><?php echo htmlspecialchars($booking_time); ?></div>
            </div>
            <div class="total-row">
                <div class="total-label">Total Amount</div>
                <div id="total-amount" class="total-value">₹<?php echo htmlspecialchars($price); ?></div>
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
                tn: "Turf Booking Payment"
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
            const amount = <?php echo json_encode($price); ?>;
            
            // Generate QR code for UPI payment
            generateQRCode(amount);
            
            // Default to UPI option
            upiOption.click();
        });
    </script>
</body>
</html>