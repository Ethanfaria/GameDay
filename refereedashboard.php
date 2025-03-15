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
  <link rel="stylesheet" href="CSS\refereedashboard.css">
  <link rel="stylesheet" href="CSS\main.css">
  <style>
    .dashboard {
      padding: 2rem;
      max-width: 1400px;
      margin: 0 auto;
    }

    .welcome {
      margin-bottom: 2rem;
      animation: fade-in 1s ease;
    }

    @keyframes fade-in {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .welcome h1 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }

    .welcome p {
      color: #a9a9a9;
    }

    .bento-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      animation: scale-up 0.5s ease;
    }

    @keyframes scale-up {
      from {
        transform: scale(0.95);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    .bento-item {
      background-color: #1a461f;
      border-radius: 1rem;
      padding: 1.5rem;
      overflow: hidden;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
      animation: pop-in 0.5s ease;
      animation-fill-mode: both;
    }

    .bento-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
    }

    @keyframes pop-in {
      0% {
        opacity: 0;
        transform: scale(0.9);
      }
      70% {
        transform: scale(1.02);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    .bento-item:nth-child(1) { animation-delay: 0.1s; }
    .bento-item:nth-child(2) { animation-delay: 0.2s; }
    .bento-item:nth-child(3) { animation-delay: 0.3s; }
    .bento-item:nth-child(4) { animation-delay: 0.4s; }
    .bento-item:nth-child(5) { animation-delay: 0.5s; }
    .bento-item:nth-child(6) { animation-delay: 0.6s; }

    .bento-item h2 {
      margin-bottom: 1rem;
      color: #c4ff3c;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .bento-item p {
      margin-bottom: 1rem;
    }

    .section-icon {
      color: #c4ff3c;
      margin-right: 0.5rem;
    }

    .referee-profile-section {
      grid-column: span 2;
    }

    .dashboard-stats {
      grid-column: span 1;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .stat-item {
      background-color: #276e2f;
      border-radius: 0.5rem;
      padding: 1rem;
      text-align: center;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: bold;
      color: #c4ff3c;
      display: inline-block;
      position: relative;
    }

    .animate-number {
      animation: count-up 2s ease-out forwards;
    }

    @keyframes count-up {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .referee-detail-item {
      background-color: #276e2f;
      padding: 1rem;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .referee-detail-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .referee-detail-icon {
      width: 40px;
      height: 40px;
      background-color: #1a461f;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-right: 1rem;
      color: #c4ff3c;
    }

    .referee-detail-content {
      flex: 1;
    }

    .referee-detail-label {
      font-size: 0.8rem;
      color: #a9a9a9;
      margin-bottom: 0.2rem;
    }

    .referee-detail-value {
      font-weight: bold;
      font-size: 1.1rem;
    }

    .availability-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .day-availability {
      background-color: #276e2f;
      padding: 0.8rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }

    .day-availability:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .day-name {
      font-weight: bold;
      margin-bottom: 0.3rem;
      color: #c4ff3c;
    }

    .day-hours {
      font-size: 0.9rem;
    }

    .earnings-analytics {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .bento-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid #276e2f;
    }

    .edit-button {
      color: #c4ff3c;
      font-size: 0.9rem;
      background-color: #276e2f;
      padding: 0.4rem 0.8rem;
      border-radius: 0.3rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.3rem;
    }

    .edit-button:hover {
      background-color: #307d38;
    }

    .status-badge {
      display: inline-block;
      padding: 0.2rem 0.5rem;
      border-radius: 0.3rem;
      font-size: 0.8rem;
      font-weight: bold;
      margin-right: 0.5rem;
    }

    .status-active {
      background-color: #c4ff3c;
      color: #000;
    }

    .earnings-chart {
      height: 200px;
      background-color: #276e2f;
      border-radius: 0.5rem;
      padding: 1rem;
      position: relative;
      overflow: hidden;
    }

    .chart-bar {
      position: absolute;
      bottom: 0;
      width: 12%;
      background-color: #c4ff3c;
      border-radius: 5px 5px 0 0;
      transition: height 1s ease;
    }

    .chart-bar:nth-child(1) { left: 5%; height: 40%; animation-delay: 0.1s; }
    .chart-bar:nth-child(2) { left: 22%; height: 65%; animation-delay: 0.2s; }
    .chart-bar:nth-child(3) { left: 39%; height: 45%; animation-delay: 0.3s; }
    .chart-bar:nth-child(4) { left: 56%; height: 80%; animation-delay: 0.4s; }
    .chart-bar:nth-child(5) { left: 73%; height: 55%; animation-delay: 0.5s; }
    .chart-bar:hover {
      background-color: #d1ff4a;
    }

    .chart-labels {
      display: flex;
      justify-content: space-between;
      padding: 0 5%;
      margin-top: 0.5rem;
    }

    .chart-label {
      width: 12%;
      text-align: center;
      font-size: 0.8rem;
      color: #a9a9a9;
    }

    .signout-button {
      background-color: #3d0f0f;
      color: white;
      padding: 0.8rem 1rem;
      border-radius: 0.5rem;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      width: 100%;
      margin-top: 1rem;
      font-weight: bold;
    }

    .signout-button:hover {
      background-color: #5a1818;
      transform: translateY(-2px);
    }

    .upcoming-matches {
      grid-column: span 3;
    }

    .match-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1rem;
    }

    .match-card {
      background-color: #276e2f;
      border-radius: 0.5rem;
      padding: 1rem;
      position: relative;
      transition: all 0.3s ease;
    }

    .match-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .match-date {
      font-size: 0.8rem;
      color: #a9a9a9;
      margin-bottom: 0.5rem;
    }

    .match-teams {
      font-weight: bold;
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }

    .match-location {
      display: flex;
      align-items: center;
      gap: 0.3rem;
      margin-bottom: 0.5rem;
    }

    .match-actions {
      display: flex;
      gap: 0.5rem;
      margin-top: 0.5rem;
    }

    .match-button {
      padding: 0.5rem 0.8rem;
      border-radius: 0.3rem;
      font-size: 0.9rem;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      flex: 1;
    }

    .accept-btn {
      background-color: #c4ff3c;
      color: #0a2e1a;
    }

    .decline-btn {
      background-color: #3d0f0f;
      color: white;
    }

    .badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      padding: 0.3rem 0.6rem;
      border-radius: 0.3rem;
      font-size: 0.8rem;
      font-weight: bold;
    }

    .badge-upcoming {
      background-color: #276e2f;
      border: 1px solid #c4ff3c;
      color: #c4ff3c;
    }

    .badge-confirmed {
      background-color: #c4ff3c;
      color: #0a2e1a;
    }

    .certification-badge {
      display: inline-flex;
      align-items: center;
      background-color: #1a461f;
      padding: 0.3rem 0.5rem;
      border-radius: 0.3rem;
      margin-right: 0.5rem;
      margin-top: 0.5rem;
      border: 1px solid #c4ff3c;
    }

    .certification-badge i {
      margin-right: 0.3rem;
      color: #c4ff3c;
    }

    .rating-stars {
      display: flex;
      margin-top: 0.5rem;
    }

    .star {
      color: #ffc107;
      margin-right: 0.2rem;
    }

    .empty-star {
      color: #a9a9a9;
      margin-right: 0.2rem;
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .bento-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .dashboard-stats {
        grid-column: span 2;
      }
      .upcoming-matches {
        grid-column: span 2;
      }
    }

    @media (max-width: 768px) {
      .bento-grid {
        grid-template-columns: 1fr;
      }
      .referee-profile-section, .dashboard-stats, .upcoming-matches {
        grid-column: span 1;
      }
      .nav-links {
        display: none;
      }
      .mobile-menu-btn {
        display: block;
      }
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  <div class="dashboard">
    <div class="welcome">
      <h1>Referee Dashboard</h1>
      <p>Manage your match assignments, track your schedule, and view performance metrics all in one place.</p>
    </div>
    
    <div class="bento-grid">
      <div class="bento-item referee-profile-section">
        <div class="bento-header">
          <h2><i class="fas fa-user-tie section-icon"></i> Referee Profile</h2>
          <button class="edit-button"><i class="fas fa-edit"></i> Edit Profile</button>
        </div>
        
        <div class="referee-detail-item">
          <div class="referee-detail-icon">
            <i class="fas fa-id-card"></i>
          </div>
          <div class="referee-detail-content">
            <div class="referee-detail-label">Name</div>
            <div class="referee-detail-value">Michael Johnson <span class="status-badge status-active">ACTIVE</span></div>
          </div>
        </div>
        
        <div class="referee-detail-item">
          <div class="referee-detail-icon">
            <i class="fas fa-medal"></i>
          </div>
          <div class="referee-detail-content">
            <div class="referee-detail-label">Experience & Certifications</div>
            <div class="referee-detail-value">8 Years Experience</div>
            <div>
              <span class="certification-badge"><i class="fas fa-certificate"></i> FIFA Level 2</span>
              <span class="certification-badge"><i class="fas fa-certificate"></i> National License</span>
              <span class="certification-badge"><i class="fas fa-certificate"></i> Youth Certified</span>
            </div>
          </div>
        </div>
        
        <div class="referee-detail-item">
          <div class="referee-detail-icon">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="referee-detail-content">
            <div class="referee-detail-label">Hourly Rate</div>
            <div class="referee-detail-value">$30 Standard | $45 Tournaments | $50 Championship Games</div>
          </div>
        </div>
        
        <div class="referee-detail-item">
          <div class="referee-detail-icon">
            <i class="fas fa-star"></i>
          </div>
          <div class="referee-detail-content">
            <div class="referee-detail-label">Rating</div>
            <div class="referee-detail-value">4.8/5.0 (42 ratings)</div>
            <div class="rating-stars">
              <i class="fas fa-star star"></i>
              <i class="fas fa-star star"></i>
              <i class="fas fa-star star"></i>
              <i class="fas fa-star star"></i>
              <i class="fas fa-star-half-alt star"></i>
            </div>
          </div>
        </div>
        
        <div class="referee-detail-item">
          <div class="referee-detail-icon">
            <i class="fas fa-calendar-alt"></i>
          </div>
          <div class="referee-detail-content">
            <div class="referee-detail-label">Availability</div>
            <div class="availability-grid">
              <div class="day-availability">
                <div class="day-name">Monday</div>
                <div class="day-hours">06:00 PM - 09:00 PM</div>
              </div>
              <div class="day-availability">
                <div class="day-name">Tuesday</div>
                <div class="day-hours">Not Available</div>
              </div>
              <div class="day-availability">
                <div class="day-name">Wednesday</div>
                <div class="day-hours">06:00 PM - 09:00 PM</div>
              </div>
              <div class="day-availability">
                <div class="day-name">Thursday</div>
                <div class="day-hours">07:00 PM - 10:00 PM</div>
              </div>
              <div class="day-availability">
                <div class="day-name">Friday</div>
                <div class="day-hours">06:00 PM - 11:00 PM</div>
              </div>
              <div class="day-availability">
                <div class="day-name">Saturday</div>
                <div class="day-hours">10:00 AM - 08:00 PM</div>
              </div>
              <div class="day-availability">
                <div class="day-name">Sunday</div>
                <div class="day-hours">10:00 AM - 06:00 PM</div>
              </div>
            </div>
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
            <div class="stat-number animate-number">28</div>
            <div class="stat-label">Matches Officiated</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">4</div>
            <div class="stat-label">Tournaments</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">5</div>
            <div class="stat-label">Upcoming Matches</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">$840</div>
            <div class="stat-label">Monthly Earnings</div>
          </div>
        </div>
        
        <div class="earnings-analytics">
          <div>
            <h3 style="margin-bottom: 0.5rem; color: #c4ff3c;">Weekly Earnings</h3>
            <div class="earnings-chart">
              <div class="chart-bar"></div>
              <div class="chart-bar"></div>
              <div class="chart-bar"></div>
              <div class="chart-bar"></div>
              <div class="chart-bar"></div>
            </div>
            <div class="chart-labels">
              <div class="chart-label">Mon</div>
              <div class="chart-label">Tue</div>
              <div class="chart-label">Wed</div>
              <div class="chart-label">Thu</div>
              <div class="chart-label">Fri</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Upcoming Matches Section -->
      <div class="bento-item upcoming-matches">
        <div class="bento-header">
          <h2><i class="fas fa-calendar-alt section-icon"></i> Upcoming Matches</h2>
        </div>
        
        <div class="match-list">
          <div class="match-card">
            <div class="badge badge-confirmed">CONFIRMED</div>
            <div class="match-date">Today - 7:00 PM</div>
            <div class="match-teams">FC Strikers vs. United FC</div>
            <div class="match-location">
              <i class="fas fa-map-marker-alt" style="color: #c4ff3c;"></i>
              Green Valley Futsal
            </div>
            <div>Match Type: League Game</div>
            <div>Duration: 90 mins</div>
            <div>Payment: $45</div>
          </div>
          
          <div class="match-card">
            <div class="badge badge-upcoming">PENDING</div>
            <div class="match-date">Tomorrow - 6:30 PM</div>
            <div class="match-teams">Phoenix AC vs. Dynamo FC</div>
            <div class="match-location">
              <i class="fas fa-map-marker-alt" style="color: #c4ff3c;"></i>
              Downtown Sports Complex
            </div>
            <div>Match Type: Friendly</div>
            <div>Duration: 60 mins</div>
            <div>Payment: $30</div>
            <div class="match-actions">
              <button class="match-button accept-btn">Accept</button>
              <button class="match-button decline-btn">Decline</button>
            </div>
          </div>
          
          <div class="match-card">
            <div class="badge badge-confirmed">CONFIRMED</div>
            <div class="match-date">Saturday - 10:00 AM</div>
            <div class="match-teams">Youth Tournament - Group Stage</div>
            <div class="match-location">
              <i class="fas fa-map-marker-alt" style="color: #c4ff3c;"></i>
              City Sports Arena
            </div>
            <div>Match Type: Tournament</div>
            <div>Duration: 4 hours (multiple games)</div>
            <div>Payment: $120</div>
          </div>
          
          <div class="match-card">
            <div class="badge badge-upcoming">PENDING</div>
            <div class="match-date">Sunday - 2:00 PM</div>
            <div class="match-teams">Academy Finals - U16</div>
            <div class="match-location">
              <i class="fas fa-map-marker-alt" style="color: #c4ff3c;"></i>
              Elite Soccer Center
            </div>
            <div>Match Type: Championship</div>
            <div>Duration: 90 mins</div>
            <div>Payment: $50</div>
            <div class="match-actions">
              <button class="match-button accept-btn">Accept</button>
              <button class="match-button decline-btn">Decline</button>
            </div>
          </div>
          
          <div class="match-card">
            <div class="badge badge-upcoming">PENDING</div>
            <div class="match-date">Next Monday - 7:30 PM</div>
            <div class="match-teams">Corporate League: Tech Giants vs. Financial Group</div>
            <div class="match-location">
              <i class="fas fa-map-marker-alt" style="color: #c4ff3c;"></i>
              Business Park Arena
            </div>
            <div>Match Type: Corporate League</div>
            <div>Duration: 60 mins</div>
            <div>Payment: $35</div>
            <div class="match-actions">
              <button class="match-button accept-btn">Accept</button>
              <button class="match-button decline-btn">Decline</button>
            </div>
          </div>
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
      const target = parseInt(element.textContent);
      let count = 0;
      const duration = 2000; // 2 seconds
      const interval = Math.floor(duration / target);
      
      const counter = setInterval(() => {
        count += 1;
        element.textContent = count;
        
        if (count >= target) {
          clearInterval(counter);
          element.textContent = target;
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
        window.location.href = 'login.html';
      }
    });
  </script>
</body>
</html>