<?php
session_start();
include 'db.php';

// Process payment if success button was clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_status'])) {
    if ($_POST['payment_status'] === 'success') {
        // Get booking details from session
        $email = $_SESSION['user_email'];
        $booking_date = $_SESSION['booking_date'];
        $booking_time = (string)$_SESSION['booking_time'];
        $venue_id = $_SESSION['venue_id'];

        // Validate session data exists
        if (!isset($email, $booking_date, $booking_time, $venue_id)) {
            $_SESSION['booking_error'] = "Missing required booking details.";
            header("Location: payment-failed.php");
            exit();
        }

        // Check if the user exists
        $checkUser = $conn->prepare("SELECT email FROM user WHERE email = ?");
        $checkUser->bind_param("s", $email);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows === 0) {
            $_SESSION['booking_error'] = "The user email does not exist in the database.";
            header("Location: payment-failed.php");
            exit();
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
            // Slot already booked, redirect with error
            $_SESSION['booking_error'] = 'You have already booked this slot.';
            header('Location: payment-failed.php');
            exit();
        } else {
            // Insert booking into the database
            $sql = "INSERT INTO book (email, bk_date, bk_dur, venue_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                $_SESSION['booking_error'] = "Error preparing statement: " . $conn->error;
                header("Location: payment-failed.php");
                exit();
            }

            $stmt->bind_param("ssss", $email, $booking_date, $booking_time, $venue_id);

            if ($stmt->execute()) {
                $_SESSION['booking_successful'] = true;
                header("Location: payment-ground-success.php?email=$email&venue_id=$venue_id&date=$booking_date&time=$booking_time");
                exit();
            } else {
                // Database error
                $_SESSION['booking_error'] = 'Database error: ' . $stmt->error;
                header('Location: payment-failed.php');
                exit();
            }

            $stmt->close();
        }

        $checkBooking->close();
        $checkUser->close();
    } else {
        // Payment failure selected
        header('Location: payment-failed.php');
        exit();
    }
}

// Check if we have payment method from previous page
$payment_method = isset($_GET['method']) ? $_GET['method'] : 'unknown';

// For security, ensure all required session variables exist
$required_vars = ['user_email', 'booking_date', 'booking_time', 'venue_id', 'venue_name', 'price'];
foreach ($required_vars as $var) {
    if (!isset($_SESSION[$var])) {
        header("Location: turfbooknow.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Payment Status</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background-color: #0a2e1a; color: white; overflow-x: hidden; padding: 0 20px; margin: 0; font-family: 'Montserrat', 'Arial', sans-serif; box-sizing: border-box;">
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; max-width: 500px; margin: 50px auto; padding: 30px; background-color: rgba(0, 100, 50, 0.3); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1);">
        <h2 style="margin-bottom: 30px; font-size: 28px; color: #b9ff00; text-align: center;">Complete Payment</h2>
        
        <div style="width: 100%; background-color: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 15px; margin-bottom: 30px; border: 1px solid rgba(255, 255, 255, 0.1);">
            <p style="font-size: 18px; margin-bottom: 10px;"><strong><?php echo htmlspecialchars($_SESSION['venue_name']); ?></strong></p>
            <p style="font-size: 16px; margin-bottom: 10px; opacity: 0.8;"><?php echo htmlspecialchars($_SESSION['booking_date'] . ' | ' . $_SESSION['booking_time']); ?></p>
            <p style="font-size: 24px; margin-bottom: 10px;"><strong>₹<?php echo htmlspecialchars($_SESSION['price']); ?></strong></p>
            <span style="display: inline-block; background-color: #b9ff00; color: #0a2e1a; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: 600;"><?php echo ucfirst(htmlspecialchars($payment_method)); ?></span>
        </div>
        
        <p style="text-align: center; margin-bottom: 20px; font-size: 16px;">For demonstration purposes, please select the payment outcome:</p>
        
        <div style="display: flex; gap: 20px; width: 100%; justify-content: center;">
            <form method="POST" action="" style="width: 45%;">
                <input type="hidden" name="payment_status" value="success">
                <button type="submit" style="width: 100%; padding: 12px; background-color: #4CAF50; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Success
                </button>
            </form>
            
            <button id="failureButton" style="width: 45%; padding: 12px; background-color: #f44336; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                <i class="fas fa-times-circle" style="margin-right: 8px;"></i> Failure
            </button>
        </div>
    </div>
    
    <script>
        // Handle failure button click
        document.getElementById('failureButton').addEventListener('click', function() {
            window.location.href = 'payment-failed.php';
        });
    </script>
</body>
</html>