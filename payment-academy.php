<?php
session_start();
include 'db.php'; // Ensure this file contains the database connection



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_SESSION['user_email'])) {
        echo "<script>alert('Please log in to complete your enrollment.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();
    }

    // Retrieve data from session
    $email = $_SESSION['user_email'];
    $ac_id = $_SESSION['academy_id'];
    $enrollment_duration = 3; 
    $enrollment_date = date('Y-m-d');

    // Check if required session variables are set
    if (!isset($email, $ac_id)) {
        die("Error: Missing required enrollment details.");
    }

    // Check if the user exists
    $checkUser = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows === 0) {
        die("Error: The user email does not exist in the database.");
    }

    // Check if enrollment already exists
    $checkEnrollment = $conn->prepare("SELECT * FROM enroll WHERE email = ? AND ac_id = ?");
    $checkEnrollment->bind_param("ss", $email, $ac_id);
    $checkEnrollment->execute();
    $enrollmentResult = $checkEnrollment->get_result();
    
    if ($enrollmentResult->num_rows > 0) {
        echo "<script>alert('You are already enrolled in this academy.');</script>";
    } else {
        // Insert enrollment into the database
        $sql = "INSERT INTO enroll (ac_id, en_dur, en_date, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("siis", $ac_id, $enrollment_duration, $enrollment_date, $email);

        if ($stmt->execute()) {
            $_SESSION['enrollment_successful'] = true;
            header("Location: payment-academy-success.php?ac_id=$ac_id&enrollment_success=true");
            exit();
        } else {
            // Handle error quietly or with a more appropriate message
            die("Error: " . $stmt->error);
        }
        $stmt->close();
    }
    $checkEnrollment->close();
    $checkUser->close();
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
    <link rel="stylesheet" href="CSS\payment-ground.css">
    <link rel="stylesheet" href="CSS\payment.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>

<?php 
    // Try to get academy_id from session first, then from GET parameters as fallback
    $ac_id = isset($_SESSION['academy_id']) ? $_SESSION['academy_id'] : 
             (isset($_GET['ac_id']) ? $_GET['ac_id'] : '');
    
    $sql = "SELECT * FROM academys WHERE ac_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ac_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $academy = $result->fetch_assoc();
        
        // Retrieve enrollment details from session or fall back to available data
        $academy_name = isset($_SESSION['academy_name']) ? $_SESSION['academy_name'] : $academy['aca_nm'];
        $academy_location = isset($_SESSION['academy_location']) ? $_SESSION['academy_location'] : $academy['ac_location'];
        $academy_charges = isset($_SESSION['academy_charges']) ? $_SESSION['academy_charges'] : $academy['ac_charges'];
        $enrollment_duration = 3; // Default enrollment duration in months
    } else {
        echo "<p>Academy not found.</p>";
        exit();
    }
?>
    <div class="payment-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <h1  class="summary-title">Order Summary</h1>
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
            <button type="submit" class="proceed-button" id="proceedButton">Proceed to Pay</button>
        </form>
    </div>

    <div id="overlay">
        <div class="popup" id="successPopup">
            <h3>Payment Successful!</h3>
            <p>Thank you for your payment. You will be redirected shortly.</p>
        </div>
        <div class="popup" id="errorPopup">
            <h3>Payment Failed</h3>
            <p>Something went wrong with your payment. Please try again.</p>
            <button class="popup-button" onclick="closeErrorPopup()">Close</button>
        </div>
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

        // Show success popup
        function showSuccessPopup() {
            document.getElementById('successPopup').classList.add('show');
            document.getElementById('overlay').classList.add('show');
            
            setTimeout(() => {
                window.location.href = 'payment-academy-success.php?ac_id=<?php echo htmlspecialchars($ac_id); ?>&enrollment_success=true';
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
            const amount = <?php echo json_encode($academy_charges * $enrollment_duration); ?>;
            
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
                        description: `Payment for Academy Enrollment`,
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