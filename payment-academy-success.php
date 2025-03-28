<?php
session_start();
include 'db.php';

// Check if enrollment was successful
if (!isset($_SESSION['enrollment_successful']) && !isset($_GET['enrollment_success'])) {
    header("Location: academy.php");
    exit();
}

// Clear the session flag
unset($_SESSION['enrollment_successful']);

// Get academy ID from session or GET parameters
$ac_id = isset($_SESSION['academy_id']) ? $_SESSION['academy_id'] : 
         (isset($_GET['ac_id']) ? $_GET['ac_id'] : '');

// Get user email from session
$email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

// Fetch academy details
$sql = "SELECT * FROM academys WHERE ac_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ac_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $academy = $result->fetch_assoc();
    
    // Retrieve enrollment details
    $academy_name = isset($_SESSION['academy_name']) ? $_SESSION['academy_name'] : $academy['aca_nm'];
    $academy_location = isset($_SESSION['academy_location']) ? $_SESSION['academy_location'] : $academy['ac_location'];
    $academy_charges = isset($_SESSION['academy_charges']) ? $_SESSION['academy_charges'] : $academy['ac_charges'];
    
    // Fetch enrollment details from database
    $enrollment_sql = "SELECT * FROM enroll WHERE email = ? AND ac_id = ? ORDER BY en_date DESC LIMIT 1";
    $enrollment_stmt = $conn->prepare($enrollment_sql);
    $enrollment_stmt->bind_param("ss", $email, $ac_id);
    $enrollment_stmt->execute();
    $enrollment_result = $enrollment_stmt->get_result();
    
    if ($enrollment_result->num_rows > 0) {
        $enrollment = $enrollment_result->fetch_assoc();
        $enrollment_date = $enrollment['en_date'];
        $enrollment_duration = $enrollment['en_dur'];
    } else {
        $enrollment_date = date('Y-m-d');
        $enrollment_duration = 3; // Default value
    }
    
    // Calculate end date
    $end_date = date('Y-m-d', strtotime($enrollment_date . ' + ' . $enrollment_duration . ' months'));
} else {
    echo "<p>Academy not found.</p>";
    exit();
}

// Clear academy session data
unset($_SESSION['academy_id']);
unset($_SESSION['academy_name']);
unset($_SESSION['academy_location']);
unset($_SESSION['academy_charges']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Enrollment Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\payment-ground.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="payment-container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1>Enrollment Successful!</h1>
        <p>You have successfully enrolled in the academy. Please check your details below.</p>
        
        <div class="enrollment-details">
            <p>
                <span>Academy Name</span>
                <span><?php echo htmlspecialchars($academy_name); ?></span>
            </p>
            <p>
                <span>Location</span>
                <span><?php echo htmlspecialchars($academy_location); ?></span>
            </p>
            <p>
                <span>Enrollment Date</span>
                <span><?php echo date('d M Y', strtotime($enrollment_date)); ?></span>
            </p>
            <p>
                <span>Duration</span>
                <span><?php echo htmlspecialchars($enrollment_duration); ?> months</span>
            </p>
            <p>
                <span>End Date</span>
                <span><?php echo date('d M Y', strtotime($end_date)); ?></span>
            </p>
            <p>
                <span>Monthly Fee</span>
                <span>₹<?php echo htmlspecialchars(number_format($academy_charges)); ?></span>
            </p>
            <p>
                <span>Total Amount Paid</span>
                <span>₹<?php echo htmlspecialchars(number_format($academy_charges * $enrollment_duration)); ?></span>
            </p>
        </div>
        
        <p>A confirmation email has been sent to your registered email address.</p>
        
        <div class="action-buttons">
            <a href="index.php" class="action-button">Go to Home</a>
            <a href="profile.php" class="action-button secondary-button">View Profile</a>
        </div>
    </div>
</body>
</html>