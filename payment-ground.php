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
    <div class="payment-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <div class="order-summary">
            <h2>Order Summary</h2>
            <div class="order-details">
                <p>
                    <span id="booking-type">Turf Booking</span>
                    <span id="booking-name">Don Bosco Turf</span>
                </p>
                <p>
                    <span>Date & Time</span>
                    <span id="booking-datetime">May 15, 2024 | 18:00 - 19:00</span>
                </p>
                <p class="total-amount">
                    <span>Total Amount</span>
                    <span id="total-amount">₹1200</span>
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
            <p class="qr-amount" id="qrAmount">₹1200</p>
            <div id="qrcode"></div>
            <p class="upi-id">UPI ID: gameday@ybl</p>
            <p>Or click below to pay using other UPI apps</p>
        </div>

        <button id="pay-button" class="payment-button">Proceed to Pay</button>
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
        // Function to get URL parameters
        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                type: params.get('type') || 'turf',
                amount: parseInt(params.get('amount')) || 1200,
                name: params.get('name') || 'Don Bosco Turf',
                datetime: params.get('datetime') || 'May 15, 2024 | 18:00 - 19:00'
            };
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

        // Update page with booking details
        function updateBookingDetails() {
            const params = getUrlParams();
            document.getElementById('booking-type').textContent = 
                params.type === 'academy' ? 'Academy Enrollment' : 'Turf Booking';
            document.getElementById('booking-name').textContent = params.name;
            document.getElementById('booking-datetime').textContent = params.datetime;
            document.getElementById('total-amount').textContent = `₹${params.amount}`;
            document.getElementById('qrAmount').textContent = `₹${params.amount}`;
            generateQRCode(params.amount);
        }

        // Show success popup
        function showSuccessPopup() {
            document.getElementById('successPopup').classList.add('show');
            document.getElementById('overlay').classList.add('show');
            
            setTimeout(() => {
                window.location.href = 'booking-success.html';
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
                description: `Payment for ${params.type === 'academy' ? 'Academy Enrollment' : 'Turf Booking'}`,
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
            
            document.getElementById('pay-button').onclick = function() {
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
            };
        }

        // Initialize page
        updateBookingDetails();
        initializePayment();
    </script>
</body>
</html> 