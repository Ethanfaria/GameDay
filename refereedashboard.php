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
function fetchRefereeProfile($conn, $email) {
    $query = "SELECT * FROM referee WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $referee = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $referee;
}

// Fetch referee statistics
function fetchRefereeStats($conn, $email) {
  // Modify the query to use existing columns
  $stats_sql = "SELECT 
      COUNT(DISTINCT r.ref_id) as matches_officiated,
      COUNT(DISTINCT MONTH(b.bk_date)) as months_active,
      COALESCE(r.charges, 0) as hourly_rate,
      COALESCE(r.charges * COUNT(DISTINCT b.booking_id), 0) as total_earnings
  FROM referee r
  LEFT JOIN book b ON r.email = ?
  WHERE r.email = ? 
  AND b.bk_date <= CURDATE()";
  
  $stats_stmt = $conn->prepare($stats_sql);
  $stats_stmt->bind_param("ss", $email, $email);
  $stats_stmt->execute();
  $stats = $stats_stmt->get_result()->fetch_assoc();
  $stats_stmt->close();
  
  return $stats;
}

// Fetch upcoming matches
function fetchUpcomingMatches($conn, $email) {
  $matches_sql = "SELECT 
      b.booking_id,
      b.bk_date,
      b.bk_dur AS time_slot,
      v.venue_nm AS venue_name,
      v.location,
      b.venue_id,
      'pending' AS status,
      r.charges
  FROM book b
  LEFT JOIN venue v ON b.venue_id = v.venue_id
  LEFT JOIN referee r ON r.email = ?
  WHERE b.bk_date >= ?
  ORDER BY b.bk_date ASC";

  $matches_stmt = $conn->prepare($matches_sql);
  $matches_stmt->bind_param("ss", $email, $current_date);
  $matches_stmt->execute();
  $matches_result = $matches_stmt->get_result();
  $matches_stmt->close();
  
  return $matches_result;
}

// Handle booking status updates
function updateBookingStatus($conn, $booking_id, $email, $action) {
    $new_status = ($action == 'accept') ? 'confirmed' : 'declined';
    
    $update_sql = "UPDATE book SET status = ? WHERE booking_id = ? AND referee_email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sis", $new_status, $booking_id, $email);
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
$matches_result = fetchUpcomingMatches($conn, $ref_email);
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
                    <button class="edit-button"><i class="fas fa-edit"></i> Edit Profile</button>
                </div>
                
                <div class="referee-detail-item">
                    <div class="referee-detail-icon"><i class="fas fa-id-card"></i></div>
                    <div class="referee-detail-content">
                        <div class="referee-detail-label">Name</div>
                        <div class="referee-detail-value"><?php echo htmlspecialchars($referee['ref_name']); ?></div>
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
                
                <button class="signout-button">
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
                        <?php while($match = $matches_result->fetch_assoc()): ?>
                            <div class="match-card">
                                <?php 
                                $badge_class = '';
                                switch($match['status']) {
                                    case 'confirmed':
                                        $badge_class = 'badge-confirmed';
                                        break;
                                    case 'pending':
                                        $badge_class = 'badge-upcoming';
                                        break;
                                    default:
                                        $badge_class = 'badge-upcoming';
                                }
                                ?>
                                <div class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($match['status']); ?></div>
                                
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
                                
                                <div class="match-teams"><?php echo htmlspecialchars($match['venue_name']); ?></div>
                                
                                <div class="match-location">
                                    <i class="fas fa-map-marker-alt" style="color: #c4ff3c;"></i>
                                    <?php echo htmlspecialchars($match['location']); ?>
                                </div>
                                
                                <div>Duration: <?php echo $match['duration']; ?> mins</div>
                                <div>Payment: ₹<?php echo number_format($match['charges'], 2); ?></div>
                                
                                <?php if($match['status'] == 'pending'): ?>
                                    <div class="match-actions">
                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="booking_id" value="<?php echo $match['booking_id']; ?>">
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="match-button accept-btn">Accept</button>
                                        </form>
                                        
                                        <form method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to decline this match?');">
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

    // Edit profile button
    document.querySelector('.edit-button').addEventListener('click', function() {
      alert('Profile edit feature coming soon!');
    });

    // Sign out button
    document.querySelector('.signout-button').addEventListener('click', function() {
      if (confirm('Are you sure you want to sign out?')) {
        window.location.href = 'login.php';
      }
    });
  </script>
</body>
</html>