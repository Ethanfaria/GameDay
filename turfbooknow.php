<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Store date, time, and venue details in session
    $_SESSION['booking_date'] = $_POST['selectedDate'];
    $_SESSION['booking_time'] = $_POST['selectedTime'];
    $_SESSION['venue_name'] = $_POST['venueName'];
    $_SESSION['price'] = $_POST['price'];
    $_SESSION['venue_id'] = $_POST['venue_id'];
    
    // For debugging
    echo "Setting venue_id in session: " . $_POST['venue_id']; 
    
    // Redirect to the payment page
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
    <style>
        /* Add this CSS for booked time slots */
        .time-slot.booked {
            background-color: #ffdddd; /* Light red background */
            border-color: #ff0000; /* Red border */
            cursor: not-allowed; /* Change cursor to indicate not clickable */
        }

        .time-slot.booked .booking-status {
            color: #ff0000;
            font-weight: bold;
        }

        .time-slot.selected {
            background-color: #d4ffcc; /* Light green when selected */
            border-color: #00cc00;
        }
    </style>
</head>
<body>
<?php 
include 'header.php'; 
include 'db.php';

$venue_id = isset($_GET['venue_id']) ? $_GET['venue_id'] : null;

if (!$venue_id) {
    header("Location: grounds.php"); // Redirect if no venue ID provided
    exit();
}

$sql = "SELECT * FROM venue WHERE venue_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $venue_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $ground = $result->fetch_assoc();
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

    <!-- Booking Container -->
    <div class="booking-container">
        <!-- Turf Details -->
        <div class="turf-details">
            <img src="https://content.jdmagicbox.com/comp/goa/q3/0832px832.x832.230913204513.f7q3/catalogue/kicks-n-flicks-goa-mini-football-fields-9soy7roh71.jpg" alt="Don Bosco Turf" class="turf-image">
            <div class="turf-info">
                <h2><?php echo htmlspecialchars($ground['venue_nm']); ?></h2>
                <p>Location:<?php echo htmlspecialchars($ground['location']); ?></p>
                <div class="rating">
                    <i class="fas fa-star" style="color: var(--neon-green);"></i>
                    <span>4.9 (120 Reviews)</span>
                    <button id="showReviewsBtn" class="review-link">(120 Reviews)</button>
                </div>
                <p class="description mt-3">Premium futsal ground with high-quality artificial turf. Perfect for professional and amateur players.</p>
                <div class="amenities mt-3">
                    <h4>Amenities</h4>
                    <ul>
                        <li>Changing Rooms</li>
                        <li>Parking Available</li>
                        <li>Water Facility</li>
                    </ul>
                </div>
                <div class="pricing mt-3">
                    <h4>Pricing</h4>
                    <p>₹<?php echo htmlspecialchars($ground['price']); ?> per hour</p>
                </div>
            </div>
        </div>

        <!-- Date Slider -->
        <div class="date-slider-container">
            <button class="slider-nav-btn prev" onclick="scrollDates('prev')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="date-slider" id="dateSlider">
                <!-- JavaScript will populate this dynamically -->
            </div>
            <button class="slider-nav-btn next" onclick="scrollDates('next')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- Time Slots -->
        <div class="time-slots-container">
            <button class="time-slots-nav-btn prev" onclick="scrollTimeSlots('prev')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="time-slots" id="timeSlots">
                <!-- Slots will be dynamically added -->
            </div>
            <button class="time-slots-nav-btn next" onclick="scrollTimeSlots('next')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- Booking Summary -->
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
    
        <div id="reviewsPopup" class="reviews-popup">
            <div class="reviews-popup-content">
                <span class="close-popup">&times;</span>
                <h2>Reviews for <?php echo htmlspecialchars($ground['venue_nm']); ?></h2>
                <div class="overall-rating">
                    <div class="rating-number">4.9</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="total-reviews">Based on 120 reviews</div>
                </div>
                
                <div class="reviews-list">
                    <!-- Sample reviews - in a real implementation, you would fetch these from the database -->
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-name">John D.</div>
                            <div class="review-date">March 10, 2025</div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="review-content">
                            Amazing turf! Perfect condition and the staff was very friendly. Will definitely book again.
                        </div>
                    </div>
                    
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-name">Sarah M.</div>
                            <div class="review-date">March 5, 2025</div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="review-content">
                            One of the best grounds I've played on. Great turf quality and excellent facilities.
                        </div>
                    </div>
                    
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-name">Mike P.</div>
                            <div class="review-date">February 28, 2025</div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <div class="review-content">
                            Very good quality turf and well-maintained. The only downside was the limited parking space.
                        </div>
                    </div>
                    
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-name">Rachel T.</div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="review-content">
                            This is our team's favorite place to practice! Great lighting for evening games and the turf is always in excellent condition.
                        </div>
                    </div>
                    
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-name">Alex W.</div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                        <div class="review-content">
                            Clean facilities and good quality turf. Changing rooms are spacious and well-maintained. Would recommend!
                        </div>
                    </div>
                    
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-name">David L.</div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="review-content">
                            Best turf in the area by far! The staff is super helpful and the booking process is very straightforward.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Add this new function for date slider navigation
        function scrollDates(direction) {
            const slider = document.getElementById('dateSlider');
            const scrollAmount = 360; // Scroll by 3 date boxes at a time
            
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

        // Generate Time Slots
        function generateTimeSlots(selectedDate) {
            const timeSlots = document.getElementById('timeSlots');
            timeSlots.innerHTML = ''; // Clear previous slots
            
            const startHour = 6;  // 6 AM
            const endHour = 22;   // 10 PM
            
            const dateString = selectedDate.toISOString().split('T')[0];
            const bookedHours = bookedSlots[dateString] || [];

            // Only generate time slots if venue is available
            if (venueAvailability === 0) {
                const unavailableMessage = document.createElement('div');
                unavailableMessage.className = 'venue-unavailable-message';
                unavailableMessage.innerHTML = '<p>This venue is currently unavailable for booking.</p>';
                timeSlots.appendChild(unavailableMessage);
                return;
            }
            
            for (let hour = startHour; hour < endHour; hour++) {
                const timeSlot = document.createElement('div');
                timeSlot.classList.add('time-slot');
                
                const startTime = formatTime(hour);
                const endTime = formatTime(hour + 1);
                
                const isBooked = bookedHours.includes(hour.toString()) || bookedHours.includes(hour);
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
            document.getElementById('selectedTime').value = hour; // Store the hour number for backend processing
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
        // Add this to your existing script or create a new script section
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