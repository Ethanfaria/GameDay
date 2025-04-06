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
    
    // Set default user type to "normal"
    $user_type = "normal";

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
    } elseif (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        array_push($errors, "Email contains invalid characters");
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
    } else {
        // Check if phone number already exists in database
        $sql = "SELECT * FROM user WHERE user_ph = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $number);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            array_push($errors, "Phone number is already registered. Please use a different number.");
        }
        $stmt->close();
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
    } elseif (!preg_match("/[\W_]/", $password)) {
        array_push($errors, "Password must contain at least one special character");
    }    
    
    // Confirm passwords match
    if ($password !== $passwordRepeat) {
        array_push($errors, "Passwords do not match");
    }

    // If no errors, proceed with registration
    if (count($errors) == 0) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Store phone number as string to prevent conversion issues
        $phone_str = $number;
        
        // Prepare and bind parameters to prevent SQL injection
        $sql = "INSERT INTO user (user_name, user_ph, email, password, user_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $fullname, $phone_str, $email, $passwordHash, $user_type);
        
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
        .requirements {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
            margin-bottom: 10px;
            display: none;
        }
        
        .requirements.show {
            display: block;
        }
        
        .requirements ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .requirements li {
            margin-bottom: 2px;
        }
        
        .valid {
            color: green;
        }
        
        .invalid {
            color: red;
        }
    </style>
</head>
<body>
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
                        <i class="fas fa-user left-icon"></i>
                        <input type="text" id="Username" class="form-input" name="fullname" 
                               placeholder="Enter your username" value="<?php echo isset($fullname) ? htmlspecialchars($fullname) : ''; ?>" required>
                    </div>
                    <div id="usernameRequirements" class="requirements">
                        <ul>
                            <li id="usernameLength">At least 3 characters long</li>
                            <li id="usernameChars">Only letters, numbers, and underscores</li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-envelope left-icon"></i>
                        <input type="email" id="Email" class="form-input" name="email" 
                               placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                    </div>
                    <div id="emailRequirements" class="requirements">
                        <ul>
                            <li id="emailFormat">Valid email format (example@domain.com)</li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-phone left-icon"></i>
                        <input type="text" id="phone" class="form-input" name="number" 
                               placeholder="Enter your phone number (10 digits)" value="<?php echo isset($number) ? htmlspecialchars($number) : ''; ?>" required>
                    </div>
                    <div id="phoneRequirements" class="requirements">
                        <ul>
                            <li id="phoneDigits">Must be exactly 10 digits</li>
                            <li id="phoneNumbers">Must contain only numbers</li>
                            <li id="phoneVal">Number cannot be zero</li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock left-icon"></i>
                        <input type="password" id="Password" class="form-input" name="password" 
                               placeholder="Enter your password (min 8 characters)" required>
                        <i class="fa-solid fa-eye password-toggle" onclick="togglePasswordVisibility('Password')"></i>
                    </div>
                    <div id="passwordRequirements" class="requirements">
                        <ul>
                            <li id="passwordLength">At least 8 characters long</li>
                            <li id="passwordUpper">At least one uppercase letter</li>
                            <li id="passwordLower">At least one lowercase letter</li>
                            <li id="passwordNumber">At least one number</li>
                            <li id="passwordSpecial">At least one special character</li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Repeat-Password">Repeat Password</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock left-icon"></i>
                        <input type="password" id="Repeat-Password" class="form-input" name="repeat_password" 
                               placeholder="Enter your password again" required>
                        <i class="fa-solid fa-eye password-toggle" onclick="togglePasswordVisibility('Repeat-Password')"></i>
                    </div>
                    <div id="repeatPasswordRequirements" class="requirements">
                        <ul>
                            <li id="passwordMatch">Passwords must match</li>
                        </ul>
                    </div>
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

        // Show validation requirements on input focus
        document.getElementById('Username').addEventListener('focus', function() {
            document.getElementById('usernameRequirements').classList.add('show');
        });
        
        document.getElementById('Email').addEventListener('focus', function() {
            document.getElementById('emailRequirements').classList.add('show');
        });
        
        document.getElementById('phone').addEventListener('focus', function() {
            document.getElementById('phoneRequirements').classList.add('show');
        });
        
        document.getElementById('Password').addEventListener('focus', function() {
            document.getElementById('passwordRequirements').classList.add('show');
        });
        
        document.getElementById('Repeat-Password').addEventListener('focus', function() {
            document.getElementById('repeatPasswordRequirements').classList.add('show');
        });
        
        // Live validation feedback
        document.getElementById('Username').addEventListener('input', validateUsername);
        document.getElementById('Email').addEventListener('input', validateEmail);
        document.getElementById('phone').addEventListener('input', validatePhone);
        document.getElementById('Password').addEventListener('input', validatePassword);
        document.getElementById('Repeat-Password').addEventListener('input', validatePasswordMatch);
        
        function validateUsername() {
            const username = document.getElementById('Username').value;
            const lengthRequirement = document.getElementById('usernameLength');
            const charsRequirement = document.getElementById('usernameChars');
            
            if (username.length >= 3) {
                lengthRequirement.classList.add('valid');
                lengthRequirement.classList.remove('invalid');
            } else {
                lengthRequirement.classList.add('invalid');
                lengthRequirement.classList.remove('valid');
            }
            
            if (/^[a-zA-Z0-9_]+$/.test(username)) {
                charsRequirement.classList.add('valid');
                charsRequirement.classList.remove('invalid');
            } else {
                charsRequirement.classList.add('invalid');
                charsRequirement.classList.remove('valid');
            }
        }
        
        function validateEmail() {
            const email = document.getElementById('Email').value;
            const emailFormat = document.getElementById('emailFormat');
            const emailPattern = /^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            
            if (emailPattern.test(email)) {
                emailFormat.classList.add('valid');
                emailFormat.classList.remove('invalid');
            } else {
                emailFormat.classList.add('invalid');
                emailFormat.classList.remove('valid');
            }
        }
        
        function validatePhone() {
            const phone = document.getElementById('phone').value;
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
        
        function validatePassword() {
            const password = document.getElementById('Password').value;
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
            
            // If repeat password field has value, check match
            const repeatPassword = document.getElementById('Repeat-Password').value;
            if (repeatPassword) {
                validatePasswordMatch();
            }
        }

        function validatePasswordMatch() {
            const password = document.getElementById('Password').value;
            const repeatPassword = document.getElementById('Repeat-Password').value;
            const matchRequirement = document.getElementById('passwordMatch');
            
            if (password === repeatPassword) {
                matchRequirement.classList.add('valid');
                matchRequirement.classList.remove('invalid');
            } else {
                matchRequirement.classList.add('invalid');
                matchRequirement.classList.remove('valid');
            }
        }

        // Create function to add error messages at the top
        function displayErrorsAtTop(errors) {
            // Clear previous error messages
            const existingErrors = document.querySelectorAll('.error');
            existingErrors.forEach(error => error.remove());
            
            // Get login-header to insert errors after it
            const loginHeader = document.querySelector('.login-header');
            
            // Create and insert error messages
            errors.forEach(errorMsg => {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error';
                errorDiv.textContent = errorMsg;
                loginHeader.insertAdjacentElement('afterend', errorDiv);
            });
            
            // Scroll to top to show errors
            window.scrollTo(0, 0);
        }

        // Form submission validation
        document.getElementById('SignupForm').addEventListener('submit', function(event) {
            // Array to collect error messages
            const errorMessages = [];
            
            // Validate username
            const username = document.getElementById('Username').value;
            if (username.length < 3) {
                errorMessages.push("Username must be at least 3 characters long");
            }
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                errorMessages.push("Username can only contain letters, numbers, and underscores");
            }
            
            // Validate email
            const email = document.getElementById('Email').value;
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errorMessages.push("Email is not valid");
            }
            
            // Validate phone
            const phone = document.getElementById('phone').value;
            if (!/^[0-9]{10}$/.test(phone)) {
                errorMessages.push("Phone number must be exactly 10 digits");
            }
            
            // Validate password
            const password = document.getElementById('Password').value;
            if (password.length < 8) {
                errorMessages.push("Password must be at least 8 characters long");
            }
            if (!/[A-Z]/.test(password)) {
                errorMessages.push("Password must contain at least one uppercase letter");
            }
            if (!/[a-z]/.test(password)) {
                errorMessages.push("Password must contain at least one lowercase letter");
            }
            if (!/[0-9]/.test(password)) {
                errorMessages.push("Password must contain at least one number");
            }
            if (!/[\W_]/.test(password)) {
                errorMessages.push("Password must contain at least one special character");
            }

            // Validate password match
            const repeatPassword = document.getElementById('Repeat-Password').value;
            if (password !== repeatPassword) {
                errorMessages.push("Passwords do not match");
            }
            
            // If there are errors, prevent form submission and display them
            if (errorMessages.length > 0) {
                event.preventDefault(); // Prevent form submission
                displayErrorsAtTop(errorMessages);
            }
        });
    </script>
</body>
</html>