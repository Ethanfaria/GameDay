<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

include 'db.php'; // Ensure this file contains the database connection

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from session
    $email = $_SESSION['user_email'];
    $booking_date = $_SESSION['booking_date']; // Convert to MySQL format
    $booking_time = (string)$_SESSION['booking_time']; // Ensure this is a string
    $venue_name = $_SESSION['venue_name'];
    $price = $_SESSION['price'];
    $venue_id = $_SESSION['venue_id'];

    // Check if required session variables are set
    if (!isset($email, $booking_date, $booking_time, $venue_id)) {
        die("Error: Missing required booking details.");
    }

    // Check if the user exists
    $checkUser = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows === 0) {
        die("Error: The user email does not exist in the database.");
    }

    // Check if booking already exists
    $checkBooking = $conn->prepare("SELECT bk_dur FROM book WHERE email = ? AND venue_id = ? AND bk_date = ?");
    $checkBooking->bind_param("sss", $email, $venue_id, $booking_date);
    $checkBooking->execute();
    $bookingResult = $checkBooking->get_result();
    
    // Fetch all booked slots
    $booked_slots = [];
    while ($row = $bookingResult->fetch_assoc()) {
        $booked_slots[] = $row['bk_dur']; // Store booked time slots
    }
    
    if (in_array($booking_time, $booked_slots)) {
        echo "<script>alert('You have already booked this slot.');</script>";
    } else {
        // Insert booking into the database
        $sql = "INSERT INTO book (email, bk_date, bk_dur, venue_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssss", $email, $booking_date, $booking_time, $venue_id);

        if ($stmt->execute()) {

            $_SESSION['booking_successful'] = true;

            header("Location: payment-ground-success.php?email=$email&venue_id=$venue_id&date=$booking_date&time=$booking_time");
            exit();
        }    

        $stmt->close();
    }
    $checkBooking->close();
    $checkUser->close();
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
    <link rel="stylesheet" href="CSS\payment.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>

<?php 
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
    } else {
        echo "<p>Ground not found.</p>";
        exit();
    }
?>
    <div class="payment-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <h2 class="summary-title">Order Summary</h2>
        
        <div class="order-summary">
            <div class="summary-row">
                <span class="summary-label">Booking Type</span>
                <span class="summary-value" id="booking-type">Turf Booking</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Venue</span>
                <span class="summary-value" id="booking-name"><?php echo htmlspecialchars($venue_name); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Date & Time</span>
                <span class="summary-value" id="booking-datetime"><?php echo htmlspecialchars($booking_date . ' | ' . $booking_time); ?></span>
            </div>
            <div class="total-row">
                <span class="total-label">Total Amount</span>
                <span class="total-value" id="total-amount">₹<?php echo htmlspecialchars($price); ?></span>
            </div>
        </div>

        <h3 class="payment-section-title">Select Payment Method</h3>
        
        <div class="payment-option" data-method="upi">
            <div class="payment-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <span class="payment-label">UPI / QR Code</span>
        </div>
        
        <div class="payment-option" data-method="card">
            <div class="payment-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <span class="payment-label">Credit / Debit Card</span>
        </div>
        
        <div class="payment-option" data-method="netbanking">
            <div class="payment-icon">
                <i class="fas fa-university"></i>
            </div>
            <span class="payment-label">Net Banking</span>
        </div>

        <div class="qr-section" id="qrSection" style="display: none;">
            <h3>Scan QR Code to Pay</h3>
            <p class="qr-amount" id="qrAmount">₹<?php echo htmlspecialchars($price); ?></p>
            <div class="qr-code">
                <div id="qrcode"></div>
            </div>
            <div class="payment-instructions">
                <h3>Payment Instructions</h3>
                <p>Open any UPI app and scan the QR code above to make the payment</p>
            </div>
            <div class="payment-upi-id">UPI ID: gameday@ybl</div>
            <p class="payment-instructions">Or use your preferred UPI app to pay</p>
        </div>

        <form method="POST" action="">
            <button type="submit" id="pay-button" class="proceed-button">Proceed to Pay</button>
        </form>
    </div>
    
    <script>
        // Get URL parameters
        function getUrlParams() {
            const params = {};
            const queryString = window.location.search.substring(1);
            const pairs = queryString.split('&');
            
            for (const pair of pairs) {
                const [key, value] = pair.split('=');
                params[key] = decodeURIComponent(value || '');
            }
            
            return params;
        }

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
            // No need to update anything as we're using PHP to set these values
            // This function is now just a placeholder
            
            // Generate QR code on page load
            const amount = <?php echo json_encode($price); ?>;
            generateQRCode(amount);
        }

        // Handle payment method selection
        document.querySelectorAll('.payment-option').forEach(method => {
            method.addEventListener('click', () => {
                // Remove selected class from all methods
                document.querySelectorAll('.payment-option').forEach(m => 
                    m.classList.remove('selected'));
                
                // Add selected class to clicked method
                method.classList.add('selected');
                
                // Show/hide QR section for UPI
                const qrSection = document.getElementById('qrSection');
                if (method.dataset.method === 'upi') {
                    qrSection.style.display = 'flex';
                } else {
                    qrSection.style.display = 'none';
                }
            });
        });

        // Initialize Razorpay payment
        function initializePayment() {
            const params = getUrlParams();
            
            const options = {
                key: 'YOUR_RAZORPAY_KEY',
                amount: params.amount * 100,
                currency: 'INR',
                name: 'GAME DAY',
                description: `Payment for Turf Booking`,
                image: 'your-logo-url.png',
                handler: function(response) {
                    // Direct redirect instead of showing popup
                    window.location.href = 'payment-ground-success.php?venue_id=<?php echo htmlspecialchars($venue_id); ?>&booking_success=true';
                },
                prefill: {
                    name: '',
                    email: '',
                    contact: ''
                },
                theme: {
                    color: '#b9ff00'
                }
            };
        }

        // Initialize page
        updateBookingDetails();
        initializePayment();
        
        // Select UPI by default
        document.querySelector('.payment-option[data-method="upi"]').click();
    </script>
    
    <style>
    /* Additional styles for QR code */
    #qrcode {
        width: 100%;
        height: 100%;
    }
    
    .qr-amount {
        font-size: 24px;
        font-weight: 700;
        color: var(--neon-green);
        margin-bottom: 15px;
    }
    </style>
</body>
</html>