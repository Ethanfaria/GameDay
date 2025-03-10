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
  <?php include 'header.php'; ?>
  
  <div class="dashboard">
    <div class="welcome">
      <h1>Welcome back, Player!</h1>
      <p>Manage your futsal bookings and schedule from your personal dashboard.</p>
    </div>
    
    <div class="bento-grid">
      <div class="bento-item upcoming-matches">
        <div class="bento-header">
          <h2><i class="fas fa-futbol section-icon"></i> Upcoming Matches</h2>
        </div>
        <div class="scroll-container">
          <div class="match-item">
            <div class="match-info">
              <div><span class="date-badge today-badge">TODAY</span> 6:00 PM - 7:30 PM</div>
              <div class="match-location"><i class="fas fa-map-marker-alt"></i> Turf City Grounds - Court 3</div>
            </div>
          </div>
          <div class="match-item">
            <div class="match-info">
              <div><span class="date-badge">MAR 10</span> 7:00 PM - 8:30 PM</div>
              <div class="match-location"><i class="fas fa-map-marker-alt"></i> Kickoff Arena - Field 2</div>
            </div>
          </div>
          <div class="match-item">
            <div class="match-info">
              <div><span class="date-badge">MAR 15</span> 5:30 PM - 7:00 PM</div>
              <div class="match-location"><i class="fas fa-map-marker-alt"></i> Sports Hub - Indoor Court</div>
            </div>
          </div>
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
            <div class="stat-number animate-number">17</div>
            <div class="stat-label">Matches Played</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">5</div>
            <div class="stat-label">Grounds Visited</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">23</div>
            <div class="stat-label">Hours Played</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">8</div>
            <div class="stat-label">Teammates</div>
          </div>
        </div>
        
        <div class="countdown">
          <div style="color: #a9a9a9;">City League Tournament</div>
          <div class="countdown-number">12</div>
          <div>Days Left</div>
        </div>
      </div>
      
      <div class="bento-item academy-enrollments">
        <div class="bento-header">
          <h2><i class="fas fa-graduation-cap section-icon"></i> Enrolled Academies</h2>
        </div>
        <div class="academy-item">
          <div class="academy-info">
            <div>Elite Futsal Academy</div>
            <div class="academy-location"><i class="far fa-clock"></i> Mondays & Wednesdays, 6:00 PM</div>
          </div>
        </div>
        <div class="academy-item">
          <div class="academy-info">
            <div>Youth Skills Development</div>
            <div class="academy-location"><i class="far fa-clock"></i> Saturdays, 10:00 AM</div>
          </div>
        </div>
        <div class="section-footer">
          <span class="view-all">View all</span>
        </div>
      </div>
      
      <div class="bento-item tournament-registration">
        <div class="bento-header">
          <h2><i class="fas fa-trophy section-icon"></i> Tournament Registration</h2>
        </div>
        <div class="tournament-item">
          <div class="tournament-info">
            <div>City League Tournament</div>
            <div class="tournament-location"><i class="far fa-calendar-alt"></i> Starting Mar 20, 2025</div>
          </div>
        </div>
        <div class="tournament-item">
          <div class="tournament-info">
            <div>Weekend Championship</div>
            <div class="tournament-location"><i class="far fa-calendar-alt"></i> Apr 5-6, 2025</div>
          </div>
        </div>
        <div class="section-footer">
          <span class="view-all">View all</span>
        </div>
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
          <div class="booking-item">
            <div class="booking-info">
              <div><span class="date-badge">FEB 28</span> 6:00 PM</div>
              <div class="booking-location"><i class="fas fa-map-marker-alt"></i> Turf City Grounds - Court 1</div>
            </div>
            <div class="booking-actions">
              <button class="action-button primary"><i class="fas fa-redo"></i> Book Again</button>
            </div>
          </div>
          <div class="booking-item">
            <div class="booking-info">
              <div><span class="date-badge">FEB 21</span> 7:30 PM</div>
              <div class="booking-location"><i class="fas fa-map-marker-alt"></i> Kickoff Arena - Field 1</div>
            </div>
            <div class="booking-actions">
              <button class="action-button primary"><i class="fas fa-redo"></i> Book Again</button>
            </div>
          </div>
          <div class="booking-item">
            <div class="booking-info">
              <div><span class="date-badge">FEB 14</span> 5:00 PM</div>
              <div class="booking-location"><i class="fas fa-map-marker-alt"></i> Sports Hub - Indoor Court</div>
            </div>
            <div class="booking-actions">
              <button class="action-button primary"><i class="fas fa-redo"></i> Book Again</button>
            </div>
          </div>
        </div>
        <div class="section-footer">
          <span class="view-all">View all</span>
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
      const startValue = parseInt(countdownEl.textContent);
    });
  </script>
</body>
</html>