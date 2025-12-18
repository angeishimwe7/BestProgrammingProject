<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

$errors = [];
$success = '';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize inputs
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = trim($_POST['address']);
    $id_number = trim($_POST['id_number']);
    
    // Validation
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    } else {
        // Check if username already exists
        $check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_username->bind_param("s", $username);
        $check_username->execute();
        $check_username->store_result();
        if ($check_username->num_rows > 0) {
            $errors[] = "Username already taken";
        }
        $check_username->close();
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();
        if ($check_email->num_rows > 0) {
            $errors[] = "Email already registered";
        }
        $check_email->close();
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (empty($date_of_birth)) {
        $errors[] = "Date of birth is required";
    }
    
    if (empty($id_number)) {
        $errors[] = "ID number is required";
    }
    
    if (empty($address)) {
        $errors[] = "Address is required";
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate unique account number
        $account_number = 'ACC' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        
        // Set default values
        $status = 'active';
        $profile_image = NULL; // or 'default.jpg' if you have a default image
        
        // Insert user - matching your database columns
        $sql = "INSERT INTO users (username, email, password, full_name, phone, address, date_of_birth, id_number, profile_image, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        
        // Check if prepare failed
        if ($stmt === false) {
            $errors[] = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("ssssssssss", $username, $email, $hashed_password, $full_name, $phone, $address, $date_of_birth, $id_number, $profile_image, $status);
            
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                
                // Create default checking account
                $initial_balance = 0.00;
                $account_type = 'Checking';
                $status = 'active';
                
                $account_sql = "INSERT INTO accounts (user_id, account_number, account_type, balance, status, created_at) 
                               VALUES (?, ?, ?, ?, ?, NOW())";
$account_stmt = $conn->prepare($account_sql);

if ($account_stmt === false) {
    $errors[] = "Account creation error: " . $conn->error;
} else {
    $account_stmt->bind_param("issds", $user_id, $account_number, $account_type, $initial_balance, $status);

    if ($account_stmt->execute()) {
        $success = "Account created successfully! You can now login.";
    } else {
        $errors[] = "Failed to create account. Error: " . $account_stmt->error;
    } // <-- closes the inner if-else

    $account_stmt->close(); // <-- close once after the inner if-else
} // <-- closes the outer if-else


                
                // Optional: Auto-login after signup
                // $_SESSION['user_id'] = $user_id;
                // $_SESSION['username'] = $username;
                // $_SESSION['full_name'] = $full_name;
                // header('Location: dashboard.php');
                // exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Bank - Sign Up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .signup-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
        }
        
        .signup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .signup-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .signup-header p {
            opacity: 0.9;
        }
        
        .signup-form {
            padding: 40px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .error-messages {
            background: #fee;
            border-left: 4px solid #f44336;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .error-messages ul {
            list-style: none;
            color: #d32f2f;
        }
        
        .error-messages li {
            margin: 5px 0;
        }
        
        .success-message {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #2e7d32;
        }
        
        .btn-signup {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .signup-header {
                padding: 30px 20px;
            }
            
            .signup-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h1>🏦 Smart Bank</h1>
            <p>Create your account and start banking smarter</p>
        </div>
        
        <div class="signup-form">
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li>❌ <?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    ✅ <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" 
                               value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" 
                               placeholder="John Doe"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" 
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                               placeholder="johndoe123"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               placeholder="john@example.com"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                               placeholder="+250 123 456 789"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_number">ID Number *</label>
                        <input type="text" id="id_number" name="id_number" 
                               value="<?php echo isset($_POST['id_number']) ? htmlspecialchars($_POST['id_number']) : ''; ?>" 
                               placeholder="1234567890123456"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" 
                               value="<?php echo isset($_POST['date_of_birth']) ? htmlspecialchars($_POST['date_of_birth']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" 
                               placeholder="Minimum 6 characters"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               placeholder="Re-enter password"
                               required>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="address">Address *</label>
                        <textarea id="address" name="address" placeholder="Enter your full address" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                    </div>
                </div>
                
                <button type="submit" class="btn-signup">Create Account</button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>