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
        // Prepare SQL statement to fetch user by email
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if user exists
        if($result->num_rows == 1){
            $user = $result->fetch_assoc();
            
            // Verify password
            if(password_verify($password, $user["password"])){
                // Password is correct, create session
                $_SESSION["user_email"] = $email; // Email is now the primary identifier
                $_SESSION["user_name"] = $user["user_name"];
                $_SESSION["user_type"] = $user["user_type"];
                
                // Redirect based on user type
                switch($user["user_type"]) {
                    case "admin":
                        header("Location: admin-dashboard.php");
                        break;
                    case "owner":
                            header("Location: admyadmindashboard.php");
                            break;
                    case "referee":
                        header("Location: refereedashboard.php");
                        break;
                    default: // normal users
                        header("Location: index.php");
                }
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
    <title>GAME DAY - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\main.css">
    <link rel="stylesheet" href="CSS\login.css">
</head>
<body>
<?php include 'header.php'; ?>
    
    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-shield user-icon"></i>
                <div class="login-title">WELCOME BACK</div>
                <div class="login-subtitle">Login to your account.</div>
            </div>

            <!-- Display errors if any -->
            <?php if (count($errors) > 0): ?>
                <?php foreach($errors as $error): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form id="LoginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                
                <div class="form-group">
                    <label for="Email">Email</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-envelope left-icon"></i>
                        <input type="email" id="Email" name="email" class="form-input" 
                               placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="Password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock lock-icon left-icon"></i>
                        <input type="password" id="Password" name="password" class="form-input" 
                               placeholder="Enter your password" required>
                        <i class="fa-solid fa-eye password-toggle" onclick="togglePasswordVisibility('Password')"></i>
                    </div>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="rememberMe" name="remember">
                    <label for="rememberMe">Remember me</label>
                </div>

                <button type="submit" name="submit" class="login-button">Login to GameDay</button>
            </form>

            <div class="forgot-password">
                <a href="signup.php">Don't have an Account? Sign Up</a>
            </div>
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

        document.getElementById('LoginForm').addEventListener('submit', function(event) {
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