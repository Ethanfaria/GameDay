<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
// Redirect if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}
?>
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

	// Handle Profile Update
	// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
    $user_email = $_SESSION['user_email'];
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    // Validate inputs
    $errors = [];
    
    // Name validation
    if (empty($name)) {
        $errors[] = 'Name is required';
    } elseif (strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters long";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $name)) {
        $errors[] = "Name can only contain letters, numbers, and underscores";
    }
    
    // Phone validation
    if (empty($phone)) {
        $errors[] = 'Phone is required';
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Phone number must be 10 digits";
    } else {
        // Check if phone number already exists in database (but not for current user)
        $sql = "SELECT * FROM user WHERE user_ph = ? AND email != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $phone, $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Phone number is already registered by another user";
        }
        $stmt->close();
    }
    
    // Password validation (only if new password is provided)
    if (!empty($newPassword)) {
        if (strlen($newPassword) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        } elseif (!preg_match("/[A-Z]/", $newPassword)) {
            $errors[] = "Password must contain at least one uppercase letter";
        } elseif (!preg_match("/[a-z]/", $newPassword)) {
            $errors[] = "Password must contain at least one lowercase letter";
        } elseif (!preg_match("/[0-9]/", $newPassword)) {
            $errors[] = "Password must contain at least one number";
        } elseif (!preg_match("/[\W_]/", $newPassword)) {
            $errors[] = "Password must contain at least one special character";
        }
    }

    // Verify current password
    $verify_password_sql = "SELECT password FROM user WHERE email = ?";
    $verify_stmt = $conn->prepare($verify_password_sql);
    $verify_stmt->bind_param("s", $user_email);
    $verify_stmt->execute();
    $result = $verify_stmt->get_result();
    $user = $result->fetch_assoc();

    if (!password_verify($currentPassword, $user['password'])) {
        $errors[] = 'Current password is incorrect';
    }

    // If no errors, proceed with update
    if (empty($errors)) {
        // Rest of your existing update code
    } else {
        $_SESSION['update_errors'] = $errors;
    }
    
    // Redirect back to dashboard to prevent form resubmission
    header("Location: userdashboard.php");
    exit();
}
	
	function getUserVisitedVenues($conn, $user_email) {
		$visited_venues_sql = "SELECT DISTINCT v.venue_id, v.venue_nm 
							FROM book b
							JOIN venue v ON b.venue_id = v.venue_id
							WHERE b.email = ? AND b.bk_date < CURDATE()
							AND NOT EXISTS (
								SELECT 1 FROM venue_reviews vr 
								WHERE vr.venue_id = v.venue_id 
								AND vr.v_reviews LIKE ?
							)
							LIMIT 5";
		$stmt = $conn->prepare($visited_venues_sql);
		$review_tag = '%' . $user_email . '%';
		$stmt->bind_param("ss", $user_email, $review_tag);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_all(MYSQLI_ASSOC);
	}

	// Handle review submission for venues
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_ground_review'])) {
		$venue_id = $_POST['venue_id'];
		$rating = $_POST['rating'];
		$feedback = $_POST['feedback'];
		
		if (isset($_SESSION['user_email'])) {
			$email = $_SESSION['user_email'];
			
			$review_id = 'rev_' . uniqid();
			$full_feedback = $feedback . ' (' . $email . ')';
			$sql = "INSERT INTO venue_reviews (v_review_id, venue_id, v_ratings, v_reviews) VALUES (?, ?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("ssis", $review_id, $venue_id, $rating, $full_feedback);
			
			if ($stmt->execute()) {
				$_SESSION['ground_review_success'] = "Your review for the venue has been submitted successfully!";
			} else {
				$_SESSION['ground_review_error'] = "Error submitting review: " . $stmt->error;
			}
		} else {
			$_SESSION['ground_review_error'] = "You must be logged in to submit a review.";
		}
		header("Location: userdashboard.php");
		exit();
	}

	function getUserEnrolledAcademies($conn, $user_email) {
		$enrolled_academies_sql = "SELECT DISTINCT a.ac_id, a.aca_nm 
								FROM enroll e
								JOIN academys a ON e.ac_id = a.ac_id
								WHERE e.email = ? AND NOT EXISTS (
									SELECT 1 FROM academy_reviews ar 
									WHERE ar.ac_id = a.ac_id 
									AND ar.a_reviews LIKE ?
								)
								LIMIT 5";
		$stmt = $conn->prepare($enrolled_academies_sql);
		$review_tag = '%' . $user_email . '%';
		$stmt->bind_param("ss", $user_email, $review_tag);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_all(MYSQLI_ASSOC);
	}

	// Handle review submission for academies
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_academy_review'])) {
		$ac_id = $_POST['ac_id'];
		$rating = $_POST['rating'];
		$feedback = $_POST['feedback'];
		
		if (isset($_SESSION['user_email'])) {
			$email = $_SESSION['user_email'];
			
			$review_id = 'acrev_' . uniqid();
			$full_feedback = $feedback . ' (' . $email . ')';
			$sql = "INSERT INTO academy_reviews (a_review_id, ac_id, a_ratings, a_reviews) VALUES (?, ?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("ssis", $review_id, $ac_id, $rating, $full_feedback);
			
			if ($stmt->execute()) {
				$_SESSION['academy_review_success'] = "Your review for the academy has been submitted successfully!";
			} else {
				$_SESSION['academy_review_error'] = "Error submitting review: " . $stmt->error;
			}
		} else {
			$_SESSION['academy_review_error'] = "You must be logged in to submit a review.";
		}
		header("Location: userdashboard.php");
		exit();
	}
	
	// Fetch current user details for pre-filling
	$user_email = $_SESSION['user_email'] ?? '';
	$user_details_sql = "SELECT user_name, user_ph FROM user WHERE email = ?";
	$user_details_stmt = $conn->prepare($user_details_sql);
	$user_details_stmt->bind_param("s", $user_email);
	$user_details_stmt->execute();
	$user_details = $user_details_stmt->get_result()->fetch_assoc();

	// Make sure user_email is defined
	$user_email = $_SESSION['user_email'] ?? '';

	// Query to fetch upcoming bookings with venue details
	$bookings_sql = "SELECT b.*, v.venue_nm, v.location 
			FROM book b 
			LEFT JOIN venue v ON b.venue_id = v.venue_id 
			WHERE b.email = ? AND b.bk_date >= CURRENT_DATE() 
			ORDER BY b.bk_date ASC";

	$bookings_stmt = $conn->prepare($bookings_sql);
	$bookings_stmt->bind_param("s", $user_email);
	$bookings_stmt->execute();
	$bookings_result = $bookings_stmt->get_result();

	// Get user stats with total hours calculation
	$stats_sql = "SELECT 
					COUNT(DISTINCT booking_id) as matches_played,
					COUNT(DISTINCT venue_id) as grounds_visited,
					COUNT(DISTINCT CONCAT(bk_date, bk_dur)) as total_sessions
				FROM book 
				WHERE email = ? AND bk_date <= CURDATE()";
	$stats_stmt = $conn->prepare($stats_sql);
	$stats_stmt->bind_param("s", $user_email);
	$stats_stmt->execute();
	$stats = $stats_stmt->get_result()->fetch_assoc();
	
	// Calculate approximate total hours (assuming average 1 hour per session)
	$stats['total_hours'] = $stats['total_sessions'] ?? 0;
	
	// Count teammates (unique users who booked same venues on same dates)
	$teammates_sql = "SELECT COUNT(DISTINCT b2.email) as teammate_count
					FROM book b1
					JOIN book b2 ON b1.venue_id = b2.venue_id AND b1.bk_date = b2.bk_date 
					WHERE b1.email = ? AND b2.email != ?";
	$teammates_stmt = $conn->prepare($teammates_sql);
	$teammates_stmt->bind_param("ss", $user_email, $user_email);
	$teammates_stmt->execute();
	$teammates_result = $teammates_stmt->get_result()->fetch_assoc();
	$teammate_count = $teammates_result['teammate_count'] ?? 0;
	
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
	$next_tournament_sql = "SELECT tr_name, start_date, end_date,
							DATEDIFF(start_date, CURDATE()) as days_left
							FROM tournaments
							WHERE start_date > CURDATE()
							ORDER BY start_date ASC
							LIMIT 1";
	$next_tournament_result = $conn->query($next_tournament_sql);
	$next_tournament = $next_tournament_result->fetch_assoc();

	// Get registered tournaments
	$registered_tournaments_sql = "SELECT t.tr_name, t.start_date
	FROM register r
	JOIN tournaments t ON r.tr_id = t.tr_id
	WHERE r.email = ? AND t.start_date > CURDATE()
	ORDER BY t.start_date ASC";
	$registered_stmt = $conn->prepare($registered_tournaments_sql);
	$registered_stmt->bind_param("s", $user_email);
	$registered_stmt->execute();
	$registered_tournament_result = $registered_stmt->get_result();
	?>
	
	<div class="dashboard">
		<div class="welcome">
		<h1>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Player'); ?>!</h1>
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
		</div>
		
		<div class="bento-item profile-actions">
			<div class="bento-header">
			<h2><i class="fas fa-user-circle section-icon"></i> Profile Actions</h2>
			</div>
			<div class="quick-action" id="editProfileAction">
			<div class="icon-placeholder"><i class="fas fa-user-edit"></i></div>
			<div>Edit Profile</div>
			</div>
			<div class="quick-action" id="inviteFriendsAction">
			<div class="icon-placeholder"><i class="fas fa-user-plus"></i></div>
			<div>Invite Friends</div>
			</div>
			<div class="quick-action" id="groundReviewAction">
			<div class="icon-placeholder"><i class="fas fa-star"></i></div>
			<div>Rate & Review Turf</div>
			</div>
			<div class="quick-action" id="academyReviewAction">
			<div class="icon-placeholder"><i class="fas fa-graduation-cap"></i></div>
			<div>Rate & Review Academy</div>
			</div>
			<button onclick="window.location.href='logout.php'" class="signout-button">
			<i class="fas fa-sign-out-alt"></i> Sign Out
			</button>
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
				<div class="stat-number animate-number"><?php echo $teammate_count ?? 0; ?></div>
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
			<h2><i class="fas fa-trophy section-icon"></i> Tournament Registrations</h2>
			</div>
			<?php 
			if ($registered_tournament_result->num_rows > 0) {
				while ($registered_tournament = $registered_tournament_result->fetch_assoc()) {
			?>
			<div class="tournament-item">
			<div class="tournament-info">
				<div><?php echo htmlspecialchars($registered_tournament['tr_name']); ?></div>
				<div class="tournament-location">
				<i class="far fa-calendar-alt"></i> 
				Starting <?php echo date('M d, Y', strtotime($registered_tournament['start_date'])); ?>
				</div>
			</div>
			</div>
			<?php 
				}
			} else {
			?>
			<p>No tournament registrations</p>
			<?php 
			}
			$registered_stmt->close();
			?>
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
	<div id="profileEditModal" class="modal">
		<div class="modal-content">
			<span class="close-btn" onclick="closeModal('profileEditModal')">&times;</span>
			<h2>Edit Profile</h2>
			
			<?php if (isset($_SESSION['update_errors'])): ?>
				<div class="error-message">
					<?php foreach ($_SESSION['update_errors'] as $error): ?>
						<p><?php echo htmlspecialchars($error); ?></p>
					<?php endforeach; ?>
					<?php unset($_SESSION['update_errors']); ?>
				</div>
			<?php endif; ?>
			
			<?php if (isset($_SESSION['update_success'])): ?>
				<div class="success-message">
					<p><?php echo htmlspecialchars($_SESSION['update_success']); ?></p>
					<?php unset($_SESSION['update_success']); ?>
				</div>
			<?php endif; ?>
			
			<form method="POST" action="userdashboard.php" id="profileEditForm">
				<input type="hidden" name="action" value="update_profile">
				
				<div class="form-group">
					<input type="text" name="name" id="editName" class="modal-input" placeholder="Name" value="<?php echo htmlspecialchars($user_details['user_name'] ?? ''); ?>" required onkeyup="validateEditName()">
					<div class="requirements">
						<p id="nameLength" class="requirement">At least 3 characters</p>
						<p id="nameChars" class="requirement">Only letters, numbers, and underscores</p>
					</div>
				</div>
				
				<input type="email" class="modal-input" placeholder="Email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
				
				<div class="form-group">
					<input type="tel" name="phone" id="editPhone" class="modal-input" placeholder="Phone Number" value="<?php echo htmlspecialchars($user_details['user_ph'] ?? ''); ?>" required onkeyup="validateEditPhone()">
					<div class="requirements">
						<p id="phoneDigits" class="requirement">Must be 10 digits</p>
						<p id="phoneNumbers" class="requirement">Numbers only</p>
						<p id="phoneVal" class="requirement">Valid phone number</p>
					</div>
				</div>
				
				<input type="password" name="currentPassword" class="modal-input" placeholder="Current Password" required>
				
				<div class="form-group">
					<input type="password" name="newPassword" id="editPassword" class="modal-input" placeholder="New Password (leave blank if no change)" onkeyup="validateEditPassword()">
					<div class="requirements">
						<p id="passwordLength" class="requirement">At least 8 characters</p>
						<p id="passwordUpper" class="requirement">At least one uppercase letter</p>
						<p id="passwordLower" class="requirement">At least one lowercase letter</p>
						<p id="passwordNumber" class="requirement">At least one number</p>
						<p id="passwordSpecial" class="requirement">At least one special character</p>
					</div>
				</div>
				
				<button type="submit" class="modal-submit">Save Changes</button>
			</form>
		</div>
	</div>
	<div id="groundReviewModal" class="modal">
		<div class="modal-content">
			<span class="close-btn" onclick="closeModal('groundReviewModal')">&times;</span>
			<h2>Rate & Review Ground</h2>
			
			<?php
			// Display success or error messages
			if (isset($_SESSION['ground_review_success'])) {
				echo '<div class="success-message">' . htmlspecialchars($_SESSION['ground_review_success']) . '</div>';
				unset($_SESSION['ground_review_success']);
			}
			if (isset($_SESSION['ground_review_error'])) {
				echo '<div class="error-message">' . htmlspecialchars($_SESSION['ground_review_error']) . '</div>';
				unset($_SESSION['ground_review_error']);
			}

			// Get venues user can review
			$visited_venues = getUserVisitedVenues($conn, $user_email);
			?>

			<?php if (!empty($visited_venues)): ?>
			<form method="POST" action="userdashboard.php">
				<input type="hidden" name="submit_ground_review" value="1">
				
				<div class="form-group">
					<label for="venue_select">Select Venue</label>
					<select name="venue_id" id="venue_select" class="modal-input" required>
						<?php foreach ($visited_venues as $venue): ?>
							<option value="<?php echo htmlspecialchars($venue['venue_id']); ?>">
								<?php echo htmlspecialchars($venue['venue_nm']); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="rating-input">
					<span>Your Rating:</span>
					<div class="star-rating">
						<input type="radio" id="star5" name="rating" value="5" required><label for="star5"></label>
						<input type="radio" id="star4" name="rating" value="4"><label for="star4"></label>
						<input type="radio" id="star3" name="rating" value="3"><label for="star3"></label>
						<input type="radio" id="star2" name="rating" value="2"><label for="star2"></label>
						<input type="radio" id="star1" name="rating" value="1"><label for="star1"></label>
					</div>
				</div>

				<div class="review-text">
					<textarea name="feedback" rows="4" placeholder="Share your experience with this venue..." required></textarea>
				</div>

				<button type="submit" class="modal-submit">Submit Review</button>
			</form>
			<?php else: ?>
				<p>No venues available for review. Book a ground and play first!</p>
			<?php endif; ?>
		</div>
	</div>
	<div id="academyReviewModal" class="modal">
		<div class="modal-content">
			<span class="close-btn" onclick="closeModal('academyReviewModal')">&times;</span>
			<h2>Rate & Review Academy</h2>
			
			<?php
			// Display success or error messages
			if (isset($_SESSION['academy_review_success'])) {
				echo '<div class="success-message">' . htmlspecialchars($_SESSION['academy_review_success']) . '</div>';
				unset($_SESSION['academy_review_success']);
			}
			if (isset($_SESSION['academy_review_error'])) {
				echo '<div class="error-message">' . htmlspecialchars($_SESSION['academy_review_error']) . '</div>';
				unset($_SESSION['academy_review_error']);
			}

			// Get academies user can review
			$enrolled_academies = getUserEnrolledAcademies($conn, $user_email);
			?>

			<?php if (!empty($enrolled_academies)): ?>
			<form method="POST" action="userdashboard.php">
				<input type="hidden" name="submit_academy_review" value="1">
				
				<div class="form-group">
					<label for="academy_select">Select Academy</label>
					<select name="ac_id" id="academy_select" class="modal-input" required>
						<?php foreach ($enrolled_academies as $academy): ?>
							<option value="<?php echo htmlspecialchars($academy['ac_id']); ?>">
								<?php echo htmlspecialchars($academy['aca_nm']); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="rating-input">
					<span>Your Rating:</span>
					<div class="star-rating">
						<input type="radio" id="academy_star5" name="rating" value="5" required><label for="academy_star5"></label>
						<input type="radio" id="academy_star4" name="rating" value="4"><label for="academy_star4"></label>
						<input type="radio" id="academy_star3" name="rating" value="3"><label for="academy_star3"></label>
						<input type="radio" id="academy_star2" name="rating" value="2"><label for="academy_star2"></label>
						<input type="radio" id="academy_star1" name="rating" value="1"><label for="academy_star1"></label>
					</div>
				</div>

				<div class="review-text">
					<textarea name="feedback" rows="4" placeholder="Share your experience with this academy..." required></textarea>
				</div>

				<button type="submit" class="modal-submit">Submit Review</button>
			</form>
			<?php else: ?>
				<p>No academies available for review. Enroll in an academy first!</p>
			<?php endif; ?>
		</div>
	</div>
	<?php include 'footer.php'; ?>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
		// Animation for stats numbers
		const countElements = document.querySelectorAll('.animate-number');
		countElements.forEach(el => {
			const target = parseInt(el.textContent);
			let count = 0;
			const duration = 1000; // ms
			const frameDuration = 1000/30; // 30fps
			const increment = target / (duration / frameDuration);
			
			const counter = setInterval(() => {
			count += increment;
			if(count >= target) {
				el.textContent = target;
				clearInterval(counter);
			} else {
				el.textContent = Math.floor(count);
			}
			}, frameDuration);
		});
		
		// Countdown animation
		const countdownEl = document.querySelector('.countdown-number');
		if (countdownEl) {
			const startValue = parseInt(countdownEl.textContent);
			// Animation code would go here if needed
		}
		
		// Modal functionality
		document.getElementById('editProfileAction').addEventListener('click', function() {
			openModal('profileEditModal');
		});
		
		document.getElementById('inviteFriendsAction').addEventListener('click', function() {
			const inviteLink = 'https://gameday.com/invite?ref=' + btoa('<?php echo $_SESSION['user_email']; ?>');
			
			// Create a temporary textarea to copy the link
			const tempInput = document.createElement('textarea');
			tempInput.value = inviteLink;
			document.body.appendChild(tempInput);
			tempInput.select();
			document.execCommand('copy');
			document.body.removeChild(tempInput);
			
			// Show a notification
			alert('Invite link copied to clipboard!');
		});
		
		document.getElementById('groundReviewAction').addEventListener('click', function() {
			openModal('groundReviewModal');
		});
		
		document.getElementById('academyReviewAction').addEventListener('click', function() {
			openModal('academyReviewModal');
		});
		
		// Close modal when clicking outside of it
		window.addEventListener('click', function(event) {
			const modals = document.querySelectorAll('.modal');
			modals.forEach(modal => {
			if (event.target === modal) {
				closeModal(modal.id);
			}
			});
		});
		});
		
		function openModal(modalId) {
		document.getElementById(modalId).style.display = 'flex';
		}
		
		function closeModal(modalId) {
		document.getElementById(modalId).style.display = 'none';
		}

		function validateEditName() {
			const name = document.getElementById('editName').value;
			const lengthRequirement = document.getElementById('nameLength');
			const charsRequirement = document.getElementById('nameChars');
			
			if (name.length >= 3) {
				lengthRequirement.classList.add('valid');
				lengthRequirement.classList.remove('invalid');
			} else {
				lengthRequirement.classList.add('invalid');
				lengthRequirement.classList.remove('valid');
			}
			
			if (/^[a-zA-Z0-9_]+$/.test(name)) {
				charsRequirement.classList.add('valid');
				charsRequirement.classList.remove('invalid');
			} else {
				charsRequirement.classList.add('invalid');
				charsRequirement.classList.remove('valid');
			}
		}

		function validateEditPhone() {
			const phone = document.getElementById('editPhone').value;
			const digitsRequirement = document.getElementById('phoneDigits');
			const numbersRequirement = document.getElementById('phoneNumbers');
			const valueRequirement = document.getElementById('phoneVal');
			
			if (phone.length === 10) {
				digitsRequirement.classList.add('valid');
				digitsRequirement.classList.remove('invalid');
			} else {
				digitsRequirement.classList.add('invalid');
				digitsRequirement.classList.remove('valid');
			}
			
			if (/^[0-9]+$/.test(phone)) {
				numbersRequirement.classList.add('valid');
				numbersRequirement.classList.remove('invalid');
			} else {
				numbersRequirement.classList.add('invalid');
				numbersRequirement.classList.remove('valid');
			}
			
			if (phone !== '' && parseInt(phone) !== 0) {
				valueRequirement.classList.add('valid');
				valueRequirement.classList.remove('invalid');
			} else {
				valueRequirement.classList.add('invalid');
				valueRequirement.classList.remove('valid');
			}
		}

		function validateEditPassword() {
			const password = document.getElementById('editPassword').value;
			
			// If password is empty, don't validate (since it's optional)
			if (!password) {
				document.querySelectorAll('#profileEditForm .requirements p').forEach(el => {
					el.classList.remove('valid');
					el.classList.remove('invalid');
				});
				return;
			}
			
			const lengthRequirement = document.getElementById('passwordLength');
			const upperRequirement = document.getElementById('passwordUpper');
			const lowerRequirement = document.getElementById('passwordLower');
			const numberRequirement = document.getElementById('passwordNumber');
			const specialRequirement = document.getElementById('passwordSpecial');
			
			if (password.length >= 8) {
				lengthRequirement.classList.add('valid');
				lengthRequirement.classList.remove('invalid');
			} else {
				lengthRequirement.classList.add('invalid');
				lengthRequirement.classList.remove('valid');
			}
			
			if (/[A-Z]/.test(password)) {
				upperRequirement.classList.add('valid');
				upperRequirement.classList.remove('invalid');
			} else {
				upperRequirement.classList.add('invalid');
				upperRequirement.classList.remove('valid');
			}
			
			if (/[a-z]/.test(password)) {
				lowerRequirement.classList.add('valid');
				lowerRequirement.classList.remove('invalid');
			} else {
				lowerRequirement.classList.add('invalid');
				lowerRequirement.classList.remove('valid');
			}
			
			if (/[0-9]/.test(password)) {
				numberRequirement.classList.add('valid');
				numberRequirement.classList.remove('invalid');
			} else {
				numberRequirement.classList.add('invalid');
				numberRequirement.classList.remove('valid');
			}
			
			if (/[\W_]/.test(password)) {
				specialRequirement.classList.add('valid');
				specialRequirement.classList.remove('invalid');
			} else {
				specialRequirement.classList.add('invalid');
				specialRequirement.classList.remove('valid');
			}
		}

		// Add this to your existing DOMContentLoaded event listener
		document.addEventListener('DOMContentLoaded', function() {
			// Initialize validation states when modal opens
			document.getElementById('editProfileAction').addEventListener('click', function() {
				setTimeout(function() {
					validateEditName();
					validateEditPhone();
					validateEditPassword();
				}, 100);
			});
		});
		</script>
	</body>
</html>