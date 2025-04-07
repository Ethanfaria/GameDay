<?php
session_start();
include 'db.php'; // Ensure this file contains the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_SESSION['user_email'])) {
        echo "<script>alert('Please log in to complete your registration.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();
    }

    // Retrieve data from session
    $email = $_SESSION['user_email'];
    $tr_id = $_SESSION['tournament_id'];
    $registration_date = date('Y-m-d');

    // Check if required session variables are set
    if (!isset($email, $tr_id)) {
        die("Error: Missing required registration details.");
    }

    // Check if the user exists
    $checkUser = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows === 0) {
        die("Error: The user email does not exist in the database.");
    }

    // Check if registration already exists
    $checkRegistration = $conn->prepare("SELECT * FROM register WHERE email = ? AND tr_id = ?");
    $checkRegistration->bind_param("ss", $email, $tr_id);
    $checkRegistration->execute();
    $registrationResult = $checkRegistration->get_result();
    
    if ($registrationResult->num_rows > 0) {
        echo "<script>alert('You are already registered for this tournament.');</script>";
    } else {
        // Insert registration into the database
        $sql = "INSERT INTO tournament_registrations (tr_id, reg_date, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("sss", $tr_id, $registration_date, $email);

        if ($stmt->execute()) {
            $_SESSION['registration_successful'] = true;
            header("Location: payment-tournament-success.php?tr_id=$tr_id&registration_success=true");
            exit();
        } else {
            // Handle error quietly or with a more appropriate message
            die("Error: " . $stmt->error);
        }
        $stmt->close();
    }
    $checkRegistration->close();
    $checkUser->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Tournament Payment</title>
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
    // Try to get tournament_id from session first, then from GET parameters as fallback
    $tr_id = isset($_SESSION['tournament_id']) ? $_SESSION['tournament_id'] : 
             (isset($_GET['tr_id']) ? $_GET['tr_id'] : '');
    
    $sql = "SELECT t.*, v.venue_nm, v.location 
            FROM tournaments t 
            LEFT JOIN venue v ON t.venue_id = v.venue_id
            WHERE t.tr_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tr_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tournament = $result->fetch_assoc();
        
        // Retrieve tournament details from session or fall back to available data
        $tournament_name = isset($_SESSION['tournament_name']) ? $_SESSION['tournament_name'] : $tournament['tr_name'];
        $tournament_venue = isset($_SESSION['tournament_venue']) ? $_SESSION['tournament_venue'] : $tournament['venue_nm'];
        $tournament_location = isset($_SESSION['tournament_location']) ? $_SESSION['tournament_location'] : $tournament['location'];
        $tournament_fee = isset($_SESSION['tournament_fee']) ? $_SESSION['tournament_fee'] : $tournament['entry_fee'];
        $tournament_date = isset($_SESSION['tournament_date']) ? $_SESSION['tournament_date'] : $tournament['start_date'];
    } else {
        echo "<p>Tournament not found.</p>";
        exit();
    }
?>
    <div class="payment-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <h1 class="summary-title">Order Summary</h1>
        <div class="order-summary">
                <div class="summary-row">
                    <div id="booking-type" class="summary-label">Tournament Registration</div>
                    <div id="booking-name" class="summary-value"><?php echo htmlspecialchars($tournament_name); ?></div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Venue</div>
                    <div id="booking-venue" class="summary-value"><?php echo htmlspecialchars($tournament_venue); ?></div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Location</div>
                    <div id="booking-location" class="summary-value"><?php echo htmlspecialchars($tournament_location); ?></div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Date</div>
                    <div id="tournament-date" class="summary-value"><?php echo date('d M Y', strtotime($tournament_date)); ?></div>
                </div>
                <div class="total-row">
                    <div class="total-label">Registration Fee</div>
                    <div id="total-amount" class="total-value">₹<?php echo htmlspecialchars($tournament_fee); ?></div>
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
            <button type="submit" class="proceed-button" id="proceedButton">Proceed to Pay</button>
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
                tn: "Tournament Registration Payment"
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
        const proceedButton = document.getElementById('proceedButton');

        // Show card details when card option is clicked
        cardOption.addEventListener('click', function() {
            hideAllDetails();
            cardDetails.style.display = 'block';
            cardOption.classList.add('selected');
        });
        
        // Show banking details when banking option is clicked
        bankingOption.addEventListener('click', function() {
            hideAllDetails();
            bankingDetails.style.display = 'block';
            bankingOption.classList.add('selected');
        });

        // Show UPI details when UPI option is clicked
        upiOption.addEventListener('click', function() {
            hideAllDetails();
            upiDetails.style.display = 'block';
            upiOption.classList.add('selected');
        });

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

        // Initialize Razorpay payment
        function initializePayment() {
            // Amount calculation
            const amount = <?php echo json_encode($tournament_fee); ?>;
            
            // Generate QR code for UPI payment
            generateQRCode(amount);
            
            // Setup proceed button action
            proceedButton.addEventListener('click', function() {
                if (upiOption.classList.contains('selected')) {
                    // For UPI, just show success message after a delay to simulate payment
                    setTimeout(() => {
                        showSuccessPopup();
                    }, 1500);
                } else if (cardOption.classList.contains('selected') || bankingOption.classList.contains('selected')) {
                    // For card and netbanking, initialize Razorpay
                    const options = {
                        key: 'YOUR_RAZORPAY_KEY',
                        amount: amount * 100,
                        currency: 'INR',
                        name: 'GAME DAY',
                        description: `Payment for Tournament Registration`,
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
                    
                    // This is where you would typically initialize Razorpay
                    // For demonstration, we'll just simulate success
                    setTimeout(() => {
                        showSuccessPopup();
                    }, 1500);
                }
            });
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            upiOption.click();
            initializePayment();
        });
    </script>
</body>
</html>