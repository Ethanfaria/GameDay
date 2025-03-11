<?php
include 'header.php';
include 'db.php';

$tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : null;

if (!$tr_id) {
    header("Location: tournaments.php"); // Redirect if no tournament ID provided
    exit();
}

// Get tournament details using tr_id
$tournament_sql = "SELECT t.tr_id, t.tr_name, t.start_date, t.end_date, t.tr_schedule, 
                         t.entry_fee, t.description, t.img_url, v.venue_nm, v.location 
                  FROM tournaments t
                  LEFT JOIN venue v ON t.venue_id = v.venue_id 
                  WHERE t.tr_id = ?";
$stmt = $conn->prepare($tournament_sql);
$stmt->bind_param("s", $tr_id);
$stmt->execute();
$result = $stmt->get_result();
$tournament = $result->fetch_assoc();

if (!$tournament) {
    header("Location: tournaments.php"); // Redirect if tournament not found
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Tournament Registration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/tournregis.css">

</head>
<body>
    <div class="page-container">
        <!-- Registration Header -->
        <div class="registration-header">
            <h1 class="registration-title"><?php echo htmlspecialchars($tournament['tr_name']); ?></h1>
            <p class="registration-subtitle">Register Your Team for the Ultimate Competition</p>
        </div>

        <!-- Registration Container with Split Layout -->
        <div class="registration-container">
            <!-- Tournament Information Section -->
            <div class="tournament-info">
                <div class="tournament-decoration"></div>
                <div class="tournament-info-content">
                    <div class="tournament-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h2 class="tournament-name"><?php echo htmlspecialchars($tournament['tr_name']); ?></h2>
                    
                    <div class="tournament-detail">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Location</h3>
                            <p><?php echo htmlspecialchars($tournament['venue_nm']); ?></p>
                            <p><?php echo htmlspecialchars($tournament['location']); ?></p>
                        </div>
                    </div>
                    
                    <div class="tournament-detail">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Tournament Dates</h3>
                            <p><?php echo date('F d', strtotime($tournament['start_date'])); ?> - <?php echo date('F d, Y', strtotime($tournament['end_date'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="tournament-detail">
                        <div class="detail-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Match Times</h3>
                            <p><?php echo htmlspecialchars($tournament['tr_schedule']); ?></p>
                        </div>
                    </div>
                    
                    <div class="tournament-detail">
                        <div class="detail-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Entry Fee</h3>
                            <p>₹<?php echo number_format($tournament['entry_fee']); ?></p>
                        </div>
                    </div>
                    
                    <div class="tournament-highlight">
                        <h3 class="highlight-title"><i class="fas fa-star"></i> Tournament Highlight</h3>
                        <p class="hightlight-text">
                            <?php echo htmlspecialchars($tournament['description']); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Registration Form Section -->
            <div class="card-container">
                <form class="registration-form" id="registrationForm" method="POST" action="">
                    <div class="form-decoration"></div>
                    <div class="form-decoration-2"></div>
                    
                    <div class="form-header">
                        <div class="form-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h2 class="form-title">Team Registration</h2>
                    </div>

                    <?php
                    // Process form submission
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $team_name = $_POST['team_name'] ?? '';
                        $captain_name = $_POST['captain_name'] ?? '';
                        $captain_phone = $_POST['captain_phone'] ?? '';
                        $captain_email = $_POST['captain_email'] ?? '';
                        $players_count = $_POST['players_count'] ?? '';

                        // Validate form data
                        $errors = [];
                        if (empty($team_name)) $errors[] = "Team name is required";
                        if (empty($captain_name)) $errors[] = "Captain name is required";
                        if (empty($captain_phone)) $errors[] = "Captain phone is required";
                        if (empty($captain_email)) $errors[] = "Captain email is required";
                        if (empty($players_count)) $errors[] = "Number of players is required";

                        if (empty($errors)) {
                            // Insert into tournament_registration table
                            $insert_sql = "INSERT INTO tourn_reg (tr_id, team_name, cap_name, cap_phone, cap_email, players) 
                                         VALUES (?, ?, ?, ?, ?, ?)";
                            $insert_stmt = $conn->prepare($insert_sql);
                            $insert_stmt->bind_param("issssi", $tr_id, $team_name, $captain_name, $captain_phone, $captain_email, $players_count);
                            
                            if ($insert_stmt->execute()) {
                                echo '<div class="success-message">Registration successful! Your team has been registered.</div>';
                            } else {
                                echo '<div class="error-message">Registration failed. Please try again.</div>';
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

                    <input type="hidden" name="tr_id" value="<?php echo htmlspecialchars($tr_id); ?>">

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-users"></i> Team Name</label>
                        <div class="input-group">
                            <input type="text" name="team_name" class="form-input" placeholder="Enter your team name" required>
                            <i class="fas fa-shield-alt trailing-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user-tie"></i> Captain Name</label>
                        <div class="input-group">
                            <input type="text" name="captain_name" class="form-input" placeholder="Full name of team captain" required>
                            <i class="fas fa-id-badge trailing-icon"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Captain Phone Number
                            <div class="tooltip">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip-text">We'll use this as the primary contact for all tournament updates</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <input type="tel" name="captain_phone" class="form-input" placeholder="Captain's contact number" required>
                            <i class="fas fa-mobile-alt trailing-icon"></i>
                        </div>
                    </div>
            
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-envelope"></i> Captain Email</label>
                        <div class="input-group">
                            <input type="email" name="captain_email" class="form-input" placeholder="Captain's email address" required>
                            <i class="fas fa-at trailing-icon"></i>
                        </div>
                    </div>
            
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-friends"></i> Number of Players
                            <div class="tooltip">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip-text">Minimum 5, maximum 15 players allowed per team</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <input type="number" name="players_count" class="form-input" min="5" max="15" placeholder="Number of team members" required>
                            <i class="fas fa-users trailing-icon"></i>
                        </div>
                    </div>
            
                    <button type="submit" class="submit-button">
                        <i class="fas fa-paper-plane"></i> Complete Registration
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
    .success-message {
        background-color: rgba(0, 255, 0, 0.1);
        border: 1px solid #00ff00;
        color: #00ff00;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
    }

    .error-message {
        background-color: rgba(255, 0, 0, 0.1);
        border: 1px solid #ff0000;
        color: #ff0000;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
    }
    </style>

    <!-- Footer -->
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
</body>
</html>