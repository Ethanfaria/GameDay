<?php
include 'header.php';
include 'db.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : null;

if (!$tr_id) {
    header("Location: tournaments.php"); // Redirect if no tournament ID provided
    exit();
}

// Fetch user details
$user_sql = "SELECT * FROM user WHERE email = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $_SESSION['user_email']);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Get tournament details using tr_id
$tournament_sql = "SELECT t.tr_id, t.tr_name, t.start_date, t.end_date, t.tr_time, 
                         t.entry_fee, t.description, t.img_url, t.players_per_team, t.max_teams,
                         t.prize, v.venue_nm, v.location 
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

$team_name_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team_nm = $_POST['team_name'] ?? '';

    // Validate form data
    if (empty($team_nm)) {
        $team_name_error = "Team name is required";
    } elseif (!preg_match('/^[a-zA-Z0-9 ]{3,50}$/', $team_nm)) {
        $team_name_error = "Team name must be 3-50 characters long and contain only letters, numbers, and spaces";
    }

    if (empty($team_name_error)) {
        // Store tournament and team details in session for payment page
        $_SESSION['tournament_id'] = $tr_id;
        $_SESSION['tournament_name'] = $tournament['tr_name'];
        $_SESSION['tournament_venue'] = $tournament['venue_nm'];
        $_SESSION['tournament_location'] = $tournament['location'];
        $_SESSION['tournament_fee'] = $tournament['entry_fee'];
        $_SESSION['tournament_date'] = $tournament['start_date'];
        $_SESSION['team_name'] = $team_nm;

        // Redirect to payment page instead of inserting into database
        header("Location: tournpayment.php");
        exit();
    }
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
                    
                    <div class="tournament-details-container">
                        <div class="tournament-details-left">
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
                                    <p><?php echo htmlspecialchars($tournament['tr_time']); ?></p>
                                </div>
                            </div>
                        </div>
                    
                        <div class="tournament-details-right">
                            <div class="tournament-detail">
                                <div class="detail-icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="detail-content">
                                    <h3>Entry Fee</h3>
                                    <p>₹<?php echo number_format($tournament['entry_fee']); ?></p>
                                </div>
                            </div>
                            
                            <div class="tournament-detail">
                                <div class="detail-icon">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="detail-content">
                                    <h3>Cash Prize</h3>
                                    <p>₹<?php echo number_format($tournament['prize'], 2); ?></p>
                                </div>
                            </div>
                            
                            <div class="tournament-detail">
                                <div class="detail-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="detail-content">
                                    <h3>Team Composition</h3>
                                    <p><?php echo htmlspecialchars($tournament['players_per_team']); ?> Players per Team</p>
                                </div>
                            </div>
                            
                            <div class="tournament-detail">
                                <div class="detail-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="detail-content">
                                    <h3>Max Participants</h3>
                                    <p><?php echo htmlspecialchars($tournament['max_teams']); ?> Teams</p>
                                </div>
                            </div>
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
                    if (!empty($team_name_error)) {
                        echo '<div class="error-message">' . htmlspecialchars($team_name_error) . '</div>';
                    }
                    ?>

                    <input type="hidden" name="tr_id" value="<?php echo htmlspecialchars($tr_id); ?>">
                    
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-users"></i> Team Name</label>
                        <div class="input-group">
                            <input type="text" name="team_name" class="form-input" placeholder="Enter your team name" required value="<?php echo isset($_POST['team_name']) ? htmlspecialchars($_POST['team_name']) : ''; ?>">
                            <i class="fas fa-shield-alt trailing-icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="submit-button">
                        <i class="fas fa-paper-plane"></i> Complete Registration
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputFields = ['team_name'];
        
        inputFields.forEach(fieldId => {
            const field = document.querySelector(`[name="${fieldId}"]`);
            
            // Create requirements div if it doesn't exist
            let requirementsDiv = document.createElement('div');
            requirementsDiv.className = 'requirements';
            field.parentNode.insertBefore(requirementsDiv, field.nextSibling);
            
            // Validate on input
            field.addEventListener('input', function() {
                validateField(field, requirementsDiv);
                requirementsDiv.classList.add('show');
            });
        });

        // Form submission validation
        const registrationForm = document.getElementById('registrationForm');
        if (registrationForm) {
            registrationForm.addEventListener('submit', function(event) {
                const errorMessages = [];
                
                // Validate Team Name
                const teamName = document.querySelector('[name="team_name"]');
                if (!validateTeamName(teamName.value)) {
                    errorMessages.push("Team name must be 3-50 characters long and contain only letters, numbers, and spaces");
                    teamName.classList.add('input-error');
                } else {
                    teamName.classList.remove('input-error');
                }
                
                // If there are errors, prevent form submission and display them
                if (errorMessages.length > 0) {
                    event.preventDefault();
                    displayErrorsAtTop(errorMessages);
                }
            });
        }
    });

    // Validation functions
    function validateTeamName(teamName) {
        return /^[a-zA-Z0-9 ]{3,50}$/.test(teamName);
    }

    // Generic field validation function
    function validateField(field, requirementsDiv) {
        const name = field.name;
        
        switch(name) {
            case 'team_name':
                requirementsDiv.innerHTML = `
                    <ul>
                        <li class="${field.value.length >= 3 && field.value.length <= 50 ? 'valid' : 'invalid'}">3-50 characters long</li>
                        <li class="${/^[a-zA-Z0-9 ]+$/.test(field.value) ? 'valid' : 'invalid'}">Only letters, numbers, and spaces allowed</li>
                    </ul>
                `;
                break;
        }
    }

    // Function to display errors at the top of the form
    function displayErrorsAtTop(errors) {
        // Clear previous error messages
        const existingErrors = document.querySelectorAll('.error');
        existingErrors.forEach(error => error.remove());
        
        // Get form header to insert errors after it
        const formHeader = document.querySelector('.form-header');
        
        // Create and insert error messages
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error';
        errorDiv.innerHTML = errors.map(error => `<p>${error}</p>`).join('');
        
        // Insert error div after form header
        formHeader.insertAdjacentElement('afterend', errorDiv);
        
        // Scroll to top to show errors
        window.scrollTo(0, 0);
    }
    </script>
</body>
</html>