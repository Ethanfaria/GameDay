<?php
  session_start();
  include 'db.php';
  $userEmail = $_SESSION['user_email'];
  $userType = $_SESSION['user_type'];

  // Initialize variables
  $venueDetails = [];
  $bookings = [];
  $totalRevenue = 0;
  $totalBookings = 0;
  $totalTournaments = 0;
  $academyDetails = [];
  $students = [];
  $totalTournaments = 0;
  $totalStudents = 0;

  // Get venue details if user is an owner
  if ($userType == 'owner' || $userType == 'admin') {
      $venueQuery = "SELECT v.*, COUNT(b.booking_id) as booking_count, 
                    COUNT(DISTINCT t.tr_id) as tournament_count,
                    SUM(b.bk_dur LIKE '%AM%') as morning_bookings,
                    SUM(b.bk_dur LIKE '%PM%') as evening_bookings
                    FROM venue v
                    LEFT JOIN book b ON v.venue_id = b.venue_id
                    LEFT JOIN tournaments t ON v.venue_id = t.venue_id
                    WHERE v.owner_email = ?
                    GROUP BY v.venue_id";
      
      $stmt = $conn->prepare($venueQuery);
      $stmt->bind_param("s", $userEmail);
      $stmt->execute();
      $venueResult = $stmt->get_result();
      
      if ($venueResult->num_rows > 0) {
          // Store all venues in the array
          while ($venue = $venueResult->fetch_assoc()) {
              $venueDetails[] = $venue;
          }
          
          // Calculate total revenue
          $bookingQuery = "SELECT SUM(v.price) as total_revenue 
                          FROM book b 
                          JOIN venue v ON b.venue_id = v.venue_id 
                          WHERE v.owner_email = ?";
          $stmt = $conn->prepare($bookingQuery);
          $stmt->bind_param("s", $userEmail);
          $stmt->execute();
          $revenueResult = $stmt->get_result();
          
          if ($revenueResult->num_rows > 0) {
              $revenueData = $revenueResult->fetch_assoc();
              $totalRevenue = $revenueData['total_revenue'] ?? 0;
          }
          
          // Get recent bookings
          $recentBookingsQuery = "SELECT b.*, u.user_name, v.venue_nm 
                                FROM book b 
                                JOIN user u ON b.email = u.email 
                                JOIN venue v ON b.venue_id = v.venue_id 
                                WHERE v.owner_email = ? 
                                ORDER BY b.bk_date DESC LIMIT 5";
          $stmt = $conn->prepare($recentBookingsQuery);
          $stmt->bind_param("s", $userEmail);
          $stmt->execute();
          $bookingsResult = $stmt->get_result();
          
          while ($booking = $bookingsResult->fetch_assoc()) {
              $bookings[] = $booking;
          }
          
          // Calculate totals from all venues
          $totalBookings = 0;
          $totalTournaments = 0;
          foreach ($venueDetails as $venue) {
              $totalBookings += $venue['booking_count'];
              $totalTournaments += $venue['tournament_count'];
          }
      }
  }

  // Get academy details if user is an owner
  if ($userType == 'owner' || $userType == 'admin') {
      $academyQuery = "SELECT a.*, COUNT(e.email) as student_count, 
                      COUNT(DISTINCT t.tr_id) as tournament_count
                      FROM academys a
                      LEFT JOIN enroll e ON a.ac_id = e.ac_id
                      LEFT JOIN tournaments t ON a.ac_id = t.ac_id
                      WHERE a.owner_email = ?
                      GROUP BY a.ac_id";
      
      $stmt = $conn->prepare($academyQuery);
      $stmt->bind_param("s", $userEmail);
      $stmt->execute();
      $academyResult = $stmt->get_result();
      
      if ($academyResult->num_rows > 0) {
          while ($academy = $academyResult->fetch_assoc()) {
              $academyDetails[] = $academy;
          }
          
          $enrollmentQuery = "SELECT SUM(a.ac_charges) as total_revenue 
                            FROM enroll e 
                            JOIN academys a ON e.ac_id = a.ac_id 
                            WHERE a.owner_email = ?";
          $stmt = $conn->prepare($enrollmentQuery);
          $stmt->bind_param("s", $userEmail);
          $stmt->execute();
          $revenueResult = $stmt->get_result();
          
          if ($revenueResult->num_rows > 0) {
              $revenueData = $revenueResult->fetch_assoc();
              $academyRevenue = $revenueData['total_revenue'] ?? 0;
              $totalRevenue += $academyRevenue;
          }
          
          $studentsQuery = "SELECT e.*, u.user_name, u.user_ph 
                          FROM enroll e 
                          JOIN user u ON e.email = u.email 
                          JOIN academys a ON e.ac_id = a.ac_id 
                          WHERE a.owner_email = ? 
                          ORDER BY e.en_date DESC LIMIT 5";
          $stmt = $conn->prepare($studentsQuery);
          $stmt->bind_param("s", $userEmail);
          $stmt->execute();
          $studentsResult = $stmt->get_result();
          
          while ($student = $studentsResult->fetch_assoc()) {
              $students[] = $student;
          }
          
          $totalStudents = 0;
          foreach ($academyDetails as $academy) {
              $totalStudents += $academy['student_count'] ?? 0;
          }
      }
  }
$defaultDashboard = ($userType == 'owner' || $userType == 'admin') ? 'turf' : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GAME DAY - Admin Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <link rel="stylesheet" href="CSS/main.css">
  <link rel="stylesheet" href="CSS/dashboard.css">
</head>
<body>
  <?php 
  include 'header.php'; 
  ?>
  <div class="dashboard">
    <div class="welcome">
      <h1>Welcome, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?></h1>
      <p>Manage your futsal grounds, academies, track bookings, and more all in one place.</p>
    </div>
    
    <div class="dashboard-type-selector">
      <?php if ($userType == 'owner' || $userType == 'admin'): ?>
        <button class="dashboard-button <?php echo ($defaultDashboard == 'turf') ? 'active' : ''; ?>" onclick="switchDashboard('turf')">Turf Owner</button>
        <button class="dashboard-button <?php echo ($defaultDashboard == 'academy') ? 'active' : ''; ?>" onclick="switchDashboard('academy')">Academy Admin</button>
      <?php endif; ?>
    </div>
    
    <?php if ($userType == 'owner' || $userType == 'admin'): ?>
    <div id="turf-dashboard" class="dashboard-content <?php echo ($defaultDashboard == 'turf') ? 'active' : ''; ?>">
      <div class="bento-grid">
        <!-- Loop through all venues -->
        <?php foreach ($venueDetails as $venue): ?>
          <div class="bento-item details-section">
            <div class="bento-header">
              <h2><i class="fas fa-futbol section-icon"></i> Turf Details</h2>
              <button class="edit-button" onclick="openVenueEditModal(
                '<?php echo $venue['venue_id']; ?>',
                '<?php echo htmlspecialchars($venue['venue_nm']); ?>',
                '<?php echo htmlspecialchars($venue['location']); ?>',
                '<?php echo $venue['price']; ?>',
                '<?php echo htmlspecialchars($venue['size']); ?>',
                '<?php echo $venue['availability']; ?>',
                '<?php echo htmlspecialchars($venue['amenity1'] ?? ''); ?>',
                '<?php echo htmlspecialchars($venue['amenity2'] ?? ''); ?>',
                '<?php echo htmlspecialchars($venue['amenity3'] ?? ''); ?>'
            )">
                <i class="fas fa-edit"></i> Edit Details
            </button> 
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-signature"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Turf Name</div>
                <div class="detail-value">
                  <?php echo htmlspecialchars($venue['venue_nm']); ?>
                  <span class="status-badge <?php echo $venue['availability'] ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo $venue['availability'] ? 'ACTIVE' : 'INACTIVE'; ?>
                  </span>
                </div>
              </div>
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Location</div>
                <div class="detail-value"><?php echo htmlspecialchars($venue['location']); ?></div>
              </div>
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Per Hour Rate</div>
                <div class="detail-value">₹<?php echo number_format($venue['price']); ?></div>
              </div>
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-ruler-combined"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Turf Size</div>
                <div class="detail-value"><?php echo htmlspecialchars($venue['size']); ?></div>
              </div>
            </div>
            
            <div class="amenity-grid">
              <?php if (!empty($venue['amenity1'])): ?>
              <div class="detail-item-small">
                <div class="detail-icon">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="detail-content">
                  <div class="detail-value"><?php echo htmlspecialchars($venue['amenity1']); ?></div>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($venue['amenity2'])): ?>
              <div class="detail-item-small">
                <div class="detail-icon">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="detail-content">
                  <div class="detail-value"><?php echo htmlspecialchars($venue['amenity2']); ?></div>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($venue['amenity3'])): ?>
              <div class="detail-item-small">
                <div class="detail-icon">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="detail-content">
                  <div class="detail-value"><?php echo htmlspecialchars($venue['amenity3']); ?></div>
                </div>
              </div>
              <?php endif; ?>
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Recent Bookings</div>
                <div class="booking-list">
                <?php 
                  // Filter bookings for this specific venue
                  $venueBookings = array_filter($bookings, function($booking) use ($venue) {
                    return $booking['venue_id'] == $venue['venue_id'];
                  });
                  
                  if (count($venueBookings) > 0): 
                ?>
                    <?php foreach($venueBookings as $booking): ?>
                      <div class="booking-item">
                        <div class="booking-detail">
                          <div class="booking-name"><?php echo htmlspecialchars($booking['user_name'] ?? 'Guest User'); ?></div>
                          <div class="booking-date"><?php echo date("d M Y", strtotime($booking['bk_date'])); ?> | <?php echo htmlspecialchars($booking['bk_dur']); ?></div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="no-data">No recent bookings found</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <?php if (count($venueDetails) == 0): ?>
          <div class="bento-item">
            <div class="no-data">No venues found. Please add a venue to get started.</div>
          </div>
        <?php endif; ?>
        <div id="venueEditModal" class="modal">
          <div class="modal-content">
              <span class="close-btn" onclick="closeModal('venueEditModal')">&times;</span>
              <h2>Edit Venue Details</h2>
              
              <?php
              // Handle venue update in the same file
              if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_venue') {
                  // Validate and process venue update
                  $venue_id = $_POST['venue_id'] ?? '';
                  $venue_name = $_POST['venue_name'] ?? '';
                  $location = $_POST['location'] ?? '';
                  $price = $_POST['price'] ?? 0;
                  $size = $_POST['size'] ?? '';
                  $availability = $_POST['availability'] ?? 1;
                  $amenity1 = $_POST['amenity1'] ?? '';
                  $amenity2 = $_POST['amenity2'] ?? '';
                  $amenity3 = $_POST['amenity3'] ?? '';

                  // Validation
                  $errors = [];
                  if (empty($venue_name)) $errors[] = 'Venue name is required';
                  if (empty($location)) $errors[] = 'Location is required';
                  if ($price <= 0) $errors[] = 'Invalid price';
                  if (empty($size)) $errors[] = 'Turf size is required';

                  if (empty($errors)) {
                      // Prepare update query
                      $update_sql = "UPDATE venue SET 
                          venue_nm = ?, 
                          location = ?, 
                          price = ?, 
                          size = ?, 
                          availability = ?, 
                          amenity1 = ?, 
                          amenity2 = ?, 
                          amenity3 = ? 
                          WHERE venue_id = ? AND owner_email = ?";
                      
                      $update_stmt = $conn->prepare($update_sql);
                      $update_stmt->bind_param(
                          "ssisisssss", 
                          $venue_name, 
                          $location, 
                          $price, 
                          $size, 
                          $availability, 
                          $amenity1, 
                          $amenity2, 
                          $amenity3, 
                          $venue_id,
                          $userEmail
                      );

                      if ($update_stmt->execute()) {
                          $_SESSION['venue_update_success'] = 'Venue details updated successfully';
                          // Redirect to prevent form resubmission
                          header("Location: {$_SERVER['PHP_SELF']}");
                          exit();
                      } else {
                          $_SESSION['venue_update_error'] = 'Failed to update venue details';
                      }
                  } else {
                      $_SESSION['venue_update_errors'] = $errors;
                  }
              }
              
              // Display any success or error messages
              if (isset($_SESSION['venue_update_success'])) {
                  echo '<div class="success-message">' . htmlspecialchars($_SESSION['venue_update_success']) . '</div>';
                  unset($_SESSION['venue_update_success']);
              }
              if (isset($_SESSION['venue_update_error'])) {
                  echo '<div class="error-message">' . htmlspecialchars($_SESSION['venue_update_error']) . '</div>';
                  unset($_SESSION['venue_update_error']);
              }
              if (isset($_SESSION['venue_update_errors'])) {
                  echo '<div class="error-message">';
                  foreach ($_SESSION['venue_update_errors'] as $error) {
                      echo htmlspecialchars($error) . '<br>';
                  }
                  echo '</div>';
                  unset($_SESSION['venue_update_errors']);
              }
              ?>

              <form method="POST" action="">
                  <input type="hidden" name="action" value="update_venue">
                  <input type="hidden" name="venue_id" id="edit_venue_id" value="">
                  
                  <div class="form-group">
                      <label for="venue_name">Venue Name</label>
                      <input type="text" name="venue_name" id="edit_venue_name" class="modal-input" required>
                  </div>

                  <div class="form-group">
                      <label for="location">Location</label>
                      <input type="text" name="location" id="edit_location" class="modal-input" required>
                  </div>

                  <div class="form-row">
                      <div class="form-group half-width">
                          <label for="price">Per Hour Rate (₹)</label>
                          <input type="number" name="price" id="edit_price" class="modal-input" required min="0">
                      </div>
                      <div class="form-group half-width">
                          <label for="size">Turf Size</label>
                          <input type="text" name="size" id="edit_size" class="modal-input" required>
                      </div>
                  </div>

                  <div class="form-group">
                      <label for="availability">Availability</label>
                      <select name="availability" id="edit_availability" class="modal-input">
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                      </select>
                  </div>

                  <div class="form-group">
                      <label>Amenities</label>
                      <div class="amenities-grid">
                          <input type="text" name="amenity1" id="edit_amenity1" placeholder="Amenity 1" class="modal-input">
                          <input type="text" name="amenity2" id="edit_amenity2" placeholder="Amenity 2" class="modal-input">
                          <input type="text" name="amenity3" id="edit_amenity3" placeholder="Amenity 3" class="modal-input">
                      </div>
                  </div>

                  <button type="submit" class="modal-submit">Update Venue</button>
              </form>
          </div>
        </div>
        <form action="logout.php" method="post">
          <button type="submit" class="signout-button">
            <i class="fas fa-sign-out-alt"></i> Sign Out
          </button>
        </form>
        
        <!-- Dashboard Stats -->
        <div class="bento-item dashboard-stats">
          <div class="bento-header">
            <h2><i class="fas fa-chart-line section-icon"></i> Performance Overview</h2>
          </div>
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-number animate-number"><?php echo count($venueDetails); ?></div>
              <div class="stat-label">Active Turfs</div>
            </div>
            <div class="stat-item">
              <div class="stat-number animate-number"><?php echo $totalBookings; ?></div>
              <div class="stat-label">Bookings</div>
            </div>
            <div class="stat-item">
              <div class="stat-number animate-number"><?php echo $totalTournaments; ?></div>
              <div class="stat-label">Tournaments</div>
            </div>
            <div class="stat-item">
              <div class="stat-number animate-number">₹<?php echo number_format($totalRevenue); ?></div>
              <div class="stat-label">Revenue</div>
            </div>
          </div>
        </div>
        <!-- Recent Tournament Section -->
        <div class="bento-item tournament-section">
          <div class="bento-header">
            <h2><i class="fas fa-trophy section-icon"></i> Upcoming Tournaments</h2>
          </div>
          
          <?php
          // Get tournaments
          $tournamentsQuery = "SELECT t.* FROM tournaments t 
                     JOIN venue v ON t.venue_id = v.venue_id 
                     WHERE v.owner_email = ? AND t.end_date >= CURDATE() 
                     ORDER BY t.start_date ASC LIMIT 3";
          $stmt = $conn->prepare($tournamentsQuery);
          $stmt->bind_param("s", $userEmail);
          $stmt->execute();
          $tournamentsResult = $stmt->get_result();
          
          if ($tournamentsResult->num_rows > 0):
            while ($tournament = $tournamentsResult->fetch_assoc()):
          ?>
            <div class="tournament-item">
              <div class="tournament-icon">
                <?php if (!empty($tournament['img_url'])): ?>
                  <img src="<?php echo htmlspecialchars($tournament['img_url']); ?>" alt="Tournament logo">
                <?php else: ?>
                  <i class="fas fa-trophy"></i>
                <?php endif; ?>
              </div>
              <div class="tournament-details">
                <h3><?php echo htmlspecialchars($tournament['tr_name']); ?></h3>
                <p class="tournament-date">
                  <?php echo date("d M", strtotime($tournament['start_date'])); ?> - 
                  <?php echo date("d M Y", strtotime($tournament['end_date'])); ?>
                </p>
                <p class="tournament-schedule"><?php echo htmlspecialchars($tournament['tr_schedule']); ?></p>
                <p class="tournament-prize">Prize Pool: ₹<?php echo number_format($tournament['prize']); ?></p>
              </div>
            </div>
          <?php 
            endwhile;
          else:
          ?>
            <div class="no-data">No upcoming tournaments</div>
          <?php endif; ?>
          
          <button class="add-button" onclick="location.href='create_tournament.php'">
            <i class="fas fa-plus"></i> Add New Tournament
          </button>
        </div>
      </div>
    </div>
    <?php endif; ?>
    
    <?php if ($userType == 'owner' || $userType == 'admin'): ?>
    <div id="academy-dashboard" class="dashboard-content <?php echo ($defaultDashboard == 'academy') ? 'active' : ''; ?>">
      <div class="bento-grid">
        <?php foreach ($academyDetails as $academy): ?>
          <div class="bento-item details-section">
            <div class="bento-header">
              <h2><i class="fas fa-graduation-cap section-icon"></i> Academy Details</h2>
              <button class="edit-button" onclick="location.href='edit_academy.php?id=<?php echo $academy['ac_id']; ?>'">
                <i class="fas fa-edit"></i> Edit Details
              </button>
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-signature"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Academy Name</div>
                <div class="detail-value">
                  <?php echo htmlspecialchars($academy['aca_nm']); ?>
                  <span class="status-badge status-active">ACTIVE</span>
                </div>
              </div>
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Location</div>
                <div class="detail-value"><?php echo htmlspecialchars($academy['ac_location']); ?></div>
              </div>
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Fee</div>
                <div class="detail-value">₹<?php echo number_format($academy['ac_charges']); ?></div>
              </div>
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-user-graduate"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Level & Age Group</div>
                <div class="detail-value">
                  <?php echo htmlspecialchars($academy['level']); ?> | 
                  <?php echo htmlspecialchars($academy['age_group']); ?>
                </div>
              </div>
            </div>
            
            <div class="amenity-grid">
              <?php if (!empty($academy['feature1'])): ?>
              <div class="detail-item-small">
                <div class="detail-icon">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="detail-content">
                  <div class="detail-value"><?php echo htmlspecialchars($academy['feature1']); ?></div>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($academy['feature2'])): ?>
              <div class="detail-item-small">
                <div class="detail-icon">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="detail-content">
                  <div class="detail-value"><?php echo htmlspecialchars($academy['feature2']); ?></div>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($academy['feature3'])): ?>
              <div class="detail-item-small">
                <div class="detail-icon">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="detail-content">
                  <div class="detail-value"><?php echo htmlspecialchars($academy['feature3']); ?></div>
                </div>
              </div>
              <?php endif; ?>
            </div>
            
            <div class="detail-item">
              <div class="detail-icon">
                <i class="fas fa-users"></i>
              </div>
              <div class="detail-content">
                <div class="detail-label">Students</div>
                <div class="people-list">
                  <?php
                  // Filter students for this specific academy
                  $academyStudents = array_filter($students, function($student) use ($academy) {
                    return $student['ac_id'] == $academy['ac_id'];
                  });
                  
                  if (count($academyStudents) > 0): 
                  ?>
                    <?php foreach($academyStudents as $student): ?>
                      <div class="people-item">
                        <div class="avatar">
                          <i class="fas fa-user"></i>
                        </div>
                        <div class="people-info">
                          <div class="people-name"><?php echo htmlspecialchars($student['user_name'] ?? 'Student'); ?></div>
                          <div class="people-role">
                            Enrolled: <?php echo date("d M Y", strtotime($student['en_date'])); ?> | 
                            Duration: <?php echo $student['en_dur']; ?> months
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="no-data">No students enrolled yet</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        
        <?php if (count($academyDetails) == 0): ?>
        <div class="bento-item">
          <div class="no-data">No academies found. Please add an academy to get started.</div>
        </div>
        <?php endif; ?>
        
        <div id="academyEditModal" class="modal">
          <div class="modal-content">
              <span class="close-btn" onclick="closeModal('academyEditModal')">&times;</span>
              <h2>Edit Academy Details</h2>
              
              <?php
              // Handle academy update in the same file
              if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_academy') {
                  // Validate and process academy update
                  $academy_id = $_POST['academy_id'] ?? '';
                  $academy_name = $_POST['aca_nm'] ?? '';
                  $location = $_POST['ac_location'] ?? '';
                  $charges = $_POST['ac_charges'] ?? 0;
                  $level = $_POST['level'] ?? '';
                  $age_group = $_POST['age_group'] ?? '';
                  $availability = $_POST['availability'] ?? 1;
                  $feature1 = $_POST['feature1'] ?? '';
                  $feature2 = $_POST['feature2'] ?? '';
                  $feature3 = $_POST['feature3'] ?? '';

                  // Validation
                  $errors = [];
                  if (empty($academy_name)) $errors[] = 'Academy name is required';
                  if (empty($location)) $errors[] = 'Location is required';
                  if ($charges <= 0) $errors[] = 'Invalid fee';
                  if (empty($level)) $errors[] = 'Level is required';
                  if (empty($age_group)) $errors[] = 'Age group is required';

                  if (empty($errors)) {
                      // Prepare update query
                      $update_sql = "UPDATE academys SET 
                          aca_nm = ?, 
                          ac_location = ?, 
                          ac_charges = ?, 
                          level = ?, 
                          age_group = ?, 
                          feature1 = ?, 
                          feature2 = ?, 
                          feature3 = ? 
                          WHERE ac_id = ? AND owner_email = ?";
                      
                      $update_stmt = $conn->prepare($update_sql);
                      $update_stmt->bind_param(
                          "sssssssis", 
                          $academy_name, 
                          $location, 
                          $charges, 
                          $level, 
                          $age_group, 
                          $feature1, 
                          $feature2, 
                          $feature3, 
                          $academy_id,
                          $userEmail
                      );

                      if ($update_stmt->execute()) {
                          $_SESSION['academy_update_success'] = 'Academy details updated successfully';
                          // Redirect to prevent form resubmission
                          header("Location: {$_SERVER['PHP_SELF']}");
                          exit();
                      } else {
                          $_SESSION['academy_update_error'] = 'Failed to update academy details';
                      }
                  } else {
                      $_SESSION['academy_update_errors'] = $errors;
                  }
              }
              
              // Display any success or error messages
              if (isset($_SESSION['academy_update_success'])) {
                  echo '<div class="success-message">' . htmlspecialchars($_SESSION['academy_update_success']) . '</div>';
                  unset($_SESSION['academy_update_success']);
              }
              if (isset($_SESSION['academy_update_error'])) {
                  echo '<div class="error-message">' . htmlspecialchars($_SESSION['academy_update_error']) . '</div>';
                  unset($_SESSION['academy_update_error']);
              }
              if (isset($_SESSION['academy_update_errors'])) {
                  echo '<div class="error-message">';
                  foreach ($_SESSION['academy_update_errors'] as $error) {
                      echo htmlspecialchars($error) . '<br>';
                  }
                  echo '</div>';
                  unset($_SESSION['academy_update_errors']);
              }
              ?>

              <form method="POST" action="">
                  <input type="hidden" name="action" value="update_academy">
                  <input type="hidden" name="academy_id" id="edit_academy_id" value="">
                  
                  <div class="form-group">
                      <label for="aca_nm">Academy Name</label>
                      <input type="text" name="aca_nm" id="edit_academy_name" class="modal-input" required>
                  </div>

                  <div class="form-group">
                      <label for="ac_location">Location</label>
                      <input type="text" name="ac_location" id="edit_location" class="modal-input" required>
                  </div>

                  <div class="form-row">
                      <div class="form-group half-width">
                          <label for="ac_charges">Monthly Fee (₹)</label>
                          <input type="number" name="ac_charges" id="edit_charges" class="modal-input" required min="0">
                      </div>
                      <div class="form-group half-width">
                          <label for="level">Level</label>
                          <input type="text" name="level" id="edit_level" class="modal-input" required>
                      </div>
                  </div>

                  <div class="form-group">
                      <label for="age_group">Age Group</label>
                      <input type="text" name="age_group" id="edit_age_group" class="modal-input" required>
                  </div>

                  <div class="form-group">
                      <label>Features</label>
                      <div class="amenities-grid">
                          <input type="text" name="feature1" id="edit_feature1" placeholder="Feature 1" class="modal-input">
                          <input type="text" name="feature2" id="edit_feature2" placeholder="Feature 2" class="modal-input">
                          <input type="text" name="feature3" id="edit_feature3" placeholder="Feature 3" class="modal-input">
                      </div>
                  </div>

                  <button type="submit" class="modal-submit">Update Academy</button>
              </form>
          </div>
        </div>
        <form action="logout.php" method="post">
          <button type="submit" class="signout-button">
            <i class="fas fa-sign-out-alt"></i> Sign Out
          </button>
        </form>
        
        <div class="bento-item dashboard-stats">
          <div class="bento-header">
            <h2><i class="fas fa-chart-line section-icon"></i> Performance Overview</h2>
          </div>
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-number animate-number"><?php echo count($academyDetails); ?></div>
              <div class="stat-label">Academies</div>
            </div>
            <div class="stat-item">
              <div class="stat-number animate-number">
                <?php 
                  $totalStudents = 0;
                  foreach ($academyDetails as $academy) {
                    $totalStudents += isset($academy['student_count']) ? $academy['student_count'] : 0;
                  }
                  echo $totalStudents;
                ?>
              </div>
              <div class="stat-label">Students</div>
            </div>
            <div class="stat-item">
              <div class="stat-number animate-number">
                <?php 
                  $totalSessions = 0;
                  foreach ($academyDetails as $academy) {
                    $sessions = isset($academy['timings']) ? substr_count($academy['timings'], ',') + 1 : 0;
                    $totalSessions += $sessions;
                  }
                  echo count($academyDetails) > 0 ? round($totalSessions / count($academyDetails)) : 0;
                ?>
              </div>
              <div class="stat-label">Avg. Sessions/Week</div>
            </div>
            <div class="stat-item">
              <div class="stat-number animate-number">₹<?php echo number_format($totalRevenue); ?></div>
              <div class="stat-label">Revenue</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
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
        <li><a href="index.php">Home</a></li>
        <li><a href="venues.php">Find Grounds</a></li>
        <li><a href="academies.php">Join Academy</a></li>
        <li><a href="tournaments.php">Tournaments</a></li>
        <li><a href="about.php">About Us</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <div class="contact">
        <h3>Contact Us</h3>
        <div class="contact-info">
          <p><i class="fas fa-envelope"></i> info@gameday.com</p>
          <p><i class="fas fa-phone"></i> +91 123 456 7890</p>
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
    // Dashboard switching functionality
    function switchDashboard(type) {
      // Hide all dashboards
      document.querySelectorAll('.dashboard-content').forEach(function(dashboard) {
        dashboard.classList.remove('active');
      });
      
      // Show selected dashboard
      document.getElementById(type + '-dashboard').classList.add('active');
      
      // Update button styling
      document.querySelectorAll('.dashboard-button').forEach(function(button) {
        button.classList.remove('active');
      });
      event.target.classList.add('active');
    }

    // Animate numbers on page load
    document.addEventListener('DOMContentLoaded', function() {
      const numberElements = document.querySelectorAll('.animate-number');
      
      numberElements.forEach(function(element) {
        const targetValue = element.textContent;
        const isCurrency = targetValue.includes('₹');
        
        // Remove currency symbol and commas for calculation
        let value = isCurrency ? 
          parseInt(targetValue.replace('₹', '').replace(/,/g, '')) : 
          parseInt(targetValue);
        
        if (isNaN(value)) return;
        
        // Start from zero
        element.textContent = isCurrency ? '₹0' : '0';
        let current = 0;
        
        // Calculate step based on value
        const step = Math.max(1, Math.floor(value / 30));
        
        // Animation function
        const animate = function() {
          current += step;
          
          // Stop at or above target
          if (current >= value) {
            current = value;
            clearInterval(timer);
          }
          
          // Format with commas and currency if needed
          let formattedValue = current.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          if (isCurrency) formattedValue = '₹' + formattedValue;
          
          element.textContent = formattedValue;
        };
        
        // Run animation at 30fps
        const timer = setInterval(animate, 33);
      });
    });
    function openVenueEditModal(venueId, venueName, location, price, size, availability, amenity1, amenity2, amenity3) {
      // Populate form fields
      document.getElementById('edit_venue_id').value = venueId;
      document.getElementById('edit_venue_name').value = venueName;
      document.getElementById('edit_location').value = location;
      document.getElementById('edit_price').value = price;
      document.getElementById('edit_size').value = size;
      document.getElementById('edit_availability').value = availability;
      document.getElementById('edit_amenity1').value = amenity1 || '';
      document.getElementById('edit_amenity2').value = amenity2 || '';
      document.getElementById('edit_amenity3').value = amenity3 || '';

      // Show the modal
      document.getElementById('venueEditModal').style.display = 'flex';
  }
  function openAcademyEditModal(academyId, academyName, location, charges, level, ageGroup, feature1, feature2, feature3) {
    // Populate form fields
    document.getElementById('edit_academy_id').value = academyId;
    document.getElementById('edit_academy_name').value = academyName;
    document.getElementById('edit_location').value = location;
    document.getElementById('edit_charges').value = charges;
    document.getElementById('edit_level').value = level;
    document.getElementById('edit_age_group').value = ageGroup;
    document.getElementById('edit_feature1').value = feature1 || '';
    document.getElementById('edit_feature2').value = feature2 || '';
    document.getElementById('edit_feature3').value = feature3 || '';

    // Show the modal
    document.getElementById('academyEditModal').style.display = 'flex';
}
  // Function to close modal
  function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
  }

</script>
</body>
</html>