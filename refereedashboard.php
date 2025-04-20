<?php
session_start();
include 'header.php'; 
include 'db.php';

// Check if referee is logged in
if (!isset($_SESSION['user_email']) || $_SESSION['user_type'] != 'referee') {
    header("Location: login.php");
    exit();
}

$current_date = date('Y-m-d');
$ref_email = $_SESSION['user_email'];

// Fetch referee profile
function fetchRefereeProfile($conn, $ref_email) {
    $query = "SELECT r.*, u.user_name 
              FROM referee r
              JOIN user u ON r.referee_email = u.email 
              WHERE r.referee_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ref_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $referee = $result->fetch_assoc();
    $stmt->close();
    return $referee;
}

// Fetch referee statistics
function fetchRefereeStats($conn, $ref_email) {
    $stats_sql = "SELECT 
        COUNT(DISTINCT b.booking_id) as matches_officiated,
        COUNT(DISTINCT MONTH(b.bk_date)) as months_active,
        COALESCE(r.charges, 0) as hourly_rate,
        COALESCE(r.charges * (SELECT COUNT(*) FROM book WHERE referee_email = ? AND status = 'confirmed'), 0) as total_earnings
    FROM referee r
    LEFT JOIN book b ON r.referee_email = b.referee_email
    WHERE r.referee_email = ? 
    AND b.bk_date <= CURDATE()
    GROUP BY r.charges";
    
    $stats_stmt = $conn->prepare($stats_sql);
    $stats_stmt->bind_param("ss", $ref_email, $ref_email);
    $stats_stmt->execute();
    $stats = $stats_stmt->get_result()->fetch_assoc();
    $stats_stmt->close();
    
    return $stats;
}

// Fetch upcoming matches
// Update the matches query
// Update the function signature to accept current_date
function fetchUpcomingMatches($conn, $ref_email, $current_date) {
    // Debug query to check if canceled bookings exist
    $debug_sql = "SELECT status, COUNT(*) as count FROM book WHERE referee_email = ? GROUP BY status";
    $debug_stmt = $conn->prepare($debug_sql);
    $debug_stmt->bind_param("s", $ref_email);
    $debug_stmt->execute();
    $debug_result = $debug_stmt->get_result();
    while($row = $debug_result->fetch_assoc()) {
        error_log("Status: " . $row['status'] . ", Count: " . $row['count']);
    }
    $debug_stmt->close();
    
    $matches_sql = "SELECT 
        b.booking_id,
        b.bk_date,
        b.bk_dur AS time_slot,
        v.venue_nm AS venue_name,
        v.location,
        b.venue_id,
        b.status,
        b.referee_email,
        r.charges
    FROM book b
    LEFT JOIN venue v ON b.venue_id = v.venue_id
    LEFT JOIN referee r ON r.referee_email = b.referee_email
    WHERE b.referee_email = ? 
    AND b.bk_date >= ?
    ORDER BY b.bk_date ASC";
  
    $matches_stmt = $conn->prepare($matches_sql);
    $matches_stmt->bind_param("ss", $ref_email, $current_date);
    $matches_stmt->execute();
    $matches_result = $matches_stmt->get_result();
    $matches_stmt->close();
    
    return $matches_result;
}

// Handle booking status updates
// Update the status handling function
function updateBookingStatus($conn, $booking_id, $ref_email, $action) {
    $new_status = ($action == 'accept') ? 'accepted' : 'declined';
    
    $update_sql = "UPDATE book SET status = ?, referee_email = ? WHERE booking_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $new_status, $ref_email, $booking_id);
    $update_stmt->execute();
    $update_stmt->close();
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['booking_id'])) {
        updateBookingStatus($conn, $_POST['booking_id'], $ref_email, $_POST['action']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch data
$referee = fetchRefereeProfile($conn, $ref_email);
$stats = fetchRefereeStats($conn, $ref_email);
$matches_result = fetchUpcomingMatches($conn, $ref_email, $current_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Referee Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <link rel="stylesheet" href="CSS/refereedashboard.css">
    <link rel="stylesheet" href="CSS/main.css">
</head>
<body>
    <div class="dashboard">
        <div class="welcome">
            <h1>Referee Dashboard</h1>
            <p>Manage your match assignments, track your schedule, and view performance metrics all in one place.</p>
        </div>
        
        <div class="bento-grid">
            <!-- Referee Profile Section -->
            <div class="bento-item referee-profile-section">
                <div class="bento-header">
                    <h2><i class="fas fa-user-tie section-icon"></i> Referee Profile</h2>
                    <button onclick="window.location.href='referee-edit-profile.php'" class="edit-button"><i class="fas fa-edit"></i> Edit Profile</button>
                </div>
                
                <div class="referee-detail-item">
                    <div class="referee-detail-icon"><i class="fas fa-id-card"></i></div>
                    <div class="referee-detail-content">
                        <div class="referee-detail-label">Name</div>
                        <div class="referee-detail-value"><?php echo htmlspecialchars($referee['user_name']); ?></div>
                    </div>
                </div>
                
                <div class="referee-detail-item">
                    <div class="referee-detail-icon"><i class="fas fa-medal"></i></div>
                    <div class="referee-detail-content">
                        <div class="referee-detail-label">Experience</div>
                        <div class="referee-detail-value"><?php echo htmlspecialchars($referee['yrs_exp']); ?> years</div>
                    </div>
                </div>
                
                <div class="referee-detail-item">
                    <div class="referee-detail-icon"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                    <div class="referee-detail-content">
                        <div class="referee-detail-label">Hourly Rate</div>
                        <div class="referee-detail-value">₹<?php echo number_format($referee['charges'], 2); ?></div>
                    </div>
                </div>

                <div class="referee-detail-item">
                    <div class="referee-detail-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="referee-detail-content">
                        <div class="referee-detail-label">Location</div>
                        <div class="referee-detail-value"><?php echo htmlspecialchars($referee['ref_location']); ?></div>
                    </div>
                </div>

                <div class="referee-detail-item">
                    <div class="referee-detail-icon"><i class="fa-solid fa-image"></i></div>
                    <div class="referee-detail-content">
                        <div class="referee-detail-label">Picture</div>
                        <div class="referee-detail-value">
                            <img src="images/referee/<?php echo htmlspecialchars($referee['ref_pic']); ?>" alt="Referee" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-top: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        </div>
                    </div>
                </div>
               
                <button onclick="window.location.href='logout.php'" class="signout-button">
                    <i class="fas fa-sign-out-alt"></i> Sign Out
                </button>
            </div>
            
            <!-- Dashboard Stats -->
            <div class="bento-item dashboard-stats">
                <div class="bento-header">
                    <h2><i class="fas fa-chart-line section-icon"></i> Performance Overview</h2>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number animate-number"><?php echo $stats['matches_officiated']; ?></div>
                        <div class="stat-label">Matches Officiated</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number animate-number"><?php echo $stats['months_active']; ?></div>
                        <div class="stat-label">Active Months</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number animate-number"><?php echo $matches_result->num_rows; ?></div>
                        <div class="stat-label">Upcoming Matches</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number animate-number">₹<?php echo number_format($stats['total_earnings'], 2); ?></div>
                        <div class="stat-label">Total Earnings</div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Matches Section -->
            <div class="bento-item upcoming-matches">
                <div class="bento-header">
                    <h2><i class="fas fa-calendar-alt section-icon"></i> Upcoming Matches</h2>
                </div>
                
                <div class="match-list">
                    <?php if($matches_result->num_rows > 0): ?>
                        <?php 
                        // Debug output
                        echo "<!-- Total matches: " . $matches_result->num_rows . " -->";
                        ?>
                        <?php while($match = $matches_result->fetch_assoc()): ?>
                            <?php 
                            // Debug output for each match
                            echo "<!-- Match ID: " . $match['booking_id'] . ", Status: " . $match['status'] . " -->";
                            ?>
                            <div class="match-card">
                                <div class="match-header">
                                    <div class="match-date">
                                        <?php 
                                        $match_date = new DateTime($match['bk_date']);
                                        $today = new DateTime(date('Y-m-d'));
                                        $tomorrow = new DateTime('tomorrow');
                                        
                                        if($match_date->format('Y-m-d') == $today->format('Y-m-d')) {
                                            echo "Today";
                                        } elseif($match_date->format('Y-m-d') == $tomorrow->format('Y-m-d')) {
                                            echo "Tomorrow";
                                        } else {
                                            echo $match_date->format('d M, Y');
                                        }
                                        
                                        echo " " . $match['time_slot'];
                                        ?>
                                    </div>
                                    
                                    <?php 
                                    // Force lowercase for comparison and print raw value for debugging
                                    $status = strtolower(trim($match['status']));
                                    
                                    switch($status) {
                                        case 'confirmed':
                                            $badge_class = 'badge-confirmed';
                                            break;
                                        case 'accepted':
                                            $badge_class = 'badge-accepted';
                                            break;
                                        case 'pending':
                                            $badge_class = 'badge-upcoming';
                                            break;
                                        case 'canceled':
                                        case 'cancelled':
                                            $badge_class = 'badge-canceled';
                                            break;
                                        default:
                                            $badge_class = 'badge-upcoming';
                                    }
                                    ?>
                                    <div class="badge <?php echo $badge_class; ?>">
                                        <?php echo strtoupper($match['status']); ?>
                                    </div>
                                </div>
                                
                                <div class="match-teams"><?php echo htmlspecialchars($match['venue_name']); ?></div>
                                
                                <div class="match-location">
                                    <i class="fas fa-map-marker-alt" style="color: #c4ff3c;"></i>
                                    <?php echo htmlspecialchars($match['location']); ?>
                                </div>
                                
                                <div>Time: <?php echo $match['time_slot']; ?></div>
                                <div>Payment: ₹<?php echo number_format($match['charges'], 2); ?></div>
                                
                                <?php if($match['status'] == 'pending' && (empty($match['referee_email']) || $match['referee_email'] == $ref_email)): ?>
                                    <div class="match-actions">
                                        <form method="post">
                                            <input type="hidden" name="booking_id" value="<?php echo $match['booking_id']; ?>">
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="match-button accept-btn">Accept Request</button>
                                        </form>
                                        
                                        <form method="post" onsubmit="return confirm('Are you sure you want to decline this request?');">
                                            <input type="hidden" name="booking_id" value="<?php echo $match['booking_id']; ?>">
                                            <input type="hidden" name="action" value="decline">
                                            <button type="submit" class="match-button decline-btn">Decline</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-matches-message">
                            <i class="fas fa-calendar-xmark"></i>
                            <p>You don't have any upcoming matches. Check back later!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

  <script>
    // Mobile menu toggle
    document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
      document.querySelector('.nav-links').classList.toggle('show');
    });

    // Animate numbers counting
    const statNumbers = document.querySelectorAll('.animate-number');
    
    function animateNumber(element) {
  // Remove any non-numeric characters except decimals for parsing
  const text = element.textContent;
  const numericValue = text.replace(/[^0-9.]/g, '');
  const target = parseFloat(numericValue);
  
  let count = 0;
  const duration = 2000; // 2 seconds
  const interval = Math.floor(duration / target);
  
  const counter = setInterval(() => {
    count += 1;
    
    // If original text had a currency symbol, keep it in the display
    if (text.includes('₹')) {
      element.textContent = '₹' + count;
    } else {
      element.textContent = count;
    }
    
    if (count >= target) {
      clearInterval(counter);
      element.textContent = text; // Reset to original formatted text
    }
  }, interval);
}

    // Observe when elements come into view
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateNumber(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    statNumbers.forEach(number => {
      observer.observe(number);
    });

    // Chart animation
    const chartBars = document.querySelectorAll('.chart-bar');
    
    chartBars.forEach(bar => {
      const height = bar.style.height;
      bar.style.height = '0';
      
      setTimeout(() => {
        bar.style.height = height;
      }, 300);
    });

    // Match action buttons
    const acceptButtons = document.querySelectorAll('.accept-btn');
    const declineButtons = document.querySelectorAll('.decline-btn');
    
    acceptButtons.forEach(button => {
      button.addEventListener('click', function() {
        const matchCard = this.closest('.match-card');
        const badge = matchCard.querySelector('.badge');
        badge.className = 'badge badge-confirmed';
        badge.textContent = 'CONFIRMED';
        this.parentNode.style.display = 'none';
      });
    });
    
    declineButtons.forEach(button => {
      button.addEventListener('click', function() {
        const matchCard = this.closest('.match-card');
        matchCard.style.opacity = '0.5';
        setTimeout(() => {
          matchCard.style.display = 'none';
        }, 500);
      });
    });

    // Match card status badge
    // Remove the PHP code that was incorrectly placed here
    
   

    // Sign out button
    document.querySelector('.signout-button').addEventListener('click', function() {
      if (confirm('Are you sure you want to sign out?')) {
        window.location.href = 'logout.php';
      }
    });

  </script>
</body>
</html>