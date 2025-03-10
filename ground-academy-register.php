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
                <form id="turfForm" class="registration-form active">
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
                            <div class="form-group">
                                <label for="turfSize">Ground Size (in sq. ft)</label>
                                <input type="number" id="turfSize" name="turfSize" required placeholder="Enter ground size">
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

                    <button type="submit" class="submit-btn">Register Turf</button>
                </form>

                <!-- Academy Registration Form -->
                <form id="academyForm" class="registration-form">
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
                                <label for="academyType">Academy Level</label>
                                <select id="academyType" name="academyType" required>
                                    <option value="">Select Level</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="professional">Professional</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ageGroups">Age Groups</label>
                                <select id="ageGroups" name="ageGroups" required>
                                    <option value="">Select Age Group</option>
                                    <option value="kids">Kids (5-12 years)</option>
                                    <option value="teens">Teens (13-17 years)</option>
                                    <option value="adults">Adults (18+ years)</option>
                                    <option value="all">All Age Groups</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="academyImage">Academy Image URL</label>
                                <input type="url" id="academyImage" name="academyImage" required placeholder="Enter image URL">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Pricing</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="monthlyFee">Monthly Fee (₹)</label>
                                <input type="number" id="monthlyFee" name="monthlyFee" required placeholder="Enter monthly fee">
                            </div>
                        </div>
                    </div>

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
    </script>
</body>
</html> 