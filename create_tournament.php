<?php
include 'header.php';
include 'db.php';

// Check if user is logged in and is an owner or admin
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_email']) || ($_SESSION['user_type'] != 'owner' && $_SESSION['user_type'] != 'admin')) {
    header("Location: login.php?message=You must be logged in as an owner to access this page");
    exit();
}

// Get venues owned by user or all venues if admin
$email = $_SESSION['user_email'];
$venues_query = "SELECT * FROM venue";
if ($_SESSION['user_type'] != 'admin') {
    $venues_query .= " WHERE owner_email = ?";
}
$stmt = $conn->prepare($venues_query);

if ($_SESSION['user_type'] != 'admin') {
    $stmt->bind_param("s", $email);
}
$stmt->execute();
$venues_result = $stmt->get_result();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate a new tournament ID
    $result = $conn->query("SELECT MAX(CAST(SUBSTRING(tr_id, 3) AS SIGNED)) as max_id FROM tournaments");
    $row = $result->fetch_assoc();
    $next_id = $row['max_id'] + 1;
    $tr_id = 't_' . str_pad($next_id, 3, '0', STR_PAD_LEFT);
    
    // Get form data
    $tr_name = $_POST['tr_name'] ?? '';
    $venue_id = $_POST['venue_id'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $startTime = date("h A", strtotime($_POST['start_time'] . ':00'));
    $endTime = date("h A", strtotime($_POST['end_time'] . ':00'));
    $tr_time = $startTime . ' - ' . $endTime;
    $entry_fee = $_POST['entry_fee'] ?? 0;
    $prize = $_POST['prize'] ?? 0;
    $description = $_POST['description'] ?? '';
    $img_url = $_POST['img_url'] ?? '';
    $players_per_team = $_POST['players_per_team'] ?? 0;
    $max_teams = $_POST['max_teams'] ?? 0;

    
    // Validate form data
    $errors = [];
    if (empty($tr_name)) $errors[] = "Tournament name is required";
    if (empty($venue_id)) $errors[] = "Venue is required";
    if (empty($start_date)) $errors[] = "Start date is required";
    if (empty($end_date)) $errors[] = "End date is required";
    if (empty($tr_time)) $errors[] = "Tournament time is required";
    if (empty($players_per_team) || !is_numeric($players_per_team)) $errors[] = "Number of players per team is required and must be a number";
    if ($_POST['start_time'] >= $_POST['end_time']) {
        $errors[] = "End time must be after start time";
    }
    if (empty($errors)) {
        // Insert tournament data
        $insert_sql = "INSERT INTO tournaments (tr_id, tr_name, venue_id, start_date, end_date, tr_time, entry_fee, prize, description, img_url, players_per_team, max_teams) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssssssddssii", $tr_id, $tr_name, $venue_id, $start_date, $end_date, $tr_time, $entry_fee, $prize, $description, $img_url, $players_per_team, $max_teams);
        
        if ($insert_stmt->execute()) {
            echo '<div class="success-message">Tournament created successfully!</div>';
        } else {
            echo '<div class="error-message">Failed to create tournament. Please try again. Error: ' . $conn->error . '</div>';
        }
        $insert_stmt->close();
    } else {
        echo '<div class="error-message">';
        foreach ($errors as $error) {
            echo htmlspecialchars($error) . '<br>';
        }
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Create Tournament</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/create_tournament.css">
</head>
<body>
    <div class="page-container">
        <div class="card-container">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2 class="form-title">Create Tournament</h2>
                <p class="form-subtitle">Set up a new tournament for your venue</p>
            </div>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="tr_name" class="form-label"><i class="fas fa-flag"></i> Tournament Name</label>
                    <input type="text" id="tr_name" name="tr_name" class="form-input" placeholder="Enter tournament name" required>
                </div>
                
                <div class="form-group">
                    <label for="venue_id" class="form-label"><i class="fas fa-map-marker-alt"></i> Select Venue</label>
                    <select id="venue_id" name="venue_id" class="form-input" required>
                        <option value="">-- Select Venue --</option>
                        <?php while ($venue = $venues_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($venue['venue_id']); ?>">
                                <?php echo htmlspecialchars($venue['venue_nm']) . ' (' . htmlspecialchars($venue['location']) . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="start_date" class="form-label"><i class="fas fa-calendar-plus"></i> Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="end_date" class="form-label"><i class="fas fa-calendar-check"></i> End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-input" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="start_time" class="form-label"><i class="fas fa-clock"></i> Tournament Start Time</label>
                            <select id="start_time" name="start_time" class="form-input" required>
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
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="end_time" class="form-label"><i class="fas fa-clock"></i> Tournament End Time</label>
                            <select id="end_time" name="end_time" class="form-input" required>
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
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="entry_fee" class="form-label"><i class="fas fa-coins"></i> Entry Fee (₹)</label>
                            <input type="number" id="entry_fee" name="entry_fee" class="form-input" min="0" step="0.01" placeholder="Entry fee amount" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="prize" class="form-label"><i class="fas fa-award"></i> Prize Money (₹)</label>
                            <input type="number" id="prize" name="prize" class="form-input" min="0" step="0.01" placeholder="Total prize amount">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="players_per_team" class="form-label"><i class="fas fa-users"></i> Players Per Team</label>
                            <input type="number" id="players_per_team" name="players_per_team" class="form-input" min="1" placeholder="Number of players required per team" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="max-teams" class="form-label"><i class="fas fa-users"></i> Max Teams per Tournament</label>
                            <input type="number" id="max-teams" name="max_teams" class="form-input" min="1" placeholder="Max Teams" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label"><i class="fas fa-info-circle"></i> Tournament Description</label>
                    <textarea id="description" name="description" class="form-input" placeholder="Write details about the tournament, rules, etc." required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="img_url" class="form-label"><i class="fas fa-image"></i> Tournament Image URL</label>
                    <input type="url" id="img_url" name="img_url" class="form-input" placeholder="Enter URL for tournament image/logo">
                </div>
                
                <button type="submit" class="submit-button">
                    <i class="fas fa-paper-plane"></i> Create Tournament
                </button>
            </form>
        </div>
    </div>
    
    <footer>
        <div class="footer-col">
            <div class="footer-logo">GAME DAY</div>
            <p class="footer-text">The #1 platform for booking futsal grounds and finding the best playing experiences in your area.</p>
        </div>

        <div class="footer-col footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Find Grounds</a></li>
                <li><a href="#">Join Academy</a></li>
                <li><a href="#">Partner With Us</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">FAQs</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <div class="contact">
                <h3>Contact Us</h3>
                <div class="contact-info">
                    <p><i class="fas fa-envelope"></i> info@gameday.com</p>
                    <p><i class="fas fa-phone"></i> +1 234 567 8900</p>
                </div>
            </div>
            <div class="social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Date validation for tournament dates
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if(startDateInput && endDateInput) {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        startDateInput.setAttribute('min', today);
        
        startDateInput.addEventListener('change', function() {
            // Set minimum end date to start date
            endDateInput.setAttribute('min', startDateInput.value);
            
            // If end date is before start date, reset it
            if(endDateInput.value && endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        });
    }
    
    // Players per team validation
    const playersPerTeamInput = document.getElementById('players_per_team');
    if(playersPerTeamInput) {
        playersPerTeamInput.addEventListener('input', function() {
            let value = parseInt(this.value);
            if(value < 1) {
                this.value = 1;
            }
        });
    }
    
    // Form validation
    const tournamentForm = document.querySelector('form');
    if(tournamentForm) {
        tournamentForm.addEventListener('submit', function(event) {
            const requiredFields = tournamentForm.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if(!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error-input');
                } else {
                    field.classList.remove('error-input');
                }
            });
            
            if(!isValid) {
                event.preventDefault();
                alert('Please fill out all required fields');
            }
        });
    }
});
    </script>
</body>
</html>