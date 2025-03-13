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
    <link rel="stylesheet" href="CSS\tournpayment.css">
</head>
<body>
    <div class="payment-container">
        <a href="#" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <h1 class="summary-title">Order Summary</h1>
        
        <div class="order-summary">
            <div class="summary-row">
                <div class="summary-label">Turf Booking</div>
                <div class="summary-value">Don Bosco Turf</div>
            </div>
            
            <div class="summary-row">
                <div class="summary-label">Date & Time</div>
                <div class="summary-value">May 15, 2024 | 18:00 - 19:00</div>
            </div>
            
            <div class="total-row">
                <div class="total-label">Total Amount</div>
                <div class="total-value">₹1200</div>
            </div>
        </div>
        
        <h2 class="payment-section-title">Select Payment Method</h2>
        
        <div class="payment-option" id="upiOption">
            <div class="payment-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <div class="payment-label">UPI / QR Code</div>
        </div>
        
        <div class="payment-option" id="cardOption">
            <div class="payment-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="payment-label">Credit / Debit Card</div>
        </div>
        
        <div class="payment-option" id="bankingOption">
            <div class="payment-icon">
                <i class="fas fa-university"></i>
            </div>
            <div class="payment-label">Net Banking</div>
        </div>

        <!-- UPI Payment Details -->
        <div class="payment-details" id="upiDetails">
            <div class="qr-section">
                <div class="qr-code">
                    <!-- Using placeholder image API for QR code -->
                    <img src="/api/placeholder/200/200" alt="UPI QR Code">
                </div>
                <div class="payment-instructions">
                    <h3>Scan to Pay</h3>
                    <p>Use any UPI app to scan this QR code and make your payment</p>
                    <div class="payment-upi-id">gameday@ybl</div>
                    <p>Or use UPI ID to pay directly through your UPI app</p>
                </div>
            </div>
        </div>

        <!-- Card Payment Form (Hidden initially) -->
        <div class="payment-details" id="cardDetails">
            <div class="qr-section">
                <div class="payment-instructions">
                    <h3>Card Payment</h3>
                    <p>You will be redirected to secure payment gateway</p>
                </div>
            </div>
        </div>

        <!-- Net Banking Form (Hidden initially) -->
        <div class="payment-details" id="bankingDetails">
            <div class="qr-section">
                <div class="payment-instructions">
                    <h3>Net Banking</h3>
                    <p>You will be redirected to your bank's secure login page</p>
                </div>
            </div>
        </div>
        
        <button class="proceed-button">Proceed to Pay</button>
    </div>

    <script>
        // Get elements
        const upiOption = document.getElementById('upiOption');
        const cardOption = document.getElementById('cardOption');
        const bankingOption = document.getElementById('bankingOption');
        
        const upiDetails = document.getElementById('upiDetails');
        const cardDetails = document.getElementById('cardDetails');
        const bankingDetails = document.getElementById('bankingDetails');
        
        // Hide all payment details initially
        function hideAllDetails() {
            upiDetails.style.display = 'none';
            cardDetails.style.display = 'none';
            bankingDetails.style.display = 'none';
            
            upiOption.classList.remove('selected');
            cardOption.classList.remove('selected');
            bankingOption.classList.remove('selected');
        }
        
        // Show UPI details when UPI option is clicked
        upiOption.addEventListener('click', function() {
            hideAllDetails();
            upiDetails.style.display = 'block';
            upiOption.classList.add('selected');
        });
        
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
        
        // Trigger click on UPI option by default to show QR code initially
        upiOption.click();
    </script>
</body>
</html>