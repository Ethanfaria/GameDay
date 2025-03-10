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
    <link rel="stylesheet" href="turfbooknow.css">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<?php include 'header.php'; ?>

    <!-- Booking Container -->
    <div class="booking-container">
        <!-- Turf Details -->
        <div class="turf-details">
            <img src="/api/placeholder/600/400" alt="Don Bosco Turf" class="turf-image">
            <div class="turf-info">
                <h2>Don Bosco Turf</h2>
                <p>Location: Panjim, Goa</p>
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
                    <p>₹1200 per hour</p>
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
            <button class="confirm-button" id="confirmBooking">Confirm Booking</button>
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
                    generateTimeSlots();
                });
                
                dateSlider.appendChild(dayElement);
            }
        }

        // Generate Time Slots
        function generateTimeSlots() {
            const timeSlots = document.getElementById('timeSlots');
            timeSlots.innerHTML = ''; // Clear previous slots
            
            const startHour = 6; // 6 AM
            const endHour = 22;   // 10 PM
            
            for (let hour = startHour; hour < endHour; hour++) {
                const slotStart = `${hour.toString().padStart(2, '0')}:00`;
                const slotEnd = `${(hour + 1).toString().padStart(2, '0')}:00`;
                
                const timeSlot = document.createElement('div');
                timeSlot.classList.add('time-slot');
                timeSlot.innerHTML = `
                    <span class="time-slot-start">${slotStart}</span>
                    <span class="time-slot-end">${slotEnd}</span>
                `;
                
                timeSlot.addEventListener('click', () => {
                    document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('selected'));
                    timeSlot.classList.add('selected');
                    updateBookingSummary(slotStart, slotEnd);
                });
                
                timeSlots.appendChild(timeSlot);
            }
        }

        // Update Booking Summary
        function updateBookingSummary(startTime, endTime) {
            const bookingSummary = document.getElementById('bookingSummary');
            const selectedDate = document.querySelector('.date-day.selected');
            
            if (selectedDate) {
                const dateText = selectedDate.innerText.split('\n').join(' ');
                bookingSummary.innerHTML = `
                    <p>Date: ${dateText}</p>
                    <p>Time: ${startTime} - ${endTime}</p>
                    <p>Location: Don Bosco Turf</p>
                    <p>Price: ₹1200</p>
                `;
            }
        }

        // Confirm Booking
        document.getElementById('confirmBooking').addEventListener('click', () => {
            const selectedDate = document.querySelector('.date-day.selected');
            const selectedTime = document.querySelector('.time-slot.selected');
            
            if (selectedDate && selectedTime) {
                alert('Booking Confirmed! You will be redirected to payment.');
                // Here you would typically implement payment or booking confirmation logic
            } else {
                alert('Please select a date and time slot');
            }
        });

        // Initialize
        generateDateSlider();
    </script>
</body>
</html>
