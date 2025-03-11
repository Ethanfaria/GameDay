<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Academy Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\bookacademy.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    <?php
    include 'header.php';
    include 'db.php';

    $ac_id = isset($_GET['ac_id']) ? $_GET['ac_id'] : null;

    if (!$ac_id) {
        header("Location: academy.php"); // Redirect if no academy ID provided
        exit();
    }

    $sql = "SELECT * FROM academys WHERE ac_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ac_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $academy = $result->fetch_assoc();
    } else {
        echo "<p>Academy not found.</p>";
        exit();
    }
    ?>
    <div class="academy-container">
        <div class="academy-header">
            <img src="https://images.unsplash.com/photo-1577741314755-048d8525d31e" alt="Academy Image">
            <div class="academy-rating">
                <i class="fas fa-star"></i>
                <span id="academy-rating">4.9</span>
            </div>
            <div class="academy-header-overlay">
                <h1 id="academy-name"><?php echo htmlspecialchars($academy['aca_nm']); ?></h1>
                <p id="academy-location"><?php echo htmlspecialchars($academy['ac_location']); ?></p>
            </div>
        </div>

        <div class="academy-content">
            <div class="academy-details">
                <div class="academy-info">
                    <h2>About the Academy</h2>
                    <p><?php echo htmlspecialchars($academy['description']); ?></p>

                    <div class="academy-features">
                        <div class="feature-item">
                            <i class="fas fa-user-tie"></i>
                            <div>
                                <h3>Professional Coaches</h3>
                                <p>UEFA licensed coaches with professional playing experience</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-futbol"></i>
                            <div>
                                <h3>World-Class Facilities</h3>
                                <p>FIFA approved artificial turf and modern training equipment</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-chart-line"></i>
                            <div>
                                <h3>Performance Tracking</h3>
                                <p>Regular assessment and detailed progress reports</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-trophy"></i>
                            <div>
                                <h3>Tournament Exposure</h3>
                                <p>Participation in local and national tournaments</p>
                            </div>
                        </div>
                    </div>

                    <h2>Training Schedule</h2>
                    <div class="schedule-grid">
                        <div class="schedule-item">
                            <h4>Weekday Sessions</h4>
                            <p>Monday - Friday</p>
                            <p>4:00 PM - 6:00 PM</p>
                        </div>
                        <div class="schedule-item">
                            <h4>Weekend Sessions</h4>
                            <p>Saturday - Sunday</p>
                            <p>8:00 AM - 10:00 AM</p>
                        </div>
                    </div>
                </div>

                <div class="enrollment-card">
                    <div class="price-tag">₹<?php echo htmlspecialchars($academy['ac_charges']); ?>/month</div>
                    <div class="enrollment-details">
                        <p>
                            <span>Age Group</span>
                            <span>8-12 years</span>
                        </p>
                        <p>
                            <span>Duration</span>
                            <span>3 months minimum</span>
                        </p>
                        <p>
                            <span>Sessions/Week</span>
                            <span>5 sessions</span>
                        </p>
                        <p>
                            <span>Batch Size</span>
                            <span>15 students max</span>
                        </p>
                    </div>
                    <button class="enroll-button" onclick="proceedToPayment()">Proceed to Payment</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to get URL parameters
        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                name: params.get('name') || 'Elite Football Academy',
                location: params.get('location') || 'Panjim, Goa',
                rating: params.get('rating') || '4.9',
                price: params.get('price') || '3000'
            };
        }

        // Update academy details
        function updateAcademyDetails() {
            const params = getUrlParams();
            document.getElementById('academy-name').textContent = params.name;
            document.getElementById('academy-location').textContent = params.location;
            document.getElementById('academy-rating').textContent = params.rating;
            document.querySelector('.price-tag').textContent = `₹${params.price}/month`;
        }

        // Proceed to payment
        function proceedToPayment() {
            const params = getUrlParams();
            const paymentUrl = `academy-payment.html?name=${encodeURIComponent(params.name)}&location=${encodeURIComponent(params.location)}&amount=${params.price}`;
            window.location.href = paymentUrl;
        }

        // Initialize page
        updateAcademyDetails();
    </script>
</body>
</html> 