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

// Fetch booked slots for the venue
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

// Pass booked slots to JavaScript
echo "<script>const bookedSlots = " . json_encode($booked_slots, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";</script>";
?>

    <!-- Booking Container -->
    <div class="booking-container">
        <!-- Turf Details -->
        <div class="turf-details">
            <img src="/api/placeholder/600/400" alt="Don Bosco Turf" class="turf-image">
            <div class="turf-info">
                <h2><?php echo htmlspecialchars($ground['venue_nm']); ?></h2>
                <p>Location:<?php echo htmlspecialchars($ground['location']); ?></p>
                <div class="rating">
                    <i class="fas fa-star" style="color: var(--neon-green);"></i>
                    <span>4.9 (120 Reviews)</span>
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
        <div class="time-slots" id="timeSlots">
            <!-- Slots will be dynamically added -->
        </div>

        <!-- Booking Summary -->
        <div class="booking-summary">
            <h3>Booking Summary</h3>
            <div id="bookingSummary">
                <p>Select a date and time slot</p>
            </div>
            <button class="confirm-button" id="confirmBooking" onclick="window.location.href='payment-ground.php?venue_id=<?php echo $ground['venue_id']; ?>'">Confirm Booking</button>
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

            // Create a container for all time slots
            const timeSlotsContainer = document.createElement('div');
            timeSlotsContainer.className = 'time-slots-grid';
            
            for (let hour = startHour; hour < endHour; hour++) {
                const timeSlot = document.createElement('div');
                timeSlot.classList.add('time-slot');
                
                const startTime = formatTime(hour);
                const endTime = formatTime(hour + 1);
                
                const isBooked = bookedHours.includes(hour);
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
                        updateBookingSummary(startTime, endTime, selectedDate);
                    });
                }

                timeSlotsContainer.appendChild(timeSlot);
            }

            // Add the time slots container to the main container
            timeSlots.appendChild(timeSlotsContainer);
        }

        // Update Booking Summary
        function updateBookingSummary(startTime, endTime, selectedDate) {
            const bookingSummary = document.getElementById('bookingSummary');
            const formattedDate = selectedDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            bookingSummary.innerHTML = `
                <p>Date: ${formattedDate}</p>
                <p>Time: ${startTime} - ${endTime}</p>
                <p>Location: ${<?php echo json_encode(htmlspecialchars($ground['venue_nm'])); ?>}</p>
                <p>Price: ₹${<?php echo json_encode(htmlspecialchars($ground['price'])); ?>}</p>
            `;
        }

        // Confirm Booking
        document.getElementById('confirmBooking').addEventListener('click', () => {
            const selectedDate = document.querySelector('.date-day.selected');
            const selectedTime = document.querySelector('.time-slot.selected');
            
            if (selectedDate && selectedTime) {
                // Send the booking data to the server
                alert('Booking Confirmed! You will be redirected to payment.');
            } else {
                alert('Please select a date and time slot');
            }
        });

        // Initialize
        generateDateSlider();
    </script>
</body>
</html>
