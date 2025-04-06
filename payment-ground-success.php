<?php
include 'db.php';

$email = $_GET['user_email'] ?? 'Unknown';
$venue_id = $_GET['venue_id'] ?? 'Unknown';
$booking_date = $_GET['date'] ?? 'Unknown';
$booking_time = $_GET['time'] ?? 'Unknown';

$venue_name = "Unknown Venue";
if ($venue_id !== 'Unknown') {
    $stmt = $conn->prepare("SELECT venue_nm FROM venue WHERE venue_id = ?");
    $stmt->bind_param("s", $venue_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $venue_name = $row['venue_nm'];
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Booking Confirmed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .success-container {
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .success-icon {
            font-size: 80px;
            color: var(--neon-green);
            margin-bottom: 20px;
        }

        h1 {
            color: var(--neon-green);
            margin-bottom: 20px;
        }

        .booking-details {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }

        .booking-details p {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
        }

        .buttons {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .button {
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .primary-button {
            background-color: var(--neon-green);
            color: var(--dark-green);
        }

        .secondary-button {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .button:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1>Booking Confirmed!</h1>
        <p>Your booking has been successfully confirmed. A confirmation email has been sent to your registered email address.</p>
        
        <div class="booking-details">
            <p>
                <span>Email ID:</span>
                <span id="booking-id"><?php echo htmlspecialchars($email); ?></span>
            </p>
            <p>
                <span>Venue:</span>
                <span id="venue-name"><?php echo htmlspecialchars($venue_name); ?></span>
            </p>
            <p>
                <span>Booking Date:</span>
                <span id="booking-datetime"><?php echo htmlspecialchars($booking_date); ?></span>
            </p>
            <p>
                <span>Booking Time:</span>
                <span id="booking-time"><?php echo htmlspecialchars($booking_time); ?></span>
            </p>
        </div>

        <div class="buttons">
            <a href="index.php" class="button primary-button">Back to Home</a>
            <a href="#" class="button secondary-button" onclick="window.print()">Download Receipt</a>
        </div>
    </div>
</body>
</html>