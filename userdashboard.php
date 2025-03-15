<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GAME DAY - User Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <link rel="stylesheet" href="CSS\userdashboard.css">
  <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
  <?php
  include 'header.php'; 
  include 'db.php';

  // Make sure user_email is defined
  $user_email = $_SESSION['email'] ?? '';

  // Query to fetch upcoming bookings with venue details
  $bookings_sql = "SELECT b.*, v.venue_nm, v.location 
          FROM book b 
          LEFT JOIN venue v ON b.venue_id = v.venue_id 
          WHERE b.email = ? AND b.bk_date >= CURDATE() 
          ORDER BY b.bk_date ASC
          LIMIT 3";

  $bookings_stmt = $conn->prepare($bookings_sql);
  $bookings_stmt->bind_param("s", $user_email);
  $bookings_stmt->execute();
  $bookings_result = $bookings_stmt->get_result();

  // Get user stats
  $stats_sql = "SELECT 
                COUNT(DISTINCT booking_id) as matches_played,
                COUNT(DISTINCT venue_id) as grounds_visited
              FROM book 
              WHERE email = ? AND bk_date <= CURDATE()";
  $stats_stmt = $conn->prepare($stats_sql);
  $stats_stmt->bind_param("s", $user_email);
  $stats_stmt->execute();
  $stats = $stats_stmt->get_result()->fetch_assoc();
  $stats_stmt->close();

  // Get academy enrollments
  $academy_sql = "SELECT a.aca_nm, a.ac_location, a.admy_img, e.en_date 
                  FROM enroll e
                  JOIN academys a ON e.ac_id = a.ac_id
                  WHERE e.email = ?
                  LIMIT 2";
  $academy_stmt = $conn->prepare($academy_sql);
  $academy_stmt->bind_param("s", $user_email);
  $academy_stmt->execute();
  $academy_result = $academy_stmt->get_result();

  // Get nearest upcoming tournament
  $next_tournament_sql = "SELECT tr_name, start_date, end_date, img_url,
                         DATEDIFF(start_date, CURDATE()) as days_left
                         FROM tournaments
                         WHERE start_date > CURDATE()
                         ORDER BY start_date ASC
                         LIMIT 1";
  $next_tournament_result = $conn->query($next_tournament_sql);
  $next_tournament = $next_tournament_result->fetch_assoc();

  $registered_tournaments_sql = "SELECT t.tr_name, t.start_date 
                             FROM register r
                             JOIN tournaments t ON r.tr_id = t.tr_id
                             WHERE r.email = ? AND t.start_date > CURDATE()
                             ORDER BY t.start_date ASC
                             LIMIT 1";
  $registered_stmt = $conn->prepare($registered_tournaments_sql);
  $registered_stmt->bind_param("s", $user_email);
  $registered_stmt->execute();
  $registered_tournament = $registered_stmt->get_result()->fetch_assoc();
  $registered_stmt->close();
    ?>
  
  <div class="dashboard">
    <div class="welcome">
      <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Player'); ?>!</h1>
      <p>Manage your futsal bookings and schedule from your personal dashboard.</p>
    </div>
    
    <div class="bento-grid">
      <div class="bento-item upcoming-matches">
        <div class="bento-header">
          <h2><i class="fas fa-futbol section-icon"></i> Upcoming Matches</h2>
        </div>
        <div class="scroll-container">
        <?php
if ($bookings_result->num_rows > 0) {
    while($row = $bookings_result->fetch_assoc()) {
        $booking_date = new DateTime($row['bk_date']);
        $today = new DateTime('today');
        $date_badge = $booking_date == $today ? 'today-badge' : '';
        $formatted_date = $booking_date == $today ? 'TODAY' : $booking_date->format('M d');
        
        // Just display the duration string directly since it's already in the format "10:00 AM - 11:00 AM"
        $time_range = $row['bk_dur'];
        ?>
        <div class="match-item">
          <div class="match-info">
            <div><span class="date-badge <?php echo $date_badge; ?>"><?php echo $formatted_date; ?></span> <?php echo $time_range; ?></div>
            <div class="match-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['venue_nm']); ?></div>
          </div>
        </div>
          <?php
              }
          } else {
              echo "<p>No upcoming matches</p>";
          }
          $bookings_stmt->close();
          ?>
        </div>
        <div class="section-footer">
          <span class="view-all">View all</span>
        </div>
      </div>
      
      <div class="bento-item stats">
        <div class="bento-header">
          <h2><i class="fas fa-chart-line section-icon"></i> Your Stats</h2>
        </div>
        <div class="stats-grid">
          <div class="stat-item">
            <div class="stat-number animate-number"><?php echo $stats['matches_played'] ?? 0; ?></div>
            <div class="stat-label">Matches Played</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number"><?php echo $stats['grounds_visited'] ?? 0; ?></div>
            <div class="stat-label">Grounds Visited</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number"><?php echo $stats['total_hours'] ?? 0; ?></div>
            <div class="stat-label">Hours Played</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">8</div>
            <div class="stat-label">Teammates</div>
          </div>
        </div>
        
        <?php if ($next_tournament): ?>
        <div class="countdown">
          <div style="color: #a9a9a9;"><?php echo htmlspecialchars($next_tournament['tr_name']); ?></div>
          <div class="countdown-number"><?php echo $next_tournament['days_left']; ?></div>
          <div>Days Left</div>
        </div>
        <?php endif; ?>
      </div>
      
      <div class="bento-item academy-enrollments">
        <div class="bento-header">
          <h2><i class="fas fa-graduation-cap section-icon"></i> Enrolled Academies</h2>
        </div>
        <?php
        if ($academy_result->num_rows > 0) {
            while($academy = $academy_result->fetch_assoc()) {
        ?>
        <div class="academy-item">
          <div class="academy-info">
            <div><?php echo htmlspecialchars($academy['aca_nm']); ?></div>
            <div class="academy-location"><i class="far fa-clock"></i><?php echo htmlspecialchars($academy['ac_location']); ?></div>
          </div>
        </div>
        <?php
            }
        } else {
            echo "<p>No academy enrollments</p>";
        }
        $academy_stmt->close();
        ?>
      </div>
      
      <div class="bento-item tournament-registration">
        <div class="bento-header">
          <h2><i class="fas fa-trophy section-icon"></i> Tournament Registration</h2>
        </div>
        <?php if ($registered_tournament): ?>
<div class="tournament-item">
  <div class="tournament-info">
    <div><?php echo htmlspecialchars($registered_tournament['tr_name']); ?></div>
    <div class="tournament-location"><i class="far fa-calendar-alt"></i> Starting <?php echo date('M d, Y', strtotime($registered_tournament['start_date'])); ?></div>
  </div>
</div>
<?php else: ?>
<p>No tournament registrations</p>
<?php endif; ?>
      </div>
      
      <div class="bento-item profile-actions">
        <div class="bento-header">
          <h2><i class="fas fa-user-circle section-icon"></i> Profile Actions</h2>
        </div>
        <div class="quick-action">
          <div class="icon-placeholder"><i class="fas fa-user-edit"></i></div>
          <div>Edit Profile</div>
        </div>
        <div class="quick-action">
          <div class="icon-placeholder"><i class="fas fa-user-plus"></i></div>
          <div>Invite Friends</div>
        </div>
        <div class="quick-action">
          <div class="icon-placeholder"><i class="fas fa-star"></i></div>
          <div>Rate & Review Turf</div>
        </div>
        <div class="quick-action">
          <div class="icon-placeholder"><i class="fas fa-graduation-cap"></i></div>
          <div>Rate & Review Academy</div>
        </div>
        <button onclick="window.location.href='logout.php'" class="signout-button">
          <i class="fas fa-sign-out-alt"></i> Sign Out
        </button>
      </div>
      
      <div class="bento-item booking-history">
        <div class="bento-header">
          <h2><i class="fas fa-history section-icon"></i> Booking History</h2>
        </div>
        <div class="scroll-container">
        <?php
          // Get past bookings
          $history_sql = "SELECT b.*, v.venue_nm, v.location 
                        FROM book b 
                        LEFT JOIN venue v ON b.venue_id = v.venue_id 
                        WHERE b.email = ? AND b.bk_date < CURDATE() 
                        ORDER BY b.bk_date DESC
                        LIMIT 3";
          $history_stmt = $conn->prepare($history_sql);
          $history_stmt->bind_param("s", $user_email);
          $history_stmt->execute();
          $history_result = $history_stmt->get_result();

          if ($history_result->num_rows > 0) {
              while($row = $history_result->fetch_assoc()) {
                  $booking_date = new DateTime($row['bk_date']);
          ?>
          <div class="booking-item">
            <div class="booking-info">
              <div><span class="date-badge"><?php echo $booking_date->format('M d'); ?></span></div>
              <div class="booking-location"><i class="fas fa-map-marker-alt"></i><?php echo htmlspecialchars($row['venue_nm']) . ' - ' . htmlspecialchars($row['location']); ?></div>
            </div>
            <div class="booking-actions">
              <button class="action-button primary" onclick="window.location.href='book.php?venue_id=<?php echo $row['venue_id']; ?>'"><i class="fas fa-redo"></i> Book Again</button>
            </div>
          </div>
          <?php
              }
          } else {
              echo "<p>No booking history</p>";
          }
          $history_stmt->close();
          ?>
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
    // Add some simple animations when the page loads
    document.addEventListener('DOMContentLoaded', function() {
      // Animate the countdown number
      const countdownEl = document.querySelector('.countdown-number');
      if (countdownEl) {
        const startValue = parseInt(countdownEl.textContent);
      }
    });
  </script>
</body>
</html>