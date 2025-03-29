<?php
// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_email']) || $_SESSION['user_type'] !== "admin") {
    header("Location: login.php");
    exit();
}

// Database connection
include 'db.php';

// Get stats for dashboard
$stats = [
    'venues' => 0,
    'users' => 0,
    'bookings' => 0,
    'revenue' => 0
];

// Count venues
$sql = "SELECT COUNT(*) as count FROM venue";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['venues'] = $row['count'];
}

// Count users
$sql = "SELECT COUNT(*) as count FROM user";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['users'] = $row['count'];
}

// Count bookings (assuming you have a bookings table)
$sql = "SELECT COUNT(*) as count FROM book";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['bookings'] = $row['count'];
}

// Calculate revenue (assuming you have a bookings table with amount)
// $sql = "SELECT SUM(amount) as total FROM book";
// $result = $conn->query($sql);
// if ($result && $row = $result->fetch_assoc()) {
//     $stats['revenue'] = $row['total'] ?? 0;
// }

// Get recent bookings
$recentBookings = [];
$sql = "SELECT b.*, u.user_name, v.venue_nm 
        FROM book b 
        JOIN user u ON b.email = u.email 
        JOIN venue v ON b.venue_id = v.venue_id 
        ORDER BY b.bk_date DESC LIMIT 5";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentBookings[] = $row;
    }
}

$conn->close();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\main.css">
    <style>
        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .menu-item:hover, .menu-item.active {
            background-color: rgba(185, 255, 0, 0.2);
            border-left: 4px solid var(--neon-green);
        }
        
        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .content {
            flex: 1;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .welcome-message {
            font-size: 18px;
            font-weight: 600;
        }
        
        .admin-name {
            color: var(--neon-green);
        }
        
        .header-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--neon-green);
            color: var(--dark-green);
        }
        
        .btn-danger {
            background-color: #ff4d4d;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: rgba(0, 100, 50, 0.2);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            border: 1px solid var(--translucent-bg);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background-color: rgba(185, 255, 0, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            color: var(--neon-green);
            font-size: 24px;
        }
        
        .stat-info {
            flex: 1;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #ccc;
            font-size: 14px;
        }
        
        .card {
            background-color: rgba(0, 100, 50, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--translucent-bg);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 20px;
            font-weight: 700;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        th {
            background-color: rgba(0, 0, 0, 0.2);
            color: var(--neon-green);
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-completed {
            background-color: rgba(0, 200, 0, 0.2);
            color: #00ff00;
        }
        
        .status-pending {
            background-color: rgba(255, 200, 0, 0.2);
            color: #ffcc00;
        }
        
        .status-cancelled {
            background-color: rgba(255, 0, 0, 0.2);
            color: #ff6666;
        }
        
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .quick-link {
            background-color: rgba(0, 100, 50, 0.2);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: all 0.3s;
            border: 1px solid var(--translucent-bg);
        }
        
        .quick-link:hover {
            transform: translateY(-5px);
            background-color: rgba(185, 255, 0, 0.2);
        }
        
        .quick-link i {
            font-size: 32px;
            margin-bottom: 15px;
            color: var(--neon-green);
        }
        
        .quick-link-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .quick-link-desc {
            font-size: 12px;
            color: #ccc;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 10px 0;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="admin-container">        
        <!-- Main Content -->
        <div class="content">
            <div class="header">
                <div class="welcome-message">
                    Welcome back, <span class="admin-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                </div>
                <div class="header-buttons">
                    <button class="btn btn-primary" onclick="window.location.href='index.php'">
                        <i class="fas fa-eye"></i> View Site
                    </button>
                    <button class="btn btn-danger" onclick="window.location.href='logout.php'">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value"><?php echo number_format($stats['venues']); ?></div>
                        <div class="stat-label">Total Grounds</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value"><?php echo number_format($stats['users']); ?></div>
                        <div class="stat-label">Registered Users</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value"><?php echo number_format($stats['bookings']); ?></div>
                        <div class="stat-label">Total Bookings</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">₹<?php echo number_format($stats['revenue']); ?></div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Bookings -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recent Bookings</div>
                    <button class="btn btn-primary" onclick="window.location.href='admin-bookings.php'">
                        View All
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Ground</th>
                                <th>Date</th>
                                <th>Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recentBookings) > 0): ?>
                                <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td>#<?php echo $booking['booking_id']; ?></td>
                                        <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['venue_nm']); ?></td>
                                        <td><?php echo date('d M Y', strtotime($booking['bk_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($booking['bk_dur']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">No recent bookings found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Quick Actions</div>
                </div>
                
                <div class="quick-links">
                    <a href="admin-grounds.php" class="quick-link">
                        <i class="fas fa-plus-circle"></i>
                        <div class="quick-link-title">Manage Ground</div>
                        <div class="quick-link-desc">View and manage academies</div>
                    </a>
                    
                    <a href="admin-academy.php" class="quick-link">
                        <i class="fas fa-graduation-cap"></i>
                        <div class="quick-link-title">Manage Academies</div>
                        <div class="quick-link-desc">View and manage academies</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>