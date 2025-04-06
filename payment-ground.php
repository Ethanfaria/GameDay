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
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    <div class="payment-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <div class="order-summary">
            <h2>Order Summary</h2>
            <div class="order-details">
                <p>
                    <span id="booking-type">Turf Booking</span>
                    <span id="booking-name"><?php echo htmlspecialchars($venue_name); ?></span>
                </p>
                <p>
                    <span>Date & Time</span>
                    <span id="booking-datetime"><?php echo htmlspecialchars($booking_date . ' | ' . $booking_time); ?></span>
                </p>
                <p class="total-amount">
                    <span>Total Amount</span>
                    <span id="total-amount">₹<?php echo htmlspecialchars($price); ?></span>
                </p>
            </div>
        </div>

        <div class="payment-methods">
            <h3>Select Payment Method</h3>
            <div class="payment-method" data-method="upi">
                <i class="fas fa-mobile-alt"></i>
                <span>UPI / QR Code</span>
            </div>
            <div class="payment-method" data-method="card">
                <i class="fas fa-credit-card"></i>
                <span>Credit / Debit Card</span>
            </div>
            <div class="payment-method" data-method="netbanking">
                <i class="fas fa-university"></i>
                <span>Net Banking</span>
            </div>
        </div>

        <div class="qr-section" id="qrSection">
            <h3>Scan QR Code to Pay</h3>
            <p class="qr-amount" id="qrAmount">₹<?php echo htmlspecialchars($price); ?></p>
            <div id="qrcode"></div>
            <p class="upi-id">UPI ID: gameday@ybl</p>
            <p>Or click below to pay using other UPI apps</p>
        </div>

        <button id="pay-button" class="payment-button">Proceed to Pay</button>
    </div>

    <div class="overlay" id="overlay"></div>

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

        // Update booking details
        function updateBookingDetails() {
            // Generate QR code on page load
            const amount = <?php echo json_encode($price); ?>;
            generateQRCode(amount);
        }

        // Handle payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', () => {
                // Remove selected class from all methods
                document.querySelectorAll('.payment-method').forEach(m => 
                    m.classList.remove('selected'));
                
                // Add selected class to clicked method
                method.classList.add('selected');
                
                // Show/hide QR section for UPI
                const qrSection = document.getElementById('qrSection');
                if (method.dataset.method === 'upi') {
                    qrSection.classList.add('visible');
                } else {
                    qrSection.classList.remove('visible');
                }
            });
        });

        // Handle proceed to pay button
        document.getElementById('pay-button').addEventListener('click', function() {
            // Get selected payment method
            const selectedMethod = document.querySelector('.payment-method.selected');
            
            if (!selectedMethod) {
                alert('Please select a payment method');
                return;
            }
            
            // Redirect to payment result page
            window.location.href = 'payment-result.php?method=' + selectedMethod.dataset.method;
        });

        // Initialize page
        updateBookingDetails();
    </script>
</body>
</html>