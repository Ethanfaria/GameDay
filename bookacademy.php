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
                    <?php
                        // Get features from database and convert to array
                        $features = explode(',', $academy['features']);
                        
                        // Display each feature with fancy icon
                        foreach ($features as $feature) {
                            $feature = trim($feature);
                            ?>
                            <div class="feature-item">
                                <i class="fas fa-football"></i>
                                <div>
                                    <h3><?php echo htmlspecialchars(ucfirst($feature)); ?></h3>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
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
                            <span><?php echo htmlspecialchars($academy['age_group']); ?></span>
                        </p>
                        <p>
                            <span>Level</span>
                            <span><?php echo htmlspecialchars($academy['level']); ?></span>
                        </p>
                    </div>
                    <button class="enroll-button" onclick="window.location.href='payment-academy.php?ac_id=<?php echo $academy['ac_id']; ?>&name=<?php echo urlencode($academy['aca_nm']); ?>&location=<?php echo urlencode($academy['ac_location']); ?>&amount=<?php echo $academy['ac_charges']; ?>'">
    ₹<?php echo number_format($academy['ac_charges']); ?>/month
</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Modal -->
    <div id="reviewsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Academy Reviews</h2>
                <span class="close-modal" onclick="closeReviewsModal()">&times;</span>
            </div>
            <div class="modal-body">
                <?php
                // Fetch reviews for this academy
                $review_sql = "SELECT ar.rating, ar.review_id, v.venue_nm 
                             FROM academy_reviews ar 
                             LEFT JOIN venue v ON ar.venue_id = v.venue_id 
                             WHERE ar.ac_id = ?";
                $review_stmt = $conn->prepare($review_sql);
                $review_stmt->bind_param("s", $ac_id);
                $review_stmt->execute();
                $reviews_result = $review_stmt->get_result();

                if ($reviews_result->num_rows > 0) {
                    while ($review = $reviews_result->fetch_assoc()) {
                        ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="review-rating">
                                    <?php
                                    // Display stars based on rating
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $review['rating']) {
                                            echo '<i class="fas fa-star"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="review-venue">
                                    <?php echo htmlspecialchars($review['venue_nm']); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="no-reviews">No reviews yet.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        

        // Proceed to payment
        function proceedToPayment() {
            const params = getUrlParams();
            const paymentUrl = `academy-payment.html?name=${encodeURIComponent(params.name)}&location=${encodeURIComponent(params.location)}&amount=${params.price}`;
            window.location.href = paymentUrl;
        }

        // Reviews Modal Functions
        function openReviewsModal() {
            document.getElementById('reviewsModal').style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }

        function closeReviewsModal() {
            document.getElementById('reviewsModal').style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('reviewsModal');
            if (event.target == modal) {
                closeReviewsModal();
            }
        }
    </script>
</body>
</html> 