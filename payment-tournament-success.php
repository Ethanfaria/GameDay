<?php
session_start();
include 'db.php';

// Check if registration was successful
if (!isset($_SESSION['registration_successful']) && !isset($_GET['registration_success'])) {
    header("Location: tournaments.php");
    exit();
}

// Clear the session flag
unset($_SESSION['registration_successful']);

// Get tournament ID from session or GET parameters
$tr_id = isset($_SESSION['tournament_id']) ? $_SESSION['tournament_id'] : 
         (isset($_GET['tr_id']) ? $_GET['tr_id'] : '');

// Get user email from session
$email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

// Fetch tournament details
$sql = "SELECT t.*, v.venue_nm, v.location 
        FROM tournaments t 
        LEFT JOIN venue v ON t.venue_id = v.venue_id
        WHERE t.tr_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $tr_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tournament = $result->fetch_assoc();
    
    // Retrieve tournament details
    $tournament_name = isset($_SESSION['tournament_name']) ? $_SESSION['tournament_name'] : $tournament['tr_name'];
    $tournament_venue = isset($_SESSION['tournament_venue']) ? $_SESSION['tournament_venue'] : $tournament['venue_nm'];
    $tournament_location = isset($_SESSION['tournament_location']) ? $_SESSION['tournament_location'] : $tournament['location'];
    $tournament_fee = isset($_SESSION['tournament_fee']) ? $_SESSION['tournament_fee'] : $tournament['entry_fee'];
    $tournament_date = isset($_SESSION['tournament_date']) ? $_SESSION['tournament_date'] : $tournament['start_date'];
    $tournament_end_date = isset($_SESSION['tournament_end_date']) ? $_SESSION['tournament_end_date'] : $tournament['end_date'];
    
    // Fetch registration details from database
    $registration_sql = "SELECT * FROM register WHERE email = ? AND tr_id = ?";
    $registration_stmt = $conn->prepare($registration_sql);
    $registration_stmt->bind_param("ss", $email, $tr_id);
    $registration_stmt->execute();
    $registration_result = $registration_stmt->get_result();
    
    if ($registration_result->num_rows > 0) {
        $registration = $registration_result->fetch_assoc();
        $registration_id = $registration['reg_id'];
        $team_name = $registration['team_nm'];
    } else {
        $registration_id = "REG" . rand(1000, 9999);
        $team_name = "Individual Registration";
    }
    
    // Use current date as registration date since it's not stored in the database
    $registration_date = date('Y-m-d');
} else {
    echo "<p>Tournament not found.</p>";
    exit();
}

// Clear tournament session data
unset($_SESSION['tournament_id']);
unset($_SESSION['tournament_name']);
unset($_SESSION['tournament_venue']);
unset($_SESSION['tournament_location']);
unset($_SESSION['tournament_fee']);
unset($_SESSION['tournament_date']);
unset($_SESSION['tournament_end_date']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Registration Confirmation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\payment.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    
    <div class="payment-container">
        <i class="fas fa-check-circle success-icon" style="font-size: 64px; color: var(--neon-green); display: block; text-align: center; margin-bottom: 20px;"></i>
        <h1 class="summary-title">Registration Successful!</h1>
        <p style="text-align: center; margin-bottom: 25px;">You have successfully registered for the tournament. Please check your details below.</p>
        
        <div class="order-summary">
            <div class="summary-row">
                <div class="summary-label">Tournament Name</div>
                <div class="summary-value"><?php echo htmlspecialchars($tournament_name); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Venue</div>
                <div class="summary-value"><?php echo htmlspecialchars($tournament_venue); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Location</div>
                <div class="summary-value"><?php echo htmlspecialchars($tournament_location); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Registration ID</div>
                <div class="summary-value"><?php echo htmlspecialchars($registration_id); ?></div>
            </div>
            <?php if (!empty($team_name)): ?>
            <div class="summary-row">
                <div class="summary-label">Team Name</div>
                <div class="summary-value"><?php echo htmlspecialchars($team_name); ?></div>
            </div>
            <?php endif; ?>
            <div class="summary-row">
                <div class="summary-label">Registration Date</div>
                <div class="summary-value"><?php echo date('d M Y', strtotime($registration_date)); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Tournament Date</div>
                <div class="summary-value"><?php echo date('d M Y', strtotime($tournament_date)); ?></div>
            </div>
            <?php if (isset($tournament_end_date) && !empty($tournament_end_date)): ?>
            <div class="summary-row">
                <div class="summary-label">End Date</div>
                <div class="summary-value"><?php echo date('d M Y', strtotime($tournament_end_date)); ?></div>
            </div>
            <?php endif; ?>
            <div class="total-row">
                <div class="total-label">Registration Fee</div>
                <div class="total-value">₹<?php echo htmlspecialchars(number_format($tournament_fee)); ?></div>
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