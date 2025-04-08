<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Check if booking_id is provided
if (!isset($_GET['booking_id'])) {
    $_SESSION['error'] = "No booking specified";
    header("Location: userdashboard.php");
    exit();
}

$booking_id = $_GET['booking_id'];
$user_email = $_SESSION['user_email'];

// Verify that this booking belongs to the user
$check_sql = "SELECT * FROM book WHERE booking_id = ? AND email = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("is", $booking_id, $user_email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "You don't have permission to cancel this booking";
    header("Location: userdashboard.php");
    exit();
}

// Update the booking to mark it as canceled but keep the referee_email
$update_sql = "UPDATE book SET status = 'canceled' WHERE booking_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("i", $booking_id);

if ($update_stmt->execute()) {
    $_SESSION['success'] = "Referee request cancelled successfully";
} else {
    $_SESSION['error'] = "Failed to cancel referee request: " . $conn->error;
}

header("Location: userdashboard.php");
exit();
?>