<?php
// Start session
session_start();
include 'db.php';

// Check if referee is logged in
if (!isset($_SESSION['user_email']) || $_SESSION['user_type'] !== "referee") {
    header("Location: login.php");
    exit();
}

$ref_email = $_SESSION['user_email'];
$success_message = "";
$error_message = "";

// Fetch current referee data - updated to match your database structure
function fetchRefereeProfile($conn, $ref_email) {
    $sql = "SELECT r.*, u.user_name, u.user_ph 
            FROM referee r
            JOIN user u ON r.referee_email = u.email 
            WHERE r.referee_email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ref_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $referee = $result->fetch_assoc();
    $stmt->close();
    
    return $referee;
}

$referee = fetchRefereeProfile($conn, $ref_email);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $user_name = $_POST['user_name'];
    $ref_location = $_POST['ref_location'];
    $yrs_exp = $_POST['yrs_exp'];
    $charges = $_POST['charges'];
    $profile_pic_text = $_POST['profile_pic_text'];
    $user_ph = $_POST['user_phone'] ?? '';
    
    // Password change variables
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update user table with name and phone
        $update_user_sql = "UPDATE user SET user_name = ?, phone = ? WHERE email = ?";
        $update_user_stmt = $conn->prepare($update_user_sql);
        $update_user_stmt->bind_param("sss", $user_name, $user_ph, $ref_email);
        $update_user_stmt->execute();
        $update_user_stmt->close();
        
        // Update referee table without phone number
        $update_ref_sql = "UPDATE referee SET ref_location = ?, yrs_exp = ?, charges = ?, ref_pic = ? WHERE referee_email = ?";
        $update_ref_stmt = $conn->prepare($update_ref_sql);
        $update_ref_stmt->bind_param("sidss", $ref_location, $yrs_exp, $charges, $profile_pic_text, $ref_email);
        $update_ref_stmt->execute();
        $update_ref_stmt->close();
        
        // Handle password change if requested
        if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
            // Verify passwords match
            if ($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match.");
            }
            
            // Verify current password
            $check_pwd_sql = "SELECT password FROM user WHERE email = ?";
            $check_pwd_stmt = $conn->prepare($check_pwd_sql);
            $check_pwd_stmt->bind_param("s", $ref_email);
            $check_pwd_stmt->execute();
            $check_pwd_result = $check_pwd_stmt->get_result();
            $user_data = $check_pwd_result->fetch_assoc();
            $check_pwd_stmt->close();
            
            if (!password_verify($current_password, $user_data['password'])) {
                throw new Exception("Current password is incorrect.");
            }
            
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_pwd_sql = "UPDATE user SET password = ? WHERE email = ?";
            $update_pwd_stmt = $conn->prepare($update_pwd_sql);
            $update_pwd_stmt->bind_param("ss", $hashed_password, $ref_email);
            $update_pwd_stmt->execute();
            $update_pwd_stmt->close();
        }
        
        // Commit transaction
        $conn->commit();
        $success_message = "Profile updated successfully!";
        
        // Refresh referee data
        $referee = fetchRefereeProfile($conn, $ref_email);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Edit Referee Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <link rel="stylesheet" href="CSS/refereedashboard.css">
    <link rel="stylesheet" href="CSS/main.css">
    <style>
        .edit-profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
            font-family: 'Montserrat', sans-serif;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--neon-green);
        }
        
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--neon-green);
            color: var(--dark-green);
        }
        
        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: rgba(0, 200, 0, 0.2);
            color: #00ff00;
            border: 1px solid rgba(0, 200, 0, 0.4);
        }
        
        .alert-danger {
            background-color: rgba(255, 0, 0, 0.2);
            color: #ff6666;
            border: 1px solid rgba(255, 0, 0, 0.4);
        }
        
        .current-image {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .current-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="welcome">
            <h1>Edit Referee Profile</h1>
            <p>Update your personal information and profile settings.</p>
        </div>
        
        <div class="edit-profile-container">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label" for="user_name">Full Name</label>
                    <input type="text" id="user_name" name="user_name" class="form-control" value="<?php echo htmlspecialchars($referee['user_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="ref_location">Location</label>
                    <input type="text" id="ref_location" name="ref_location" class="form-control" value="<?php echo htmlspecialchars($referee['ref_location']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="yrs_exp">Years of Experience</label>
                    <input type="number" id="yrs_exp" name="yrs_exp" class="form-control" value="<?php echo htmlspecialchars($referee['yrs_exp']); ?>" min="0" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="charges">Hourly Rate (₹)</label>
                    <input type="number" id="charges" name="charges" class="form-control" value="<?php echo htmlspecialchars($referee['charges']); ?>" min="0" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($referee['user_ph'] ?? ''); ?>" placeholder="Enter your phone number">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($ref_email); ?>" readonly>
                    <small style="color: rgba(255,255,255,0.6); display: block; margin-top: 5px;">Email cannot be changed as it is used for login.</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Change Password</label>
                    <div style="background-color: rgba(0,0,0,0.2); border-radius: 8px; padding: 15px; border: 1px solid rgba(255,255,255,0.1);">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="form-label" for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter your current password">
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="form-label" for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="profile_pic_text">Profile Picture</label>
                    <input type="text" id="profile_pic_text" name="profile_pic_text" class="form-control" 
                           value="<?php echo htmlspecialchars($referee['ref_pic']); ?>" 
                           placeholder="Enter image URL or text identifier">
                    
                    <?php if (!empty($referee['ref_pic'])): ?>
                    <div class="current-image">
                        <div style="width: 100px; height: 100px; background-color: #1a461f; border-radius: 8px; display: flex; justify-content: center; align-items: center; color: #c4ff3c; font-size: 14px; font-weight: bold; overflow: hidden; text-align: center; padding: 5px;">
                            <?php echo htmlspecialchars($referee['ref_pic']); ?>
                        </div>
                        <span>Current profile picture</span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="refereedashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>