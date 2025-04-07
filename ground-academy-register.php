<?php
include 'db.php'; 
session_start(); 

if (!isset($_SESSION['user_email'])) {
    // Redirect to login if user is not logged in
    echo "<script>alert('Please login to register a facility.'); window.location.href='login.php';</script>";
    exit();
}

$user_email = $_SESSION['user_email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['registerAcademy'])) { // If Academy form is submitted
        // Define validation errors array
        $errors = array();
        
        // Validate required fields
        $aca_nm = trim($_POST['academyName']);
        if (empty($aca_nm)) {
            array_push($errors, "Academy Name is required");
        } elseif (strlen($aca_nm) < 3) {
            array_push($errors, "Academy Name must be at least 3 characters long");
        } elseif (!preg_match("/^[a-zA-Z0-9\s\-.,&']+$/", $aca_nm)) {
            array_push($errors, "Academy Name contains invalid characters");
        }
        
        $level = $_POST['academyLevel'];
        if (empty($level)) {
            array_push($errors, "Academy Level is required");
        }
        
        $age_group = $_POST['academyAgeGroup'];
        if (empty($age_group)) {
            array_push($errors, "Age Group is required");
        }
        
        $venue_id = $_POST['venueId'];
        if (empty($venue_id)) {
            array_push($errors, "Venue selection is required");
        }
        
        $ac_charges = $_POST['academyCharges'];
        if (empty($ac_charges) || !is_numeric($ac_charges) || $ac_charges <= 50) {
            array_push($errors, "Monthly Fee must be a number greater than 50");
        }
        
        $duration = $_POST['academyDuration'];
        if (empty($duration) || !is_numeric($duration) || $duration < 1) {
            array_push($errors, "Duration must be at least 1 month");
        }
        
        $description = trim($_POST['academyDescription']);
        if (empty($description)) {
            array_push($errors, "Academy Description is required");
        } elseif (strlen($description) < 20) {
            array_push($errors, "Academy Description must be at least 20 characters long");
        }
        
        $admy_img = trim($_POST['academyImage']);
        if (!empty($admy_img)) {
            $url_pattern = "/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/";
            if (!preg_match($url_pattern, $admy_img)) {
                array_push($errors, "Academy Image URL is not valid");
            }
        }
        
        // Validate days
        $selectedDays = isset($_POST['days']) ? $_POST['days'] : [];
        if (empty($selectedDays)) {
            array_push($errors, "At least one day must be selected");
        }
        
        // Validate time inputs
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        
        if (empty($startTime)) {
            array_push($errors, "Start Time is required");
        }
        
        if (empty($endTime)) {
            array_push($errors, "End Time is required");
        }
        
        if (!empty($startTime) && !empty($endTime) && $startTime >= $endTime) {
            array_push($errors, "End Time must be after Start Time");
        }
        
        // If there are validation errors, do not proceed with insertion
        if (count($errors) > 0) {
            $errorMessage = "";
            foreach ($errors as $error) {
                $errorMessage .= $error . "\\n";
            }
            echo "<script>alert('Please correct the following errors:\\n" . $errorMessage . "');</script>";
        } else {
            // Proceed with academy registration
            $ac_id = uniqid('ac_'); // Generate a unique ID
            $feature1 = $_POST['feature1'];
            $feature2 = $_POST['feature2'];
            $feature3 = $_POST['feature3'];
            
            // Process the time inputs
            $startTime = date("h A", strtotime($_POST['startTime'] . ':00'));
            $endTime = date("h A", strtotime($_POST['endTime'] . ':00'));
            $timings = $startTime . ' - ' . $endTime;
            
            // Process the days
            $days = !empty($selectedDays) ? implode(', ', $selectedDays) : 'Not specified';
            
            // Insert into database
            $sql = "INSERT INTO academys (ac_id, aca_nm, venue_id, ac_charges, level, age_group, description, feature1, feature2, feature3, admy_img, timings, days, duration, owner_email) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdsssssssssis", $ac_id, $aca_nm, $venue_id, $ac_charges, $level, $age_group, $description, $feature1, $feature2, $feature3, $admy_img, $timings, $days, $duration, $user_email);

            if ($stmt->execute()) {
                // Update user type after successful academy registration
                $update_user = "UPDATE user SET user_type = 'owner' WHERE email = ?";
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
                
                echo "<script>window.location.href='admyadmindashboard.php';</script>";
            } else {
                echo "<script>alert('Error: Unable to register academy. " . $conn->error . "');</script>";
            }
        }
    }

    if (isset($_POST['registerTurf'])) {
        // Define validation errors array
        $errors = array();
        
        // Validate required fields
        $venue_nm = trim($_POST['turfName']);
        if (empty($venue_nm)) {
            array_push($errors, "Turf Name is required");
        } elseif (strlen($venue_nm) < 3) {
            array_push($errors, "Turf Name must be at least 3 characters long");
        } elseif (!preg_match("/^[a-zA-Z0-9\s\-.,&']+$/", $venue_nm)) {
            array_push($errors, "Turf Name contains invalid characters");
        }
        
        $location = trim($_POST['turfLocation']);
        if (empty($location)) {
            array_push($errors, "Turf Location is required");
        } elseif (strlen($location) < 5) {
            array_push($errors, "Turf Location must be at least 5 characters long");
        }
        
        $length = $_POST['turfLength'];
        if (empty($length) || !is_numeric($length) || $length <= 0) {
            array_push($errors, "Turf Length must be a positive number");
        }
        
        $breadth = $_POST['turfBreadth'];
        if (empty($breadth) || !is_numeric($breadth) || $breadth <= 0) {
            array_push($errors, "Turf Breadth must be a positive number");
        }
        
        $price = $_POST['hourlyRate'];
        if (empty($price) || !is_numeric($price) || $price <= 50) {
            array_push($errors, "Hourly Rate must be a number greater than 50");
        }
        
        $turf_img = trim($_POST['turfImage']);
        if (empty($turf_img)) {
            array_push($errors, "Turf Image URL is required");
        } else {
            $url_pattern = "/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/";
            if (!preg_match($url_pattern, $turf_img)) {
                array_push($errors, "Turf Image URL is not valid");
            }
        }
        
        // If there are validation errors, do not proceed with insertion
        if (count($errors) > 0) {
            $errorMessage = "";
            foreach ($errors as $error) {
                $errorMessage .= $error . "\\n";
            }
            echo "<script>alert('Please correct the following errors:\\n" . $errorMessage . "');</script>";
        } else {
            // Proceed with turf registration
            $venue_id = uniqid('ven_'); // Generate a unique ID
            $size = $length . ' x ' . $breadth;
            $amenity1 = $_POST['amenity1'];
            $amenity2 = $_POST['amenity2'];
            $amenity3 = $_POST['amenity3'];
            
            $sql = "INSERT INTO venue (venue_id, venue_nm, location, size, price, turf_img, availability, amenity1, amenity2, amenity3, owner_email) 
                    VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdssssss", $venue_id, $venue_nm, $location, $size, $price, $turf_img, $amenity1, $amenity2, $amenity3, $user_email);
            
            if ($stmt->execute()) {
                // Update user type after successful turf registration
                $update_user = "UPDATE user SET user_type = 'owner' WHERE email = ?";
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
                
                echo "<script>window.location.href='admyadmindashboard.php';</script>";
            } else {
                echo "<script>alert('Error: Unable to register turf. " . $conn->error . "');</script>";
            }
        }
    }
}

// Fetch available venues for academy dropdown
$venues_query = "SELECT venue_id, venue_nm FROM venue";
$venues_result = $conn->query($venues_query);
$venues = [];
if ($venues_result->num_rows > 0) {
    while($row = $venues_result->fetch_assoc()) {
        $venues[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Register Your Facility</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\ground-academy-register.css">
    <link rel="stylesheet" href="CSS\main.css">
    <style>
        .requirements {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
            margin-bottom: 10px;
            display: none;
        }
        
        .requirements.show {
            display: block;
        }
        
        .requirements ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .requirements li {
            margin-bottom: 2px;
        }
        
        .valid {
            color: green;
        }
        
        .invalid {
            color: red;
        }
        
        .error-container {
            background-color: #ffe0e0;
            border: 1px solid #ff9999;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }
        
        .error {
            color: #d8000c;
            margin-bottom: 5px;
        }
        
        .form-group input.error-field, 
        .form-group select.error-field,
        .form-group textarea.error-field {
            border-color: #d8000c;
        }
        
        .form-group input.valid-field, 
        .form-group select.valid-field,
        .form-group textarea.valid-field {
            border-color: #4CAF50;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <div class="container">
        <div class="registration-container">
            <!-- Toggle Buttons -->
            <div class="toggle-container">
                <button class="toggle-btn active" onclick="toggleForm('turf')">Register Turf</button>
                <button class="toggle-btn" onclick="toggleForm('academy')">Register Academy</button>
            </div>

            <div class="form-container">
                <!-- Turf Registration Form -->
                <form id="turfForm" class="registration-form active" method="POST">
                    <h2>Register Your Turf</h2>
                    
                    <div class="form-section">
                        <h3>Basic Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="turfName">Turf Name</label>
                                <input type="text" id="turfName" name="turfName" required placeholder="Enter turf name">
                                <div id="turfNameRequirements" class="requirements">
                                    <ul>
                                        <li id="turfNameLength">At least 3 characters long</li>
                                        <li id="turfNameChars">Only letters, numbers, spaces, and basic punctuation</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="turfLocation">Location</label>
                                <input type="text" id="turfLocation" name="turfLocation" required placeholder="Enter turf location">
                                <div id="turfLocationRequirements" class="requirements">
                                    <ul>
                                        <li id="turfLocationLength">At least 5 characters long</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3>Ground Dimensions</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="turfLength">Length (m)</label>
                                <input type="number" id="turfLength" name="turfLength" required placeholder="Enter length">
                                <div id="turfLengthRequirements" class="requirements">
                                    <ul>
                                        <li id="turfLengthValid">Must be a positive number (greater than 0)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="turfBreadth">Breadth (m)</label>
                                <input type="number" id="turfBreadth" name="turfBreadth" required placeholder="Enter breadth">
                                <div id="turfBreadthRequirements" class="requirements">
                                    <ul>
                                        <li id="turfBreadthValid">Must be a positive number (greater than 0)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Pricing & Media</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="hourlyRate">Hourly Rate (₹)</label>
                                <input type="number" id="hourlyRate" name="hourlyRate" required placeholder="Enter hourly rate">
                                <div id="hourlyRateRequirements" class="requirements">
                                    <ul>
                                        <li id="hourlyRateValid">Must be a positive number (greater than 50)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="turfImage">Turf Image URL</label>
                                <input type="url" id="turfImage" name="turfImage" required placeholder="Enter image URL">
                                <div id="turfImageRequirements" class="requirements">
                                    <ul>
                                        <li id="turfImageUrl">Must be a valid URL format</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Amenities</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="amenity1">Amenity 1</label>
                                <input type="text" id="amenity1" name="amenity1" placeholder="E.g., Floodlights">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="amenity2">Amenity 2</label>
                                <input type="text" id="amenity2" name="amenity2" placeholder="E.g., Changing Rooms">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="amenity3">Amenity 3</label>
                                <input type="text" id="amenity3" name="amenity3" placeholder="E.g., Parking">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="registerTurf" value="1">
                    <button type="submit" class="submit-btn">Register Turf</button>
                </form>

                <!-- Academy Registration Form -->
                <form id="academyForm" class="registration-form" method="POST">
                    <h2>Register Your Academy</h2>
                    
                    <div class="form-section">
                        <h3>Basic Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="academyName">Academy Name</label>
                                <input type="text" id="academyName" name="academyName" required placeholder="Enter academy name">
                                <div id="academyNameRequirements" class="requirements">
                                    <ul>
                                        <li id="academyNameLength">At least 3 characters long</li>
                                        <li id="academyNameChars">Only letters, numbers, spaces, and basic punctuation</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Academy Details</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="academyLevel">Academy Level</label>
                                <select id="academyLevel" name="academyLevel" required>
                                    <option value="">Select Level</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="professional">Professional</option>
                                </select>
                                <div id="academyLevelRequirements" class="requirements">
                                    <ul>
                                        <li id="academyLevelSelected">Academy level must be selected</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="academyAgeGroup">Age Groups</label>
                                <select id="academyAgeGroup" name="academyAgeGroup" required>
                                    <option value="">Select Age Group</option>
                                    <option value="kids">Kids (5-12 years)</option>
                                    <option value="teens">Teens (13-17 years)</option>
                                    <option value="adults">Adults (18+ years)</option>
                                    <option value="all">All Age Groups</option>
                                </select>
                                <div id="academyAgeGroupRequirements" class="requirements">
                                    <ul>
                                        <li id="academyAgeGroupSelected">Age group must be selected</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Venue Selection</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="venueId">Select Venue</label>
                                <select id="venueId" name="venueId" required>
                                    <option value="">Select Venue</option>
                                    <?php foreach($venues as $venue): ?>
                                    <option value="<?php echo $venue['venue_id']; ?>"><?php echo $venue['venue_nm']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="venueIdRequirements" class="requirements">
                                    <ul>
                                        <li id="venueIdSelected">Venue must be selected</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Pricing</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="academyCharges">Monthly Fee (₹)</label>
                                <input type="number" id="academyCharges" name="academyCharges" required placeholder="Enter monthly fee">
                                <div id="academyChargesRequirements" class="requirements">
                                    <ul>
                                        <li id="academyChargesValid">Must be a positive number (greater than 50)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="academyDuration">Duration (Months)</label>
                                <input type="number" id="academyDuration" name="academyDuration" min="1" required placeholder="Enter program duration">
                                <div id="academyDurationRequirements" class="requirements">
                                    <ul>
                                        <li id="academyDurationValid">Must be a positive number (at least 1 month)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Additional Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="academyDescription">Description</label>
                                <textarea id="academyDescription" name="academyDescription" rows="4" placeholder="Enter academy description"></textarea>
                                <div id="academyDescriptionRequirements" class="requirements">
                                    <ul>
                                        <li id="academyDescriptionLength">At least 20 characters long</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3>Schedule & Media</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="academyImage">Academy Image URL</label>
                                <input type="url" id="academyImage" name="academyImage" placeholder="Enter image URL">
                                <div id="academyImageRequirements" class="requirements">
                                    <ul>
                                        <li id="academyImageUrl">Must be a valid URL format</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Days</label>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="days[]" value="Monday"> Monday</label>
                                    <label><input type="checkbox" name="days[]" value="Tuesday"> Tuesday</label>
                                    <label><input type="checkbox" name="days[]" value="Wednesday"> Wednesday</label>
                                    <label><input type="checkbox" name="days[]" value="Thursday"> Thursday</label>
                                    <label><input type="checkbox" name="days[]" value="Friday"> Friday</label>
                                    <label><input type="checkbox" name="days[]" value="Saturday"> Saturday</label>
                                    <label><input type="checkbox" name="days[]" value="Sunday"> Sunday</label>
                                </div>
                                <div id="daysRequirements" class="requirements">
                                    <ul>
                                        <li id="daysSelected">At least one day must be selected</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="startTime">Start Hour</label>
                                <select id="startTime" name="startTime" required>
                                    <option value="">Select Start Hour</option>
                                    <?php 
                                    for ($i = 6; $i <= 22; $i++) {
                                        $hour = $i % 12;
                                        $hour = $hour == 0 ? 12 : $hour;
                                        $ampm = $i >= 12 ? 'PM' : 'AM';
                                        echo "<option value=\"$i\">$hour $ampm</option>";
                                    }
                                    ?>
                                </select>
                                <div id="startTimeRequirements" class="requirements">
                                    <ul>
                                        <li id="startTimeSelected">Start hour must be selected</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="endTime">End Hour</label>
                                <select id="endTime" name="endTime" required>
                                    <option value="">Select End Hour</option>
                                    <?php 
                                    for ($i = 7; $i <= 23; $i++) {
                                        $hour = $i % 12;
                                        $hour = $hour == 0 ? 12 : $hour;
                                        $ampm = $i >= 12 ? 'PM' : 'AM';
                                        echo "<option value=\"$i\">$hour $ampm</option>";
                                    }
                                    ?>
                                </select>
                                <div id="endTimeRequirements" class="requirements">
                                    <ul>
                                        <li id="endTimeSelected">End hour must be selected</li>
                                        <li id="timeValid">End time must be after start time</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3>Features</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="feature1">Feature 1</label>
                                <input type="text" id="feature1" name="feature1" placeholder="E.g., Certified Coaches">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="feature2">Feature 2</label>
                                <input type="text" id="feature2" name="feature2" placeholder="E.g., Video Analysis">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="feature3">Feature 3</label>
                                <input type="text" id="feature3" name="feature3" placeholder="E.g., Fitness Training">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="registerAcademy" value="1">
                    <button type="submit" class="submit-btn">Register Academy</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleForm(type) {
            const turfForm = document.getElementById('turfForm');
            const academyForm = document.getElementById('academyForm');
            const buttons = document.querySelectorAll('.toggle-btn');
            
            buttons.forEach(btn => btn.classList.remove('active'));
            
            if (type === 'turf') {
                turfForm.classList.add('active');
                academyForm.classList.remove('active');
                buttons[0].classList.add('active');
            } else {
                academyForm.classList.add('active');
                turfForm.classList.remove('active');
                buttons[1].classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Add click event to checkbox labels to toggle selected class
            const checkboxLabels = document.querySelectorAll('.checkbox-group label');
            
            checkboxLabels.forEach(label => {
                label.addEventListener('click', function() {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    setTimeout(() => {
                        if (checkbox.checked) {
                            this.classList.add('selected');
                        } else {
                            this.classList.remove('selected');
                        }
                    }, 0);
                });
                
                // Initialize selected state based on default checkbox state
                const checkbox = label.querySelector('input[type="checkbox"]');
                if (checkbox.checked) {
                    label.classList.add('selected');
                }
            });
        });

        // Create function to add error messages at the top
        function displayErrorsAtTop(errors) {
            // Clear previous error messages
            const existingErrors = document.querySelectorAll('.error');
            existingErrors.forEach(error => error.remove());
            
            // Get registration container to insert errors at the top
            const registrationContainer = document.querySelector('.registration-container');
            
            // Create error container if it doesn't exist
            let errorContainer = document.querySelector('.error-container');
            if (!errorContainer) {
                errorContainer = document.createElement('div');
                errorContainer.className = 'error-container';
                registrationContainer.insertBefore(errorContainer, registrationContainer.firstChild);
            }
            
            // Create and insert error messages
            errors.forEach(errorMsg => {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error';
                errorDiv.textContent = errorMsg;
                errorContainer.appendChild(errorDiv);
            });
            
            // Scroll to top to show errors
            window.scrollTo(0, 0);
        }

        // Turf Form: Show validation requirements on input focus
document.getElementById('turfName').addEventListener('focus', function() {
    document.getElementById('turfNameRequirements').classList.add('show');
});

document.getElementById('turfLocation').addEventListener('focus', function() {
    document.getElementById('turfLocationRequirements').classList.add('show');
});

document.getElementById('turfLength').addEventListener('focus', function() {
    document.getElementById('turfLengthRequirements').classList.add('show');
});

document.getElementById('turfBreadth').addEventListener('focus', function() {
    document.getElementById('turfBreadthRequirements').classList.add('show');
});

document.getElementById('hourlyRate').addEventListener('focus', function() {
    document.getElementById('hourlyRateRequirements').classList.add('show');
});

document.getElementById('turfImage').addEventListener('focus', function() {
    document.getElementById('turfImageRequirements').classList.add('show');
});

// Academy Form: Show validation requirements on input focus
document.getElementById('academyName').addEventListener('focus', function() {
    document.getElementById('academyNameRequirements').classList.add('show');
});

document.getElementById('academyLevel').addEventListener('focus', function() {
    document.getElementById('academyLevelRequirements').classList.add('show');
});

document.getElementById('academyAgeGroup').addEventListener('focus', function() {
    document.getElementById('academyAgeGroupRequirements').classList.add('show');
});

document.getElementById('venueId').addEventListener('focus', function() {
    document.getElementById('venueIdRequirements').classList.add('show');
});

document.getElementById('academyCharges').addEventListener('focus', function() {
    document.getElementById('academyChargesRequirements').classList.add('show');
});

document.getElementById('academyDuration').addEventListener('focus', function() {
    document.getElementById('academyDurationRequirements').classList.add('show');
});

document.getElementById('academyDescription').addEventListener('focus', function() {
    document.getElementById('academyDescriptionRequirements').classList.add('show');
});

document.getElementById('academyImage').addEventListener('focus', function() {
    document.getElementById('academyImageRequirements').classList.add('show');
});

document.getElementById('startTime').addEventListener('focus', function() {
    document.getElementById('startTimeRequirements').classList.add('show');
});

document.getElementById('endTime').addEventListener('focus', function() {
    document.getElementById('endTimeRequirements').classList.add('show');
});

// Live validation feedback for Turf Form
document.getElementById('turfName').addEventListener('input', validateTurfName);
document.getElementById('turfLocation').addEventListener('input', validateTurfLocation);
document.getElementById('turfLength').addEventListener('input', validateTurfLength);
document.getElementById('turfBreadth').addEventListener('input', validateTurfBreadth);
document.getElementById('hourlyRate').addEventListener('input', validateHourlyRate);
document.getElementById('turfImage').addEventListener('input', validateTurfImage);

// Live validation feedback for Academy Form
document.getElementById('academyName').addEventListener('input', validateAcademyName);
document.getElementById('academyLevel').addEventListener('change', validateAcademyLevel);
document.getElementById('academyAgeGroup').addEventListener('change', validateAcademyAgeGroup);
document.getElementById('venueId').addEventListener('change', validateVenueId);
document.getElementById('academyCharges').addEventListener('input', validateAcademyCharges);
document.getElementById('academyDuration').addEventListener('input', validateAcademyDuration);
document.getElementById('academyDescription').addEventListener('input', validateAcademyDescription);
document.getElementById('academyImage').addEventListener('input', validateAcademyImage);
document.getElementById('startTime').addEventListener('change', validateStartTime);
document.getElementById('endTime').addEventListener('change', validateEndTime);

// Add event listeners to checkboxes for days validation
document.querySelectorAll('input[name="days[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', validateDays);
});

// Validation functions for Turf Form
function validateTurfName() {
    const turfName = document.getElementById('turfName').value;
    const lengthRequirement = document.getElementById('turfNameLength');
    const charsRequirement = document.getElementById('turfNameChars');
    
    if (turfName.length >= 3) {
        lengthRequirement.classList.add('valid');
        lengthRequirement.classList.remove('invalid');
    } else {
        lengthRequirement.classList.add('invalid');
        lengthRequirement.classList.remove('valid');
    }
    
    if (/^[a-zA-Z0-9\s\-.,&']+$/.test(turfName)) {
        charsRequirement.classList.add('valid');
        charsRequirement.classList.remove('invalid');
    } else {
        charsRequirement.classList.add('invalid');
        charsRequirement.classList.remove('valid');
    }
}

function validateTurfLocation() {
    const location = document.getElementById('turfLocation').value;
    const lengthRequirement = document.getElementById('turfLocationLength');
    
    if (location.length >= 5) {
        lengthRequirement.classList.add('valid');
        lengthRequirement.classList.remove('invalid');
    } else {
        lengthRequirement.classList.add('invalid');
        lengthRequirement.classList.remove('valid');
    }
}

function validateTurfLength() {
    const length = document.getElementById('turfLength').value;
    const validRequirement = document.getElementById('turfLengthValid');
    
    if (length > 0) {
        validRequirement.classList.add('valid');
        validRequirement.classList.remove('invalid');
    } else {
        validRequirement.classList.add('invalid');
        validRequirement.classList.remove('valid');
    }
}

function validateTurfBreadth() {
    const breadth = document.getElementById('turfBreadth').value;
    const validRequirement = document.getElementById('turfBreadthValid');
    
    if (breadth > 0) {
        validRequirement.classList.add('valid');
        validRequirement.classList.remove('invalid');
    } else {
        validRequirement.classList.add('invalid');
        validRequirement.classList.remove('valid');
    }
}

function validateHourlyRate() {
    const rate = document.getElementById('hourlyRate').value;
    const validRequirement = document.getElementById('hourlyRateValid');
    
    if (rate > 50) {
        validRequirement.classList.add('valid');
        validRequirement.classList.remove('invalid');
    } else {
        validRequirement.classList.add('invalid');
        validRequirement.classList.remove('valid');
    }
}

function validateTurfImage() {
    const imageUrl = document.getElementById('turfImage').value;
    const urlRequirement = document.getElementById('turfImageUrl');
    const imagePattern = /\.(jpeg|jpg|gif|png|bmp|webp|svg)$/i;
    
    if (imagePattern.test(imageUrl)) {
        urlRequirement.classList.add('valid');
        urlRequirement.classList.remove('invalid');
    } else {
        urlRequirement.classList.add('invalid');
        urlRequirement.classList.remove('valid');
    }
}

// Validation functions for Academy Form
function validateAcademyName() {
    const academyName = document.getElementById('academyName').value;
    const lengthRequirement = document.getElementById('academyNameLength');
    const charsRequirement = document.getElementById('academyNameChars');
    
    if (academyName.length >= 3) {
        lengthRequirement.classList.add('valid');
        lengthRequirement.classList.remove('invalid');
    } else {
        lengthRequirement.classList.add('invalid');
        lengthRequirement.classList.remove('valid');
    }
    
    if (/^[a-zA-Z0-9\s\-.,&']+$/.test(academyName)) {
        charsRequirement.classList.add('valid');
        charsRequirement.classList.remove('invalid');
    } else {
        charsRequirement.classList.add('invalid');
        charsRequirement.classList.remove('valid');
    }
}

function validateAcademyLevel() {
    const level = document.getElementById('academyLevel').value;
    const selectedRequirement = document.getElementById('academyLevelSelected');
    
    if (level !== '') {
        selectedRequirement.classList.add('valid');
        selectedRequirement.classList.remove('invalid');
    } else {
        selectedRequirement.classList.add('invalid');
        selectedRequirement.classList.remove('valid');
    }
}

function validateAcademyAgeGroup() {
    const ageGroup = document.getElementById('academyAgeGroup').value;
    const selectedRequirement = document.getElementById('academyAgeGroupSelected');
    
    if (ageGroup !== '') {
        selectedRequirement.classList.add('valid');
        selectedRequirement.classList.remove('invalid');
    } else {
        selectedRequirement.classList.add('invalid');
        selectedRequirement.classList.remove('valid');
    }
}

function validateVenueId() {
    const venueId = document.getElementById('venueId').value;
    const selectedRequirement = document.getElementById('venueIdSelected');
    
    if (venueId !== '') {
        selectedRequirement.classList.add('valid');
        selectedRequirement.classList.remove('invalid');
    } else {
        selectedRequirement.classList.add('invalid');
        selectedRequirement.classList.remove('valid');
    }
}

function validateAcademyCharges() {
    const charges = document.getElementById('academyCharges').value;
    const validRequirement = document.getElementById('academyChargesValid');
    
    if (charges > 50) {
        validRequirement.classList.add('valid');
        validRequirement.classList.remove('invalid');
    } else {
        validRequirement.classList.add('invalid');
        validRequirement.classList.remove('valid');
    }
}

function validateAcademyDuration() {
    const duration = document.getElementById('academyDuration').value;
    const validRequirement = document.getElementById('academyDurationValid');
    
    if (duration >= 1) {
        validRequirement.classList.add('valid');
        validRequirement.classList.remove('invalid');
    } else {
        validRequirement.classList.add('invalid');
        validRequirement.classList.remove('valid');
    }
}

function validateAcademyDescription() {
    const description = document.getElementById('academyDescription').value;
    const lengthRequirement = document.getElementById('academyDescriptionLength');
    
    if (description.length >= 20) {
        lengthRequirement.classList.add('valid');
        lengthRequirement.classList.remove('invalid');
    } else {
        lengthRequirement.classList.add('invalid');
        lengthRequirement.classList.remove('valid');
    }
}

function validateAcademyImage() {
    const imageUrl = document.getElementById('academyImage').value;
    const urlRequirement = document.getElementById('academyImageUrl');
    const imagePattern = /\.(jpeg|jpg|gif|png|bmp|webp|svg)$/i;
    
    if (imageUrl === '' || imagePattern.test(imageUrl)) {
        urlRequirement.classList.add('valid');
        urlRequirement.classList.remove('invalid');
    } else {
        urlRequirement.classList.add('invalid');
        urlRequirement.classList.remove('valid');
    }
}

function validateStartTime() {
    const startTime = document.getElementById('startTime').value;
    const selectedRequirement = document.getElementById('startTimeSelected');
    
    if (startTime !== '') {
        selectedRequirement.classList.add('valid');
        selectedRequirement.classList.remove('invalid');
    } else {
        selectedRequirement.classList.add('invalid');
        selectedRequirement.classList.remove('valid');
    }
    
    // Check time validation if both times are selected
    const endTime = document.getElementById('endTime').value;
    if (startTime !== '' && endTime !== '') {
        validateEndTime();
    }
}

function validateEndTime() {
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;
    const selectedRequirement = document.getElementById('endTimeSelected');
    const timeValidRequirement = document.getElementById('timeValid');
    
    if (endTime !== '') {
        selectedRequirement.classList.add('valid');
        selectedRequirement.classList.remove('invalid');
    } else {
        selectedRequirement.classList.add('invalid');
        selectedRequirement.classList.remove('valid');
    }
    
    // Check if end time is after start time
    if (startTime !== '' && endTime !== '' && parseInt(endTime) > parseInt(startTime)) {
        timeValidRequirement.classList.add('valid');
        timeValidRequirement.classList.remove('invalid');
    } else {
        timeValidRequirement.classList.add('invalid');
        timeValidRequirement.classList.remove('valid');
    }
}

function validateDays() {
    const selectedDays = document.querySelectorAll('input[name="days[]"]:checked');
    const selectedRequirement = document.getElementById('daysSelected');
    
    if (selectedDays.length > 0) {
        selectedRequirement.classList.add('valid');
        selectedRequirement.classList.remove('invalid');
    } else {
        selectedRequirement.classList.add('invalid');
        selectedRequirement.classList.remove('valid');
    }
}
        // Add event listeners for form submissions
        document.getElementById('turfForm').addEventListener('submit', function(event) {
            const turfErrors = validateTurfForm();
            
            if (turfErrors.length > 0) {
                event.preventDefault(); // Prevent form submission
                displayErrorsAtTop(turfErrors);
            }
        });

        document.getElementById('academyForm').addEventListener('submit', function(event) {
            const academyErrors = validateAcademyForm();
            
            if (academyErrors.length > 0) {
                event.preventDefault(); // Prevent form submission
                displayErrorsAtTop(academyErrors);
            }
        });
    </script>
</body>
</html>