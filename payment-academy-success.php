<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Enrollment Confirmed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --dark-green: #0a2e1a;
            --neon-green: #b9ff00;
            --dark-gray: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', 'Arial', sans-serif;
        }

        body {
            background-color: var(--dark-green);
            color: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .success-container {
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .success-icon {
            font-size: 80px;
            color: var(--neon-green);
            margin-bottom: 20px;
        }

        h1 {
            color: var(--neon-green);
            margin-bottom: 20px;
        }

        .enrollment-details {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }

        .enrollment-details p {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
        }

        .program-details {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }

        .program-details h3 {
            color: var(--neon-green);
            margin-bottom: 15px;
        }

        .program-details ul {
            list-style: none;
        }

        .program-details li {
            margin: 10px 0;
            display: flex;
            align-items: center;
        }

        .program-details li i {
            color: var(--neon-green);
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .buttons {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .button {
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .primary-button {
            background-color: var(--neon-green);
            color: var(--dark-green);
        }

        .secondary-button {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .button:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }

        .note {
            margin-top: 20px;
            padding: 15px;
            background-color: rgba(185, 255, 0, 0.1);
            border-radius: 10px;
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.8);
        }

        .note i {
            color: var(--neon-green);
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1>Enrollment Confirmed!</h1>
        <p>Your enrollment has been successfully confirmed. Welcome to the academy!</p>
        
        <div class="enrollment-details">
            <p>
                <span>Enrollment ID:</span>
                <span id="enrollment-id"></span>
            </p>
            <p>
                <span>Academy:</span>
                <span id="academy-name"></span>
            </p>
            <p>
                <span>Location:</span>
                <span id="academy-location"></span>
            </p>
            <p>
                <span>Course Duration:</span>
                <span>3 months</span>
            </p>
            <p>
                <span>Amount Paid:</span>
                <span id="amount-paid"></span>
            </p>
        </div>

        <div class="program-details">
            <h3>Program Schedule</h3>
            <ul>
                <li><i class="fas fa-calendar-alt"></i> Weekdays: Monday to Friday, 4:00 PM - 6:00 PM</li>
                <li><i class="fas fa-calendar-day"></i> Weekends: Saturday & Sunday, 8:00 AM - 10:00 AM</li>
                <li><i class="fas fa-clock"></i> 5 sessions per week</li>
                <li><i class="fas fa-users"></i> Batch size: Maximum 15 students</li>
            </ul>
        </div>

        <div class="note">
            <i class="fas fa-info-circle"></i>
            Classes start from next week. Please arrive 15 minutes early on your first day.
        </div>

        <div class="buttons">
            <a href="homepage.php" class="button primary-button">Back to Home</a>
            <a href="#" class="button secondary-button" onclick="window.print()">Download Receipt</a>
        </div>
    </div>

    <script>
        // Function to generate enrollment ID
        function generateEnrollmentId() {
            return 'ENR' + Math.random().toString(36).substr(2, 9).toUpperCase();
        }

        // Function to get URL parameters
        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                name: params.get('name') || localStorage.getItem('academyName') || 'Elite Football Academy',
                location: params.get('location') || localStorage.getItem('academyLocation') || 'Panjim, Goa',
                amount: params.get('amount') || localStorage.getItem('academyAmount') || '9000'
            };
        }

        // Update enrollment details
        function updateEnrollmentDetails() {
            const params = getUrlParams();
            document.getElementById('enrollment-id').textContent = generateEnrollmentId();
            document.getElementById('academy-name').textContent = params.name;
            document.getElementById('academy-location').textContent = params.location;
            document.getElementById('amount-paid').textContent = `₹${params.amount}`;
        }

        // Initialize page
        updateEnrollmentDetails();
    </script>
</body>
</html> 