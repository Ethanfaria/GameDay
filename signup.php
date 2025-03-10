<?php
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

if(isset($_POST["submit"])){
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $number = trim($_POST["number"]);
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    // Validate fullname (username)
    if (empty($fullname)) {
        array_push($errors, "Username is required");
    } elseif (strlen($fullname) < 3) {
        array_push($errors, "Username must be at least 3 characters long");
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $fullname)) {
        array_push($errors, "Username can only contain letters, numbers, and underscores");
    }

    // Validate email
    if (empty($email)) {
        array_push($errors, "Email is required");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    } else {
        // Check if email already exists in database
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            array_push($errors, "Email already exists. Please use a different email or login");
        }
        $stmt->close();
    }

    // Validate phone number
    if (empty($number)) {
        array_push($errors, "Phone number is required");
    } elseif (!preg_match("/^[0-9]{10}$/", $number)) {
        array_push($errors, "Phone number must be 10 digits");
    }

    // Validate password
    if (empty($password)) {
        array_push($errors, "Password is required");
    } elseif (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    } elseif (!preg_match("/[A-Z]/", $password)) {
        array_push($errors, "Password must contain at least one uppercase letter");
    } elseif (!preg_match("/[a-z]/", $password)) {
        array_push($errors, "Password must contain at least one lowercase letter");
    } elseif (!preg_match("/[0-9]/", $password)) {
        array_push($errors, "Password must contain at least one number");
    }

    // Confirm passwords match
    if ($password !== $passwordRepeat) {
        array_push($errors, "Passwords do not match");
    }

    // If no errors, proceed with registration
    if (count($errors) == 0) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Convert phone number to integer for the database
        $phone_int = (int)$number;
        
        // Prepare and bind parameters to prevent SQL injection
        // Use the correct table name (user) and column names (user_name, user_ph, email, password)
        $sql = "INSERT INTO user (user_name, user_ph, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siss", $fullname, $phone_int, $email, $passwordHash);
        
        // Execute the query
        if ($stmt->execute()) {
            $success_message = "Registration successful! You can now <a href='login.php'>login</a>.";
            // Reset form fields after successful submission
            $fullname = $email = $number = "";
        } else {
            array_push($errors, "Something went wrong: " . $stmt->error);
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
    <title>GAME DAY - Sign Up</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\main.css">
    <link rel="stylesheet" href="CSS\login.css">
    <style>
        .error {
            color: #ff0000;
            background-color: #ffe6e6;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ff9999;
        }
        
        .success {
            color: #006600;
            background-color: #e6ffe6;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #99ff99;
        }
        
        .input-error {
            border: 1px solid #ff0000 !important;
        }
    </style>
</head>
<?php include 'header.php'; ?>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-shield user-icon"></i>
                <div class="login-title">WELCOME TO GAMEDAY</div>
                <div class="login-subtitle">Sign up and create an account</div>
            </div>

            <!-- Display errors if any -->
            <?php if (count($errors) > 0): ?>
                <?php foreach($errors as $error): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <!-- Display success message if registration is successful -->
            <?php if (!empty($success_message)): ?>
                <div class="success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <form id="SignupForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="Username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="Username" class="form-input" name="fullname" 
                               placeholder="Enter your username" value="<?php echo isset($fullname) ? htmlspecialchars($fullname) : ''; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="Email" class="form-input" name="email" 
                               placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-phone"></i>
                        <input type="text" id="phone" class="form-input" name="number" 
                               placeholder="Enter your phone number (10 digits)" value="<?php echo isset($number) ? htmlspecialchars($number) : ''; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="Password" class="form-input" name="password" 
                               placeholder="Enter your password (min 8 characters)" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Repeat-Password">Repeat Password</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="Repeat-Password" class="form-input" name="repeat_password" 
                               placeholder="Enter your password again" required>
                    </div>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe">Remember me</label>
                </div>

                <button type="submit" name="submit" class="login-button">Sign up to GameDay</button>
            </form>

            <div class="forgot-password">
                <a href="login.php">Already have an account? Login here</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 GAME DAY. All Rights Reserved.</p>
    </div>

    <!-- Client-side validation script -->
    <script>
        document.getElementById('SignupForm').addEventListener('submit', function(event) {
            let hasErrors = false;
            const username = document.getElementById('Username');
            const email = document.getElementById('Email');
            const phone = document.getElementById('phone');
            const password = document.getElementById('Password');
            const repeatPassword = document.getElementById('Repeat-Password');
            
            // Reset previous error styling
            document.querySelectorAll('.form-input').forEach(input => {
                input.classList.remove('input-error');
            });
            
            // Username validation
            if (username.value.trim().length < 3) {
                username.classList.add('input-error');
                hasErrors = true;
            }
            
            // Email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                email.classList.add('input-error');
                hasErrors = true;
            }
            
            // Phone validation
            const phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(phone.value)) {
                phone.classList.add('input-error');
                hasErrors = true;
            }
            
            // Password validation
            if (password.value.length < 8) {
                password.classList.add('input-error');
                hasErrors = true;
            }
            
            // Password match validation
            if (password.value !== repeatPassword.value) {
                password.classList.add('input-error');
                repeatPassword.classList.add('input-error');
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