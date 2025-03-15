<?php
session_start();
include 'db.php'; // Ensure this file contains the database connection

if ($stmt->execute()) {
    $_SESSION['booking_successful'] = true;
    header("Location: payment-ground-success.php?email=$email&venue_id=$venue_id&date=$booking_date&time=$booking_time");
    exit();
} else {
    // Log the error
    error_log("Database insertion failed: " . $stmt->error);
    echo "<script>alert('Booking failed: " . $stmt->error . "');</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from session
    $email = $_SESSION['user_email'];
    $booking_date = date('Y-m-d', strtotime($_SESSION['booking_date'])); // Convert to MySQL format
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <link rel="stylesheet" href="CSS\payment-ground.css">
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

        <form method="POST" action="">
            <button type="submit" id="pay-button" class="payment-button">Proceed to Pay</button>
        </form>
    </div>

    <div class="success-popup" id="successPopup">
        <i class="fas fa-check-circle"></i>
        <h2>Payment Successful!</h2>
        <p>Redirecting to confirmation page...</p>
    </div>

    <div class="error-popup" id="errorPopup">
        <i class="fas fa-times-circle"></i>
        <h2>Payment Failed</h2>
        <p>Your payment could not be processed. Please try a different payment method.</p>
        <button onclick="closeErrorPopup()">Try Again</button>
    </div>

    <div class="overlay" id="overlay"></div>

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

        // Show success popup
        function showSuccessPopup() {
            document.getElementById('successPopup').classList.add('show');
            document.getElementById('overlay').classList.add('show');
            
            setTimeout(() => {
                window.location.href = 'payment-ground-success.php?venue_id=<?php echo htmlspecialchars($venue_id); ?>&booking_success=true';
            }, 2000);
        }

        // Show error popup
        function showErrorPopup() {
            document.getElementById('errorPopup').classList.add('show');
            document.getElementById('overlay').classList.add('show');
        }

        // Close error popup
        function closeErrorPopup() {
            document.getElementById('errorPopup').classList.remove('show');
            document.getElementById('overlay').classList.remove('show');
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
                    showSuccessPopup();
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

            const rzp = new Razorpay(options);
            
            document.getElementById('pay-button').onclick = function(e) {
                e.preventDefault();

                const selectedMethod = document.querySelector('.payment-method.selected');
                if (!selectedMethod) {
                    alert('Please select a payment method');
                    return;
                }
                
                switch(selectedMethod.dataset.method) {
                    case 'upi':
                    case 'card':
                        // Simulate payment success after 2 seconds for both UPI and card
                        setTimeout(showSuccessPopup, 2000);
                        break;
                    case 'netbanking':
                        // Simulate payment failure for net banking
                        setTimeout(showErrorPopup, 1500);
                        break;
                }
                return false;
            };
        }

        // Initialize page
        updateBookingDetails();
        initializePayment();
    </script>
</body>
</html> 