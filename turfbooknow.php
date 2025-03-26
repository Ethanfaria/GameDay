<?php
session_start();

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    // Review submission logic
    $venue_id = $_POST['venue_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    
    if (isset($_SESSION['user_email'])) {
        $email = $_SESSION['user)email'];
        
        // Generate a unique review ID
        $review_id = 'rev_' . uniqid();
        
        // Insert review into database
        $sql = "INSERT INTO venue_reviews (review_id, venue_id, ratings, feedback_clm) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $review_id, $venue_id, $rating, $feedback);
        
        if ($stmt->execute()) {
            // Set success message
            $_SESSION['review_success'] = "Your review has been submitted successfully!";
        } else {
            // Set error message
            $_SESSION['review_error'] = "Error submitting review: " . $stmt->error;
        }
    } else {
        $_SESSION['review_error'] = "You must be logged in to submit a review.";
    }
    
    // Redirect to the same page to prevent form resubmission
    header("Location: turfbooknow.php?venue_id=" . $venue_id);
    exit();
}

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selectedDate'])) {
    $_SESSION['booking_date'] = $_POST['selectedDate'];
    $_SESSION['booking_time'] = $_POST['selectedTime'];
    $_SESSION['venue_name'] = $_POST['venueName'];
    $_SESSION['price'] = $_POST['price'];
    $_SESSION['venue_id'] = $_POST['venue_id'];

    header('Location: payment-ground.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Book Turf</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\turfbooknow.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
<?php 
include 'header.php'; 
include 'db.php';

$venue_id = isset($_GET['venue_id']) ? $_GET['venue_id'] : null;

if (!$venue_id) {
    header("Location: grounds.php");
    exit();
}

$sql = "SELECT * FROM venue WHERE venue_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $venue_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $ground = $result->fetch_assoc();

    $reviews = array();
    $average_rating = 0;
    $total_reviews = 0;

    $sql = "SELECT * FROM venue_reviews WHERE venue_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $venue_id);
    $stmt->execute();
    $reviews_result = $stmt->get_result();

    if ($reviews_result->num_rows > 0) {
        $rating_sum = 0;
        while ($review = $reviews_result->fetch_assoc()) {
            $reviews[] = $review;
            $rating_sum += $review['ratings'];
        }
        $total_reviews = count($reviews);
        $average_rating = $total_reviews > 0 ? round($rating_sum / $total_reviews, 1) : 0;
} else {
    $average_rating = 0;
    $total_reviews = 0;
}
} else {
    echo "<p>Ground not found.</p>";
    exit();
}

// Fetch booked slots and venue availability
$booked_slots = array();
$sql = "SELECT bk_date, bk_dur FROM book WHERE venue_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $venue_id);
$stmt->execute();
$bookings_result = $stmt->get_result();

while ($booking = $bookings_result->fetch_assoc()) {
    $date = $booking['bk_date'];
    $duration = $booking['bk_dur'];
    if (!isset($booked_slots[$date])) {
        $booked_slots[$date] = array();
    }
    $booked_slots[$date][] = $duration;
}

// Pass booked slots and venue availability to JavaScript
echo "<script>
    const bookedSlots = " . json_encode($booked_slots) . ";
    const venueAvailability = " . $ground['availability'] . ";
</script>";
?>

    <div class="booking-container">
        <div class="turf-details">
            <img src="<?php echo htmlspecialchars($ground['turf_img']); ?>" alt="<?php echo htmlspecialchars($ground['venue_nm']); ?>" class="turf-image">
            <div class="turf-info">
                <h2><?php echo htmlspecialchars($ground['venue_nm']); ?></h2>
                <p>Location:<?php echo htmlspecialchars($ground['location']); ?></p>

                <div class="rating">
                    <i class="fas fa-star" style="color: var(--neon-green);"></i>
                    <span><?php echo $average_rating; ?> (<?php echo $total_reviews; ?> Reviews)</span>
                    <button id="showReviewsBtn" class="reviews-btn">Read Reviews</button>
                </div>
                <div id="reviewsPopup" class="reviews-popup">
                    <div class="reviews-popup-content">
                        <span class="close-popup">&times;</span>
                        <h2>Reviews for <?php echo htmlspecialchars($ground['venue_nm']); ?></h2>
                        
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
                                                for ($i = 1; $i <= $review['ratings']; $i++) {
                                                    echo '<i class="fas fa-star"></i>';
                                                }
                                                for ($i = $review['ratings'] + 1; $i <= 5; $i++) {
                                                    echo '<i class="far fa-star"></i>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <?php echo htmlspecialchars($review['feedback_clm']); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-reviews">No reviews yet. Be the first to leave a review!</div>
                            <?php endif; ?>
                        </div>
                        
                       <?php if (isset($_SESSION['user_email'])):?>
                            <div class="add-review-form">
                                <h3>Leave a Review</h3>
                                <form action="turfbooknow.php" method="POST">
                                    <input type="hidden" name="venue_id" value="<?php echo htmlspecialchars($venue_id); ?>">
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
                                        <textarea name="feedback" rows="4" placeholder="Share your experience with this venue..." required></textarea>
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
                <p class="description mt-3"><?php echo htmlspecialchars($ground['description']); ?></p>
                <div class="amenities mt-3">
                    <h4>Amenities</h4>
                    <ul>
                        <?php if (!empty($ground['amenity1'])): ?>
                            <li><?php echo htmlspecialchars($ground['amenity1']); ?></li>
                        <?php endif; ?>
                        
                        <?php if (!empty($ground['amenity2'])): ?>
                            <li><?php echo htmlspecialchars($ground['amenity2']); ?></li>
                        <?php endif; ?>
                        
                        <?php if (!empty($ground['amenity3'])): ?>
                            <li><?php echo htmlspecialchars($ground['amenity3']); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="pricing mt-3">
                    <h4>Pricing</h4>
                    <p>₹<?php echo htmlspecialchars($ground['price']); ?> per hour</p>
                </div>
            </div>
        </div>

        <div class="date-slider-container">
            <button class="slider-nav-btn prev" onclick="scrollDates('prev')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="date-slider" id="dateSlider">
            </div>
            <button class="slider-nav-btn next" onclick="scrollDates('next')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="time-slots-container">
            <button class="time-slots-nav-btn prev" onclick="scrollTimeSlots('prev')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="time-slots" id="timeSlots">
            </div>
            <button class="time-slots-nav-btn next" onclick="scrollTimeSlots('next')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <form id="bookingForm" action="turfbooknow.php" method="POST">
            <div class="booking-summary">
                <h3>Booking Summary</h3>
                <div id="bookingSummary">
                    <p>Select a date and time slot</p>
                </div>
                <input type="hidden" name="selectedDate" id="selectedDate">
                <input type="hidden" name="selectedTime" id="selectedTime">
                <input type="hidden" name="venueName" id="venueName">
                <input type="hidden" name="price" id="price">
                <input type="hidden" name="venue_id" value="<?php echo htmlspecialchars($venue_id); ?>">
                <button type="submit" class="confirm-button" id="confirmBooking" >Next</button>
            </div>
        </form>
    </div>

    <script>
        function scrollDates(direction) {
            const slider = document.getElementById('dateSlider');
            const scrollAmount = 360;
            
            if (direction === 'prev') {
                slider.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            } else {
                slider.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            }
        }

        // Convert 24h to 12h format
        function formatTime(hour) {
            const ampm = hour >= 12 ? 'PM' : 'AM';
            hour = hour % 12;
            hour = hour ? hour : 12; // Convert 0 to 12
            return `${hour}:00 ${ampm}`;
        }

        // Generate Date Slider
        function generateDateSlider() {
            const dateSlider = document.getElementById('dateSlider');
            const today = new Date();
            
            for (let i = 0; i < 14; i++) {
                const date = new Date(today);
                date.setDate(today.getDate() + i);
                
                const dayElement = document.createElement('div');
                dayElement.classList.add('date-day');
                dayElement.innerHTML = `
                    <div class="weekday">${date.toLocaleDateString('en-US', { weekday: 'short' })}</div>
                    <div class="date">${date.getDate()}</div>
                    <div class="month">${date.toLocaleDateString('en-US', { month: 'short' })}</div>
                `;
                
                dayElement.addEventListener('click', () => {
                    document.querySelectorAll('.date-day').forEach(el => el.classList.remove('selected'));
                    dayElement.classList.add('selected');
                    generateTimeSlots(date);
                });
                
                dateSlider.appendChild(dayElement);
            }
            // Select today by default
            dateSlider.firstElementChild.click();
        }

        function generateTimeSlots(selectedDate) {
    const timeSlots = document.getElementById('timeSlots');
    timeSlots.innerHTML = ''; // Clear previous slots
    
    const startHour = 6;  // 6 AM
    const endHour = 22;   // 10 PM
    
    const dateString = selectedDate.toISOString().split('T')[0];
    
    const bookedTimesForDate = bookedSlots[dateString] || [];
    
    // Check if venue is unavailable
    if (venueAvailability === 0) {
        const unavailableMessage = document.createElement('div');
        unavailableMessage.className = 'venue-unavailable-message';
        unavailableMessage.innerHTML = '<p>This venue is currently unavailable for booking.</p>';
        timeSlots.appendChild(unavailableMessage);
        return;
    }
    
    // Check if selected date is today
    const today = new Date();
    const isToday = selectedDate.getDate() === today.getDate() && 
                selectedDate.getMonth() === today.getMonth() && 
                selectedDate.getFullYear() === today.getFullYear();
    
    // Get current hour if it's today
    const currentHour = isToday ? today.getHours() : -1;
    
    for (let hour = startHour; hour < endHour; hour++) {
        // Skip past hours if it's today
        if (isToday && hour <= currentHour) {
            continue; // Skip this iteration - don't display past time slots
        }
        
        const startTime = formatTime(hour);
        const endTime = formatTime(hour + 1);
        const timeRange = `${startTime} - ${endTime}`;
        
        // Check if this specific time range is booked
        const isBooked = bookedTimesForDate.includes(timeRange);
        
        const timeSlot = document.createElement('div');
        timeSlot.classList.add('time-slot');
        
        if (isBooked) {
            timeSlot.classList.add('booked');
        }
        
        timeSlot.innerHTML = `
            <div class="time-slot-content">
                <div class="time-range">
                    <span class="time-slot-start">${startTime}</span>
                    <span class="time-slot-end">${endTime}</span>
                </div>
                ${isBooked ? '<div class="booking-status">Booked</div>' : '<div class="booking-status available">Available</div>'}
            </div>
        `;
        
        if (!isBooked) {
            timeSlot.addEventListener('click', () => {
                document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('selected'));
                timeSlot.classList.add('selected');
                updateBookingSummary(startTime, endTime, selectedDate, hour);
            });
        }

        timeSlots.appendChild(timeSlot);
    }
    
    // Display a message if no slots are available
    if (timeSlots.children.length === 0) {
        const noSlotsMessage = document.createElement('div');
        noSlotsMessage.className = 'no-slots-message';
        noSlotsMessage.innerHTML = '<p>No available time slots for today. Please select another date.</p>';
        timeSlots.appendChild(noSlotsMessage);
    }
}

        function updateBookingSummary(startTime, endTime, selectedDate, hour) {
            const bookingSummary = document.getElementById('bookingSummary');
            const formattedDate = selectedDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
    
            // Format date as YYYY-MM-DD for database storage
            const dbDateFormat = selectedDate.toISOString().split('T')[0];
            
            bookingSummary.innerHTML = `
                <p>Date: ${formattedDate}</p>
                <p>Time: ${startTime} - ${endTime}</p>
                <p>Location: ${<?php echo json_encode(htmlspecialchars($ground['venue_nm'])); ?>}</p>
                <p>Price: ₹${<?php echo json_encode(htmlspecialchars($ground['price'])); ?>}</p>
            `;

            // Set hidden form fields
            document.getElementById('selectedDate').value = dbDateFormat; // Use database format
            document.getElementById('selectedTime').value = `${startTime} - ${endTime}`; // Store the full time range
            document.getElementById('venueName').value = <?php echo json_encode(htmlspecialchars($ground['venue_nm'])); ?>;
            document.getElementById('price').value = <?php echo json_encode(htmlspecialchars($ground['price'])); ?>;
        }

        // Initialize
        generateDateSlider();

        function scrollTimeSlots(direction) {
            const slider = document.getElementById('timeSlots');
            const scrollAmount = 150; // Adjust this value as needed
            
            if (direction === 'prev') {
                slider.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            } else {
                slider.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            }
        }

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
    </script>
</body>
</html>