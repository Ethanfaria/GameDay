<?php
session_start();
include 'db.php';

// Process payment if success button was clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_status'])) {
    if ($_POST['payment_status'] === 'success') {
        // Get enrollment details from session
        $email = $_SESSION['user_email'];
        $ac_id = $_SESSION['academy_id'];
        $enrollment_duration = $_SESSION['academy_duration'];
        $enrollment_date = date('Y-m-d');
        $en_id = uniqid('en_');

        // Validate session data exists
        if (!isset($email, $ac_id)) {
            $_SESSION['enrollment_error'] = "Missing required enrollment details.";
            header("Location: payment-failed.php");
            exit();
        }

        // Check if the user exists
        $checkUser = $conn->prepare("SELECT email FROM user WHERE email = ?");
        $checkUser->bind_param("s", $email);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows === 0) {
            $_SESSION['enrollment_error'] = "The user email does not exist in the database.";
            header("Location: payment-failed.php");
            exit();
        }

        // Check if enrollment already exists
        $checkEnrollment = $conn->prepare("SELECT * FROM enroll WHERE email = ? AND ac_id = ?");
        $checkEnrollment->bind_param("ss", $email, $ac_id);
        $checkEnrollment->execute();
        $enrollmentResult = $checkEnrollment->get_result();
        
        if ($enrollmentResult->num_rows > 0) {
            // Already enrolled, redirect with error
            $_SESSION['enrollment_error'] = 'You are already enrolled in this academy.';
            header('Location: payment-failed.php');
            exit();
        } else {
            // Insert enrollment into the database
            $sql = "INSERT INTO enroll (en_id, ac_id, en_dur, en_date, email) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                $_SESSION['enrollment_error'] = "Error preparing statement: " . $conn->error;
                header("Location: payment-failed.php");
                exit();
            }

            $stmt->bind_param("ssiss", $en_id, $ac_id, $enrollment_duration, $enrollment_date, $email);

            if ($stmt->execute()) {
                $_SESSION['enrollment_successful'] = true;
                header("Location: payment-academy-success.php?ac_id=$ac_id&enrollment_success=true&en_id=$en_id");
                exit();
            } else {
                // Database error
                $_SESSION['enrollment_error'] = 'Database error: ' . $stmt->error;
                header('Location: payment-failed.php');
                exit();
            }

            $stmt->close();
        }

        $checkEnrollment->close();
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
$required_vars = ['user_email', 'academy_id', 'academy_name', 'academy_charges'];
foreach ($required_vars as $var) {
    if (!isset($_SESSION[$var])) {
        header("Location: academies.php");
        exit();
    }
}

// Calculate total charges
$enrollment_duration = $_SESSION['academy_duration'];
$total_charges = $_SESSION['academy_charges'] * $enrollment_duration;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Academy Payment Status</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\payment.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    <div class="payment-result-container">
        <h2>Complete Payment</h2>
        
        <div class="payment-info">
            <p><strong><?php echo htmlspecialchars($_SESSION['academy_name']); ?></strong></p>
            <p>Enrollment Duration: <?php echo htmlspecialchars($enrollment_duration); ?> months</p>
            <p><strong>₹<?php echo htmlspecialchars($total_charges); ?></strong></p>
            <span class="payment-method-label"><?php echo ucfirst(htmlspecialchars($payment_method)); ?></span>
        </div>
        
        <p>For demonstration purposes, please select the payment outcome:</p>
        
        <div class="payment-options">
            <form method="POST" action="">
                <input type="hidden" name="payment_status" value="success">
                <button type="submit" class="result-button success-button">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Success
                </button>
            </form>
            
            <button class="result-button failure-button" id="failureButton">
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