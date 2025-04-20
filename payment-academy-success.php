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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/payment.css">
    <link rel="stylesheet" href="CSS/main.css">
</head>
<body>
    <div class="payment-container">
        <i class="fas fa-check-circle success-icon" style="font-size: 64px; color: var(--neon-green); display: block; text-align: center; margin-bottom: 20px;"></i>
        <h1 class="summary-title">Enrollment Successful!</h1>
        
        <div class="order-summary">
            <div class="summary-row">
                <div class="summary-label">Email ID</div>
                <div class="summary-value"><?php echo htmlspecialchars($email); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Academy Name</div>
                <div class="summary-value"><?php echo htmlspecialchars($academy_name); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Location</div>
                <div class="summary-value"><?php echo htmlspecialchars($academy_location); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Enrollment Date</div>
                <div class="summary-value"><?php echo date('d M Y', strtotime($enrollment_date)); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Duration</div>
                <div class="summary-value"><?php echo htmlspecialchars($enrollment_duration); ?> months</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">End Date</div>
                <div class="summary-value"><?php echo date('d M Y', strtotime($end_date)); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Monthly Fee</div>
                <div class="summary-value">₹<?php echo htmlspecialchars(number_format($academy_charges)); ?></div>
            </div>
            <div class="total-row">
                <div class="total-label">Total Amount Paid</div>
                <div class="total-value">₹<?php echo htmlspecialchars(number_format($academy_charges * $enrollment_duration)); ?></div>
            </div>
        </div>
        
        <div class="action-buttons">
                <a href="userdashboard.php" class="action-button primary-button">
                    <i class="fas fa-home"></i> Go to Dashboard
                </a>
                <a href="index.php" class="action-button secondary-button">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
        </div>
    </div>
</body>
</html>