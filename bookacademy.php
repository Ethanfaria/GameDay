<?php
    // Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    // Check if user is logged in
    if (!isset($_SESSION['user_email'])) {
        $_SESSION['review_error'] = "You must be logged in to submit a review.";
        header("Location: academy-details.php?ac_id=" . $_POST['ac_id']);
        exit();
    }
    
    // Get form data
    $ac_id = $_POST['ac_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    
    // Generate a unique review ID
    $review_id = 'ar_' . uniqid();
    
    // Insert the review
    $sql = "INSERT INTO academy_reviews (a_review_id, a_reviews, a_ratings, ac_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $review_id, $feedback, $rating, $ac_id);
    
    if ($stmt->execute()) {
        $_SESSION['review_success'] = "Your review has been submitted successfully!";
    } else {
        $_SESSION['review_error'] = "Error submitting your review. Please try again.";
    }
    
    // Redirect to prevent form resubmission
    header("Location: academy-details.php?ac_id=" . $ac_id);
    exit();
}

// Handle academy enrollment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enroll_academy'])) {
    // Store academy details in session
    $_SESSION['academy_id'] = $_POST['ac_id'];
    echo "Debug: Setting academy_id = " . $_POST['ac_id'];
    $_SESSION['academy_name'] = $_POST['academy_name'];
    $_SESSION['academy_location'] = $_POST['academy_location'];
    $_SESSION['academy_charges'] = $_POST['academy_charges'];
    $_SESSION['academy_duration'] = $_POST['academy_duration'];
    
    // Redirect to the payment page
    header('Location: payment-academy.php');
    exit();
}
?>
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
    <link rel="stylesheet" href="CSS\turfbooknow.css">
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

        // Get average rating and total reviews
        $rating_sql = "SELECT AVG(a_ratings) as average_rating FROM academy_reviews WHERE ac_id = ?";
        $rating_stmt = $conn->prepare($rating_sql);
        $rating_stmt->bind_param("s", $ac_id);
        $rating_stmt->execute();
        $rating_result = $rating_stmt->get_result();
        $rating_row = $rating_result->fetch_assoc();
        $academy_rating = $rating_row['average_rating'] ? number_format($rating_row['average_rating'], 1) : "4.9"; // Default to 4.9 if no reviews

        // Fetch academy reviews
        $reviews_sql = "SELECT * FROM academy_reviews WHERE ac_id = ?";
        $reviews_stmt = $conn->prepare($reviews_sql);
        $reviews_stmt->bind_param("s", $ac_id);
        $reviews_stmt->execute();
        $reviews_result = $reviews_stmt->get_result();
        $reviews = [];
        $total_reviews = 0;
        $ratings_sum = 0;

        if ($reviews_result->num_rows > 0) {
            while($row = $reviews_result->fetch_assoc()) {
                $reviews[] = $row;
                $ratings_sum += $row['a_ratings'];
                $total_reviews++;
            }
        }

        $average_rating = $total_reviews > 0 ? round($ratings_sum / $total_reviews, 1) : 0;
    } else {
        echo "<p>Academy not found.</p>";
        exit();
    }
    ?>
    <div class="academy-container">
        <div class="academy-header">
            <img src="<?php echo htmlspecialchars($academy['admy_img']); ?>" alt="Academy Image">
            <div class="academy-rating">
                <i class="fas fa-star"></i>
                <span id="academy-rating"><?php echo $academy_rating; ?></span>
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
                        // Display each feature from the three separate columns
                        $feature_columns = ['feature1', 'feature2', 'feature3'];
                        
                        foreach ($feature_columns as $column) {
                            if (!empty($academy[$column])) {
                                ?>
                                <div class="feature-item">
                                <i class="fa-solid fa-futbol"></i>
                                    <div>
                                        <h3><?php echo htmlspecialchars(ucfirst($academy[$column])); ?></h3>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>

                    <h2>Training Schedule</h2>
                    <div class="schedule-grid">
                        <div class="schedule-item">
                            <p><?php echo htmlspecialchars($academy['days']); ?></p>
                            <p><?php echo htmlspecialchars($academy['timings']); ?></p>
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
                        <p>
                            <span>Days</span>
                            <span><?php echo htmlspecialchars($academy['days']); ?></span>
                        </p>
                        <p>
                            <span>Duration</span>
                            <span><?php echo htmlspecialchars($academy['duration']); ?> months</span>
                        </p>
                    </div>
                    <div class="rating">
                        <i class="fas fa-star" style="color: var(--neon-green);"></i>
                    <span><?php echo $average_rating; ?> (<?php echo $total_reviews; ?> Reviews)</span>
                        <button id="showReviewsBtn" class="reviews-btn">Show Reviews</button>
                    </div>
                    <form action="bookacademy.php" method="POST">
                        <input type="hidden" name="ac_id" value="<?php echo $academy['ac_id']; ?>">
                        <input type="hidden" name="academy_name" value="<?php echo htmlspecialchars($academy['aca_nm']); ?>">
                        <input type="hidden" name="academy_location" value="<?php echo htmlspecialchars($academy['ac_location']); ?>">
                        <input type="hidden" name="academy_charges" value="<?php echo htmlspecialchars($academy['ac_charges']); ?>">
                        <input type="hidden" name="academy_duration" value="<?php echo htmlspecialchars($academy['duration']); ?>">
                        <input type="hidden" name="enroll_academy" value="1">
                        <button type="submit" class="enroll-button">
                            ₹<?php echo number_format($academy['ac_charges']); ?>/month
                        </button>
                    </form>
                </div>

                <div id="reviewsPopup" class="reviews-popup">
                    <div class="reviews-popup-content">
                        <span class="close-popup">&times;</span>
                        <h2>Reviews for <?php echo htmlspecialchars($academy['aca_nm']); ?></h2>
                        
                        <!-- Display success or error messages if they exist -->
                        <?php if(isset($_SESSION['review_success'])): ?>
                            <div class="alert alert-success">
                                <?php echo $_SESSION['review_success']; unset($_SESSION['review_success']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['review_error'])): ?>
                            <div class="alert alert-error">
                                <?php echo $_SESSION['review_error']; unset($_SESSION['review_error']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="overall-rating">
                            <div class="rating-number"><?php echo $average_rating; ?></div>
                            <div class="stars">
                                <?php 
                                // Display full stars
                                for ($i = 1; $i <= floor($average_rating); $i++) {
                                    echo '<i class="fas fa-star"></i>';
                                }
                                // Display half star if needed
                                if ($average_rating - floor($average_rating) >= 0.5) {
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                }
                                // Display empty stars
                                for ($i = ceil($average_rating); $i < 5; $i++) {
                                    echo '<i class="far fa-star"></i>';
                                }
                                ?>
                            </div>
                            <div class="total-reviews">Based on <?php echo $total_reviews; ?> reviews</div>
                        </div>
                        
                        <div class="reviews-list">
                            <?php if (count($reviews) > 0): ?>
                                <?php foreach ($reviews as $review): ?>
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-name">User</div>
                                            <div class="review-rating">
                                                <?php 
                                                // Display stars based on rating
                                                for ($i = 1; $i <= $review['a_ratings']; $i++) {
                                                    echo '<i class="fas fa-star"></i>';
                                                }
                                                for ($i = $review['a_ratings'] + 1; $i <= 5; $i++) {
                                                    echo '<i class="far fa-star"></i>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <?php echo htmlspecialchars($review['a_reviews']); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-reviews">No reviews yet. Be the first to leave a review!</div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Review submission form - now integrated directly on the page -->
                        <?php if (isset($_SESSION['user_email'])): // Only show form if user is logged in ?>
                            <div class="add-review-form">
                                <h3>Leave a Review</h3>
                                <form action="academy-details.php" method="POST">
                                    <input type="hidden" name="ac_id" value="<?php echo htmlspecialchars($ac_id); ?>">
                                    <input type="hidden" name="submit_review" value="1">
                                    <div class="rating-input">
                                        <span>Your Rating:</span>
                                        <div class="star-rating">
                                            <input type="radio" id="star5" name="rating" value="5" required><label for="star5"></label>
                                            <input type="radio" id="star4" name="rating" value="4"><label for="star4"></label>
                                            <input type="radio" id="star3" name="rating" value="3"><label for="star3"></label>
                                            <input type="radio" id="star2" name="rating" value="2"><label for="star2"></label>
                                            <input type="radio" id="star1" name="rating" value="1"><label for="star1"></label>
                                        </div>
                                    </div>
                                    <div class="review-text">
                                        <textarea name="feedback" rows="4" placeholder="Share your experience with this academy..." required></textarea>
                                    </div>
                                    <button type="submit" class="submit-review-btn">Submit Review</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="login-to-review">
                                <p>Please <a href="login.php">log in</a> to leave a review.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reviewsBtn = document.getElementById('showReviewsBtn');
        const reviewsPopup = document.getElementById('reviewsPopup');
        const closePopup = document.querySelector('.close-popup');
        
        reviewsBtn.addEventListener('click', function() {
            reviewsPopup.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent body scrolling when popup is open
        });
        
        closePopup.addEventListener('click', function() {
            reviewsPopup.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore body scrolling
        });
        
        // Close popup when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === reviewsPopup) {
                reviewsPopup.style.display = 'none';
                document.body.style.overflow = 'auto'; // Restore body scrolling
            }
        });
    });
    // Proceed to payment
    function proceedToPayment() {
            const params = getUrlParams();
            const paymentUrl = `payment-academy.php?name=${encodeURIComponent(params.name)}&location=${encodeURIComponent(params.location)}&amount=${params.price}`;
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