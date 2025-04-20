<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "<script>alert('Please log in to cancel your referee request.');</script>";
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

// Check if booking_id is provided
if (!isset($_GET['booking_id'])) {
    echo "<script>alert('Booking ID is missing.');</script>";
    echo "<script>window.location.href = 'userdashboard.php';</script>";
    exit();
}

$booking_id = $_GET['booking_id'];
$user_email = $_SESSION['user_email'];

// Verify that the booking belongs to the user
$verify_sql = "SELECT * FROM book WHERE booking_id = ? AND email = ?";
$verify_stmt = $conn->prepare($verify_sql);
$verify_stmt->bind_param("is", $booking_id, $user_email);
$verify_stmt->execute();
$result = $verify_stmt->get_result();

if ($result->num_rows > 0) {
    // Update the booking status to 'cancelled'
    $update_sql = "UPDATE book SET status = 'cancelled' WHERE booking_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $booking_id);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Referee request has been cancelled successfully.');</script>";
    } else {
        echo "<script>alert('Failed to cancel referee request: " . $conn->error . "');</script>";
    }
} else {
    echo "<script>alert('Invalid booking or you don\\'t have permission to cancel this booking');</script>";
}

// Redirect back to dashboard
echo "<script>window.location.href = 'userdashboard.php';</script>";
exit();
?>