<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Academy Enrollment Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <style>
        :root {
            --dark-green: #0a2e1a;
            --neon-green: #b9ff00;
            --dark-gray: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', 'Arial', sans-serif;
        }

        body {
            background-color: var(--dark-green);
            color: white;
            min-height: 100vh;
            padding: 20px;
        }

        .payment-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .order-summary {
            margin-bottom: 30px;
        }

        .order-summary h2 {
            color: var(--neon-green);
            margin-bottom: 20px;
        }

        .order-details {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .order-details p {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
        }

        .enrollment-details {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .enrollment-details h3 {
            color: var(--neon-green);
            margin-bottom: 15px;
        }

        .enrollment-details ul {
            list-style: none;
        }

        .enrollment-details li {
            margin: 10px 0;
            display: flex;
            align-items: center;
        }

        .enrollment-details li i {
            color: var(--neon-green);
            margin-right: 10px;
        }

        .total-amount {
            font-size: 1.2em;
            font-weight: bold;
            color: var(--neon-green);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 10px;
            margin-top: 10px;
        }

        .payment-button {
            background-color: var(--neon-green);
            color: var(--dark-green);
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .payment-button:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        .back-button {
            display: inline-block;
            color: white;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-button i {
            margin-right: 5px;
        }

        .payment-methods {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .payment-method {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .payment-method.selected {
            background-color: var(--neon-green);
            color: var(--dark-green);
        }

        .payment-method i {
            font-size: 24px;
            width: 30px;
            text-align: center;
        }

        .qr-section {
            display: none;
            text-align: center;
            margin: 20px 0;
            animation: fadeIn 0.5s ease;
        }

        .qr-section.visible {
            display: block;
        }

        #qrcode {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px auto;
        }

        #qrcode img {
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .qr-amount {
            font-size: 1.2em;
            color: var(--neon-green);
            margin: 15px 0;
            font-weight: bold;
        }

        .upi-id {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            border-radius: 25px;
            margin: 15px auto;
            display: inline-block;
            font-family: monospace;
            font-size: 1.1em;
        }

        .success-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background-color: var(--dark-green);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            z-index: 1000;
            opacity: 0;
            transition: all 0.3s ease;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
        }

        .success-popup.show {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .success-popup i {
            font-size: 50px;
            color: var(--neon-green);
            margin-bottom: 20px;
        }

        .error-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background-color: #420d0d;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            z-index: 1000;
            opacity: 0;
            transition: all 0.3s ease;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
        }

        .error-popup.show {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .error-popup i {
            font-size: 50px;
            color: #ff4444;
            margin-bottom: 20px;
        }

        .error-popup button {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .error-popup button:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <?php
    // Get parameters from URL
    $academy_id = isset($_GET['ac_id']) ? $_GET['ac_id'] : '';
    $academy_name = isset($_GET['name']) ? $_GET['name'] : '';
    $academy_location = isset($_GET['location']) ? $_GET['location'] : '';
    $monthly_fee = isset($_GET['amount']) ? intval($_GET['amount']) : 0;

    // Calculate total amount (3 months)
    $total_amount = $monthly_fee * 3;

    // Format amounts for display
    $formatted_monthly_fee = number_format($monthly_fee);
    $formatted_total = number_format($total_amount);
    ?>
    <div class="payment-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <div class="order-summary">
            <h2>Enrollment Summary</h2>
            <div class="order-details">
                <p>
                    <span>Academy</span>
                    <span id="academy-name"><?php echo htmlspecialchars($academy_name); ?></span>
                </p>
                <p>
                    <span>Location</span>
                    <span id="academy-location"><?php echo htmlspecialchars($academy_location); ?></span>
                </p>
            </div>

            <div class="enrollment-details">
                <h3>Program Details</h3>
                <ul>
                    <li><i class="fas fa-calendar"></i> 5 sessions per week</li>
                    <li><i class="fas fa-clock"></i> 2 hours per session</li>
                    <li><i class="fas fa-users"></i> Maximum 15 students per batch</li>
                    <li><i class="fas fa-certificate"></i> Professional UEFA licensed coaches</li>
                </ul>
            </div>

            <div class="order-details">
                <p>
                    <span>Monthly Fee</span>
                    <span id="monthly-fee">₹<?php echo $formatted_monthly_fee; ?></span>
                </p>
                <p>
                    <span>Duration</span>
                    <span>3 months</span>
                </p>
                <p class="total-amount">
                    <span>Total Amount</span>
                    <span id="total-amount">₹<?php echo $formatted_total; ?></span>
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
            <p class="qr-amount" id="qrAmount">₹<?php echo $formatted_total; ?></p>
            <div id="qrcode"></div>
            <p class="upi-id">gameday@ybl</p>
        </div>

        <button id="pay-button" class="payment-button">Complete Enrollment</button>
    </div>

    <div class="success-popup" id="successPopup">
        <i class="fas fa-check-circle"></i>
        <h2>Enrollment Successful!</h2>
        <p>Welcome to the academy. You will receive a confirmation email shortly.</p>
    </div>

    <div class="error-popup" id="errorPopup">
        <i class="fas fa-times-circle"></i>
        <h2>Payment Failed</h2>
        <p>Your payment could not be processed. Please try a different payment method.</p>
        <button id="retry-button">Try Again</button>
    </div>

    <div class="overlay" id="overlay"></div>

    <script>
        // Function to get URL parameters
        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                ac_id: params.get('ac_id') || '',
                name: params.get('name') || '',
                location: params.get('location') || '',
                amount: parseInt(params.get('amount')) || 0
            };
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
            
            document.getElementById('qrcode').innerHTML = '';
            new QRCode(document.getElementById('qrcode'), {
                text: upiUrl,
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }

        // Update page with enrollment details
        function updateEnrollmentDetails() {
            const params = getUrlParams();
            const monthlyFee = params.amount;
            const totalAmount = monthlyFee * 3; // 3 months

            document.getElementById('academy-name').textContent = params.name;
            document.getElementById('academy-location').textContent = params.location;
            document.getElementById('monthly-fee').textContent = `₹${monthlyFee.toLocaleString()}`;
            document.getElementById('total-amount').textContent = `₹${totalAmount.toLocaleString()}`;
            document.getElementById('qrAmount').textContent = `₹${totalAmount.toLocaleString()}`;
            generateQRCode(totalAmount);
        }

        // Show success popup
        function showSuccessPopup() {
            document.getElementById('successPopup').classList.add('show');
            document.getElementById('overlay').classList.add('show');
            
            setTimeout(() => {
                window.location.href = 'payment-academy-success.php';
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

        // Handle payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', () => {
                document.querySelectorAll('.payment-method').forEach(m => 
                    m.classList.remove('selected'));
                method.classList.add('selected');
                
                const qrSection = document.getElementById('qrSection');
                if (method.dataset.method === 'upi') {
                    qrSection.classList.add('visible');
                } else {
                    qrSection.classList.remove('visible');
                }
            });
        });

        // Handle retry button click
        document.getElementById('retry-button').addEventListener('click', function() {
            closeErrorPopup();
        });

        // Initialize payment
        document.getElementById('pay-button').addEventListener('click', function() {
            const selectedMethod = document.querySelector('.payment-method.selected');
            if (!selectedMethod) {
                alert('Please select a payment method');
                return;
            }
            
            const params = getUrlParams();
            
            switch(selectedMethod.dataset.method) {
                case 'upi':
                    // Simulate UPI payment success
                    setTimeout(showSuccessPopup, 2000);
                    break;
                case 'card':
                    // Initialize Razorpay for card payment
                    const options = {
                        key: 'YOUR_RAZORPAY_KEY', // Replace with actual key
                        amount: params.amount * 3 * 100, // Amount in paise
                        currency: 'INR',
                        name: 'GAME DAY',
                        description: 'Academy Enrollment Payment',
                        handler: function() {
                            showSuccessPopup();
                        },
                        prefill: {
                            name: '',
                            email: '',
                            contact: ''
                        },
                        theme: {
                            color: '#b9ff00'
                        },
                        modal: {
                            ondismiss: function() {
                                console.log('Payment cancelled');
                            }
                        }
                    };
                    
                    const rzp = new Razorpay(options);
                    rzp.open();
                    break;
                case 'netbanking':
                    // Simulate error for netbanking (for demonstration)
                    setTimeout(showErrorPopup, 1500);
                    break;
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            updateEnrollmentDetails();
        });
    </script>
</body>
</html>