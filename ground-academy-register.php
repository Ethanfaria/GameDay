<?php
include 'db.php'; // Include the database connection file
session_start(); // Make sure to start the session to access user data

if (!isset($_SESSION['user_email'])) {
    // Redirect to login if user is not logged in
    echo "<script>alert('Please login to register a facility.'); window.location.href='login.php';</script>";
    exit();
}

$user_email = $_SESSION['user_email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['registerAcademy'])) { // If Academy form is submitted
        
        $ac_id = uniqid('ac_'); // Generate a unique ID
        $aca_nm = $_POST['academyName'];
        $ac_location = $_POST['academyLocation'];
        $ac_charges = $_POST['academyCharges'];
        $venue_id = $_POST['venueId'];
        $level = $_POST['academyLevel'];
        $age_group = $_POST['academyAgeGroup'];
        $description = $_POST['academyDescription'];
        $feature1 = $_POST['feature1'];
        $feature2 = $_POST['feature2'];
        $feature3 = $_POST['feature3'];
        $admy_img = $_POST['academyImage'];
        $duration = $_POST['academyDuration'];
        
        // Process the time inputs
        $startTime = date("h A", strtotime($_POST['startTime'] . ':00'));
        $endTime = date("h A", strtotime($_POST['endTime'] . ':00'));
        $timings = $startTime . ' - ' . $endTime;
        
        // Process the days checkboxes
        $selectedDays = isset($_POST['days']) ? $_POST['days'] : [];
        $days = !empty($selectedDays) ? implode(', ', $selectedDays) : 'Not specified';
        
        $sql = "INSERT INTO academys (ac_id, aca_nm, ac_location, ac_charges, venue_id, level, age_group, description, feature1, feature2, feature3, admy_img, timings, days, duration, owner_email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdssssssssssis", $ac_id, $aca_nm, $ac_location, $ac_charges, $venue_id, $level, $age_group, $description, $feature1, $feature2, $feature3, $admy_img, $timings, $days, $duration, $user_email);
        
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
            
            echo "<script>alert('Academy Registered Successfully!'); window.location.href='ground-academy-register.php';</script>";
        } else {
            echo "<script>alert('Error: Unable to register academy. " . $conn->error . "');</script>";
        }
    }

    if (isset($_POST['registerTurf'])) {
        $venue_id = uniqid('ven_'); // Generate a unique ID
        $venue_nm = $_POST['turfName'];
        $location = $_POST['turfLocation'];
        $length = $_POST['turfLength'];
        $breadth = $_POST['turfBreadth'];
        $size = $length . ' x ' . $breadth;
        $price = $_POST['hourlyRate'];
        $turf_img = $_POST['turfImage'];
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
            
            echo "<script>alert('Turf Registered Successfully!'); window.location.href='ground-academy-register.php';</script>";
        } else {
            echo "<script>alert('Error: Unable to register turf. " . $conn->error . "');</script>";
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
                            </div>
                            <div class="form-group">
                                <label for="turfLocation">Location</label>
                                <input type="text" id="turfLocation" name="turfLocation" required placeholder="Enter turf location">
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3>Ground Dimensions</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="turfLength">Length (m)</label>
                                <input type="number" id="turfLength" name="turfLength" required placeholder="Enter length">
                            </div>
                            <div class="form-group">
                                <label for="turfBreadth">Breadth (m)</label>
                                <input type="number" id="turfBreadth" name="turfBreadth" required placeholder="Enter breadth">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Pricing & Media</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="hourlyRate">Hourly Rate (₹)</label>
                                <input type="number" id="hourlyRate" name="hourlyRate" required placeholder="Enter hourly rate">
                            </div>
                            <div class="form-group">
                                <label for="turfImage">Turf Image URL</label>
                                <input type="url" id="turfImage" name="turfImage" required placeholder="Enter image URL">
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
                            </div>
                            <div class="form-group">
                                <label for="academyLocation">Location</label>
                                <input type="text" id="academyLocation" name="academyLocation" required placeholder="Enter academy location">
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
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Pricing</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="academyCharges">Monthly Fee (₹)</label>
                                <input type="number" id="academyCharges" name="academyCharges" required placeholder="Enter monthly fee">
                            </div>
                            <div class="form-group">
                                <label for="academyDuration">Duration (Months)</label>
                                <input type="number" id="academyDuration" name="academyDuration" min="1" required placeholder="Enter program duration">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Additional Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="academyDescription">Description</label>
                                <textarea id="academyDescription" name="academyDescription" rows="4" placeholder="Enter academy description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3>Schedule & Media</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="academyImage">Academy Image URL</label>
                                <input type="url" id="academyImage" name="academyImage" placeholder="Enter image URL">
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
                    
                    // Toggle the selected class based on checkbox state
                    // We use setTimeout to allow the checkbox to change state first
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

        // Validation for Turf Form
        function validateTurfForm() {
            const errorMessages = [];
            
            const turfName = document.getElementById('turfName').value.trim();
            const turfLocation = document.getElementById('turfLocation').value.trim();
            const turfSize = document.getElementById('turfSize').value;
            const hourlyRate = document.getElementById('hourlyRate').value;
            const turfImage = document.getElementById('turfImage').value.trim();
            
            // Turf Name validation
            if (turfName === '') {
                errorMessages.push('Turf Name is required');
            }
            
            // Location validation
            if (turfLocation === '') {
                errorMessages.push('Turf Location is required');
            }
            
            // Size validation
            const turfLength = document.getElementById('turfLength').value;
            const turfBreadth = document.getElementById('turfBreadth').value;
            
            if (turfLength === '' || isNaN(turfLength) || parseFloat(turfLength) <= 0) {
                errorMessages.push('Valid Ground Length is required');
            }
            
            if (turfBreadth === '' || isNaN(turfBreadth) || parseFloat(turfBreadth) <= 0) {
                errorMessages.push('Valid Ground Breadth is required');
            }
            
            // Hourly Rate validation
            if (hourlyRate === '' || isNaN(hourlyRate) || parseFloat(hourlyRate) <= 0) {
                errorMessages.push('Valid Hourly Rate is required');
            }
            
            // Image URL validation
            const urlPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
            if (turfImage === '' || !urlPattern.test(turfImage)) {
                errorMessages.push('Valid Image URL is required');
            }
            
            return errorMessages;
        }

        // Validation for Academy Form
        function validateAcademyForm() {
            const errorMessages = [];
            
            const academyName = document.getElementById('academyName').value.trim();
            const academyLocation = document.getElementById('academyLocation').value.trim();
            const academyLevel = document.getElementById('academyLevel').value;
            const academyAgeGroup = document.getElementById('academyAgeGroup').value;
            const venueId = document.getElementById('venueId').value;
            const academyCharges = document.getElementById('academyCharges').value;
            const academyDuration = document.getElementById('academyDuration').value;
            const academyDescription = document.getElementById('academyDescription').value.trim();
            const academyImage = document.getElementById('academyImage').value.trim();
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            
            // Academy Name validation
            if (academyName === '') {
                errorMessages.push('Academy Name is required');
            }
            
            // Location validation
            if (academyLocation === '') {
                errorMessages.push('Academy Location is required');
            }
            
            // Level validation
            if (academyLevel === '') {
                errorMessages.push('Academy Level is required');
            }
            
            // Age Group validation
            if (academyAgeGroup === '') {
                errorMessages.push('Age Group is required');
            }
            
            // Venue validation
            if (venueId === '') {
                errorMessages.push('Venue Selection is required');
            }
            
            // Charges validation
            if (academyCharges === '' || isNaN(academyCharges) || parseFloat(academyCharges) <= 0) {
                errorMessages.push('Valid Monthly Fee is required');
            }
            
            // Duration validation
            if (academyDuration === '' || isNaN(academyDuration) || parseInt(academyDuration) <= 0) {
                errorMessages.push('Valid Program Duration is required');
            }
            
            // Description validation
            if (academyDescription === '') {
                errorMessages.push('Academy Description is required');
            }
            
            // Image URL validation (optional, but validate if provided)
            if (academyImage !== '') {
                const urlPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
                if (!urlPattern.test(academyImage)) {
                    errorMessages.push('Invalid Image URL');
                }
            }
            
            // Time validation
            if (startTime === '') {
                errorMessages.push('Start Time is required');
            }
            
            if (endTime === '') {
                errorMessages.push('End Time is required');
            }
            
            // Check that end time is after start time
            if (startTime && endTime && startTime >= endTime) {
                errorMessages.push('End Time must be after Start Time');
            }
            
            // Ensure at least one day is selected
            const selectedDays = document.querySelectorAll('input[name="days[]"]:checked');
            if (selectedDays.length === 0) {
                errorMessages.push('At least one day must be selected');
            }
            
            return errorMessages;
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