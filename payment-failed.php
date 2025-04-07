<?php
session_start();

// Get error message if any
$error_message = isset($_SESSION['booking_error']) ? $_SESSION['booking_error'] : 'Your payment could not be processed.';

// Clear the error message from session
if (isset($_SESSION['booking_error'])) {
    unset($_SESSION['booking_error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Payment Failed</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\payment.css">
    <link rel="stylesheet" href="CSS\main.css">
    <style>
        .failed-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .failed-icon {
            font-size: 60px;
            color: #ff5252;
            margin-bottom: 20px;
        }
        
        .failed-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }
        
        .failed-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .retry-button {
            background-color: #b9ff00;
            color: #000;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        
        .retry-button:hover {
            background-color: #9edb00;
        }
        
        .back-to-home {
            margin-top: 20px;
            display: block;
            color: #666;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="failed-container">
        <i class="fas fa-times-circle failed-icon"></i>
        <h2 class="failed-title">Payment Failed</h2>
        <p class="failed-message"><?php echo htmlspecialchars($error_message); ?></p>
        <a href="payment-ground.php" class="retry-button">Try Again</a>
        <a href="turfbooknow.php" class="back-to-home">Back to Booking</a>
    </div>
</body>
</html>