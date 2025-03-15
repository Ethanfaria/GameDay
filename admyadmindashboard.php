<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GAME DAY - Academy Admin Dashboard</title>
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

    .academy-details-section {
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
    
    .academy-detail-item {
      background-color: #276e2f;
      padding: 1rem;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .academy-detail-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .amenity-grid {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    .academy-detail-item-small {
      background-color: #276e2f;
      padding: 1rem;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width:33%
    }
    .academy-detail-icon {
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

    .academy-detail-content {
      flex: 1;
    }

    .academy-detail-label {
      font-size: 0.8rem;
      color: #a9a9a9;
      margin-bottom: 0.2rem;
    }

    .academy-detail-value {
      font-weight: bold;
      font-size: 1.1rem;
    }

    .schedule-row {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .class-session {
      background-color: #276e2f;
      padding: 0.8rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }

    .class-session:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .age-group {
      font-weight: bold;
      margin-bottom: 0.3rem;
      color: #c4ff3c;
    }

    .session-time {
      font-size: 0.9rem;
    }

    .revenue-analytics {
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

    .enrollment-chart {
      height: 200px;
      background-color: #276e2f;
      border-radius: 0.5rem;
      padding: 1rem;
      position: relative;
      overflow: hidden;
    }

    .coach-list {
      margin-top: 1rem;
    }

    .coach-item {
      display: flex;
      align-items: center;
      background-color: #276e2f;
      padding: 0.8rem;
      border-radius: 0.5rem;
      margin-bottom: 0.8rem;
      transition: all 0.3s ease;
    }

    .coach-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .coach-avatar {
      width: 40px;
      height: 40px;
      background-color: #1a461f;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-right: 1rem;
      color: #c4ff3c;
      font-size: 1.2rem;
    }

    .coach-info {
      flex: 1;
    }

    .coach-name {
      font-weight: bold;
      margin-bottom: 0.2rem;
    }

    .coach-role {
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

    /* Responsive */
    @media (max-width: 1200px) {
      .bento-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .dashboard-stats {
        grid-column: span 2;
      }
    }

    @media (max-width: 768px) {
      .bento-grid {
        grid-template-columns: 1fr;
      }
      .academy-details-section, .dashboard-stats {
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
     <?php include 'header.php';?>  
  <div class="dashboard">
    <div class="welcome">
      <h1>Academy Admin Dashboard</h1>
      <p>Manage your football academy programs, track student enrollments, and organize coaching sessions all in one place.</p>
    </div>
    
    <div class="bento-grid">
      <div class="bento-item academy-details-section">
        <div class="bento-header">
          <h2><i class="fas fa-graduation-cap section-icon"></i> Academy Details</h2>
          <button class="edit-button"><i class="fas fa-edit"></i> Edit Details</button>
        </div>
        
        <div class="academy-detail-item">
          <div class="academy-detail-icon">
            <i class="fas fa-signature"></i>
          </div>
          <div class="academy-detail-content">
            <div class="academy-detail-label">Academy Name</div>
            <div class="academy-detail-value">Pro Football Academy <span class="status-badge status-active">ACTIVE</span></div>
          </div>
        </div>
        
        <div class="academy-detail-item">
          <div class="academy-detail-icon">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <div class="academy-detail-content">
            <div class="academy-detail-label">Location</div>
            <div class="academy-detail-value">Green Valley Futsal, Main Sports Center, Downtown</div>
          </div>
        </div>
        
        <div class="academy-detail-item">
          <div class="academy-detail-icon">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="academy-detail-content">
            <div class="academy-detail-label">Monthly Fee</div>
            <div class="academy-detail-value">$80 Beginner | $120 Intermediate | $150 Advanced</div>
          </div>
        </div>
        <div class="amenity-grid">
          <div class="academy-detail-item-small">
            <div class="academy-detail-icon">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="academy-detail-content">
              <div class="academy-detail-label">Amenities</div>
              <div class="academy-detail-value">Experience</div>
            </div>
          </div>
          <div class="academy-detail-item-small">
            <div class="academy-detail-icon">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="academy-detail-content">
              <div class="academy-detail-label">Amenities</div>
              <div class="academy-detail-value">Experience</div>
            </div>
          </div>
          <div class="academy-detail-item-small">
            <div class="academy-detail-icon">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="academy-detail-content">
              <div class="academy-detail-label">Amenities</div>
              <div class="academy-detail-value">Experience</div>
            </div>
          </div>
        </div>
        

        <div class="academy-detail-item">
          <div class="academy-detail-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="academy-detail-content">
            <div class="academy-detail-label">Your Students</div>
            <div class="coach-list">
              <div class="coach-item">
                <div class="coach-avatar">
                  <i class="fas fa-user"></i>
                </div>
                <div class="coach-info">
                  <div class="coach-name">Carlos Reyes</div>
                  <div class="coach-role">Payment Due: 17th March 2025</div>
                </div>
              </div>
              <div class="coach-item">
                <div class="coach-avatar">
                  <i class="fas fa-user"></i>
                </div>
                <div class="coach-info">
                  <div class="coach-name">Maria Williams</div>
                  <div class="coach-role">Payment Due: 19th april 2025</div>
                </div>
              </div>
              <div class="coach-item">
                <div class="coach-avatar">
                  <i class="fas fa-user"></i>
                </div>
                <div class="coach-info">
                  <div class="coach-name">James Smith</div>
                  <div class="coach-role">Payment Due: 7th April 2025</div>
                </div>
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
            <div class="stat-number animate-number">87</div>
            <div class="stat-label">Students</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">4</div>
            <div class="stat-label">Programs</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">12</div>
            <div class="stat-label">Sessions/Week</div>
          </div>
          <div class="stat-item">
            <div class="stat-number animate-number">$9,850</div>
            <div class="stat-label">Monthly Revenue</div>
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
    // Simple animation for stat numbers
    document.addEventListener('DOMContentLoaded', function() {
      const numbers = document.querySelectorAll('.animate-number');
      
      numbers.forEach(number => {
        const value = number.innerText;
        number.innerText = '0';
        
        setTimeout(() => {
          animateValue(number, 0, value, 1500);
        }, 500);
      });
      
      function animateValue(obj, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
          if (!startTimestamp) startTimestamp = timestamp;
          const progress = Math.min((timestamp - startTimestamp) / duration, 1);
          
          // Handle currency values with dollar sign
          if (end.toString().includes('$')) {
            const numericValue = parseInt(end.toString().replace('$', ''));
            obj.innerText = '$' + Math.floor(progress * numericValue).toLocaleString();
          } else {
            obj.innerText = Math.floor(progress * end).toString();
          }
          
          if (progress < 1) {
            window.requestAnimationFrame(step);
          }
        };
        window.requestAnimationFrame(step);
      }
    });
  </script>
</body>
</html>