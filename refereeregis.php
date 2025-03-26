<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_email'])) {
    // Redirect to login if user is not logged in
    echo "<script>alert('Please login to register as a referee.'); window.location.href='login.php';</script>";
    exit();
}

$user_email = $_SESSION['user_email'];

// Fetch user details to pre-populate some fields
$user_query = "SELECT user_name, user_ph FROM user WHERE email = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("s", $user_email);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['registerReferee'])) {
        
        $ref_id = uniqid('ref_'); // Generate a unique ID
        $ref_location = $_POST['refereeLocation'];
        $ref_pic = $_POST['refereeImage'];
        $charges = $_POST['refereeCharges'];
        $yrs_exp = $_POST['refereeExperience'];
        
        // Use user data from user table
        $ref_name = $user_data['user_name'];
        $ref_ph = $user_data['user_ph'];
        
        $sql = "INSERT INTO referee (ref_id, ref_location, ref_pic, charges, yrs_exp, referee_email) 
        VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdss", $ref_id, $ref_location, $ref_pic, $charges, $yrs_exp, $user_email);
        
        if ($stmt->execute()) {
            // Update user type to referee
            $update_user = "UPDATE user SET user_type = 'referee' WHERE email = ?";
            $update_stmt = $conn->prepare($update_user);
            $update_stmt->bind_param("s", $user_email);
            $update_stmt->execute();
            
            // Fetch the updated user type from the database
            $type_query = "SELECT user_type FROM user WHERE email = ?";
            $type_stmt = $conn->prepare($type_query);
            $type_stmt->bind_param("s", $user_email);
            $type_stmt->execute();
            $type_result = $type_stmt->get_result();
            $type_data = $type_result->fetch_assoc();

            // Update the session variable with the new user type
            $_SESSION['user_type'] = $type_data['user_type'];
            
            echo "<script>alert('Referee Registration Successful!'); window.location.href='refereedashboard.php';</script>";
        } else {
            echo "<script>alert('Error: Unable to register as referee. " . $conn->error . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Referee Registration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\ground-academy-register.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
<?php include 'header.php'; ?>

    <div class="container">
        <div class="registration-container">
            <h2 style="color: white; font-size: 28px; margin-bottom: 30px; text-align: center;">Referee Registration</h2>
            
            <div class="form-container">
                <form id="refereeForm" class="registration-form active" method="POST">
                    <div class="form-section">
                        <h3>Location & Image</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="refereeLocation">Location</label>
                                <input type="text" id="refereeLocation" name="refereeLocation" required placeholder="Enter your location">
                            </div>
                            <div class="form-group">
                                <label for="refereeImage">Profile Picture URL</label>
                                <input type="url" id="refereeImage" name="refereeImage" required placeholder="Enter profile image URL">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Professional Details</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="refereeCharges">Hourly Charges (₹)</label>
                                <input type="number" id="refereeCharges" name="refereeCharges" required placeholder="Enter your hourly rate">
                            </div>
                            <div class="form-group">
                                <label for="refereeExperience">Years of Experience</label>
                                <input type="number" id="refereeExperience" name="refereeExperience" min="0" required placeholder="Enter years of experience">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="registerReferee" value="1">
                    <button type="submit" class="submit-btn">Register as Referee</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form initialization code if needed
        });
    </script>
</body>
</html>