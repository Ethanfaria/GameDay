<?php
session_start();
include 'db.php'; // Ensure this file contains the database connection

// Try to get academy_id from session first, then from GET parameters as fallback
$ac_id = isset($_SESSION['academy_id']) ? $_SESSION['academy_id'] : 
         (isset($_GET['ac_id']) ? $_GET['ac_id'] : '');

if (empty($ac_id)) {
    echo "<script>alert('Academy ID is missing.');</script>";
    echo "<script>window.location.href = 'academies.php';</script>";
    exit();
}

// Store the academy_id in session for later use
$_SESSION['academy_id'] = $ac_id;

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "<script>alert('Please log in to complete your enrollment.');</script>";
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get selected payment method
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'upi';
    
    // Redirect to payment result page instead of processing payment here
    header("Location: payment-academy-result.php?method=$payment_method");
    exit();
}

$sql = "SELECT * FROM academys WHERE ac_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ac_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $academy = $result->fetch_assoc();
    
    // Store academy details in session
    $_SESSION['academy_name'] = $academy['aca_nm'];
    $_SESSION['academy_location'] = $academy['ac_location'];
    $_SESSION['academy_charges'] = $academy['ac_charges'];
    $_SESSION['academy_duration'] = $academy['duration'];
    
    // Get values for display
    $academy_name = $academy['aca_nm'];
    $academy_location = $academy['ac_location'];
    $academy_charges = $academy['ac_charges'];
    $enrollment_duration = $academy['duration'] ? $academy['duration'] : 1;
} else {
    echo "<p>Academy not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Academy Payment</title>
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
    <div class="payment-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <h1 class="summary-title">Order Summary</h1>
        <div class="order-summary">
                <div class="summary-row">
                    <div id="booking-type" class="summary-label">Academy Enrollment</div>
                    <div id="booking-name" class="summary-value"><?php echo htmlspecialchars($academy_name); ?></div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Location</div>
                    <div id="booking-location" class="summary-value"><?php echo htmlspecialchars($academy_location); ?></div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Duration</div>
                    <div id="enrollment-duration" class="summary-value"><?php echo htmlspecialchars($enrollment_duration); ?> months</div>
                </div>
                <div class="total-row" >
                    <div class="total-label">Total Amount</div>
                    <div id="total-amount" class="total-value">₹<?php echo htmlspecialchars($academy_charges * $enrollment_duration); ?></div>
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
                tn: "Academy Enrollment Payment"
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
            const amount = <?php echo json_encode($academy_charges * $enrollment_duration); ?>;
            
            // Generate QR code for UPI payment
            generateQRCode(amount);
            
            // Default to UPI option
            upiOption.click();
        });
    </script>
</body>
</html>