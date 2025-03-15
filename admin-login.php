<?php
// Start session
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "GameDay";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = array();
$success_message = "";

// Check if form is submitted
if(isset($_POST["submit"])){
    // Get form data
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    
    // Validate input
    if(empty($email)){
        array_push($errors, "Email is required");
    }
    
    if(empty($password)){
        array_push($errors, "Password is required");
    }
    
    // If no validation errors, proceed with login
    if(count($errors) == 0){
        // Prepare SQL statement to fetch admin by email
        $sql = "SELECT * FROM admin WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if admin exists
        if($result->num_rows == 1){
            $admin = $result->fetch_assoc();
            
            // Verify password
            if(password_verify($password, $admin["password"])){
                // Password is correct, create admin session
                $_SESSION["admin_id"] = $admin["admin_id"];
                $_SESSION["admin_name"] = $admin["name"];
                $_SESSION["admin_email"] = $email;
                
                // Redirect to admin dashboard
                header("Location: admin-dashboard.php");
                exit();
            } else {
                array_push($errors, "Invalid email or password");
            }
        } else {
            array_push($errors, "Invalid email or password");
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\main.css">
    <link rel="stylesheet" href="CSS\login.css">
    <style>
        .admin-login-card {
            border: 2px solid var(--neon-green);
            box-shadow: 0 0 20px rgba(185, 255, 0, 0.3);
        }
        
        .admin-icon {
            color: var(--neon-green);
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .admin-title {
            color: var(--neon-green);
        }
        
        .admin-warning {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .back-to-main {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--neon-green);
            text-decoration: none;
        }
        
        .back-to-main:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">GAME DAY</div>
    </nav>
    
    <!-- Admin Login Container -->
    <div class="login-container">
        <div class="login-card admin-login-card">
            <div class="login-header">
                <i class="fas fa-user-shield admin-icon"></i>
                <div class="login-title admin-title">ADMIN LOGIN</div>
                <div class="login-subtitle">Access administrator panel</div>
            </div>

            <!-- Display errors if any -->
            <?php if (count($errors) > 0): ?>
                <?php foreach($errors as $error): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form id="AdminLoginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                
                <div class="form-group">
                    <label for="Email">Admin Email</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-envelope left-icon"></i>
                        <input type="email" id="Email" name="email" class="form-input" 
                               placeholder="Enter admin email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="Password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock lock-icon left-icon"></i>
                        <input type="password" id="Password" name="password" class="form-input" 
                               placeholder="Enter admin password" required>
                        <i class="fa-solid fa-eye password-toggle" onclick="togglePasswordVisibility('Password')"></i>
                    </div>
                </div>

                <button type="submit" name="submit" class="login-button">Login to Admin Panel</button>
            </form>

            <div class="admin-warning">
                <i class="fas fa-exclamation-triangle"></i> This area is restricted to authorized personnel only.
            </div>
            
            <a href="login.php" class="back-to-main">
                <i class="fas fa-arrow-left"></i> Back to User Login
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 GAME DAY. All Rights Reserved.</p>
    </div>

    <!-- Client-side validation script -->
    <script>
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = event.currentTarget;
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        document.getElementById('AdminLoginForm').addEventListener('submit', function(event) {
            let hasErrors = false;
            const email = document.getElementById('Email');
            const password = document.getElementById('Password');
            
            // Reset previous error styling
            document.querySelectorAll('.form-input').forEach(input => {
                input.classList.remove('input-error');
            });
            
            // Email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                email.classList.add('input-error');
                hasErrors = true;
            }
            
            // Password validation - just checking if it's empty
            if (password.value.trim() === '') {
                password.classList.add('input-error');
                hasErrors = true;
            }
            
            // If client-side validation fails, prevent form submission
            if (hasErrors) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>