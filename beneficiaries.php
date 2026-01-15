<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection
include 'connection.php';

// Add new beneficiary
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_beneficiary'])) {
    $name = $conn->real_escape_string($_POST['beneficiary_name']);
    $account = $conn->real_escape_string($_POST['account_number']);
    $bank = $conn->real_escape_string($_POST['bank_name']);
    $relationship = $conn->real_escape_string($_POST['relationship']);

    $insert_sql = "INSERT INTO beneficiaries (user_id, beneficiary_name, account_number, bank_name, relationship) 
                   VALUES ('$user_id', '$name', '$account', '$bank', '$relationship')";
    if ($conn->query($insert_sql)) {
        $success_msg = "Beneficiary added successfully!";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}

// Fetch beneficiaries for the logged-in user
$result = $conn->query("SELECT * FROM beneficiaries ORDER BY created_at DESC");
$beneficiaries = $result->fetch_all(MYSQLI_ASSOC);

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Bank - Beneficiaries</title>
    <link rel="stylesheet" href="style.css">
    <style>
    /* Smaller Form Background */
    .beneficiary-form {
        background: rgba(255, 255, 255, 0.95);
        padding: 1.2rem 1.5rem;  /* Reduced padding */
        border-radius: 12px;
        margin-bottom: 1.5rem;
        max-width: 600px;       /* Limit width */
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .beneficiary-form h3 {
        margin-bottom: 1rem;
        color: #333;
        font-size: 1.3rem;      /* Smaller title */
    }

    .beneficiary-form input, 
    .beneficiary-form select {
        width: 90%;
        padding: 0.6rem 0.9rem;  /* Reduced padding */
        margin-bottom: 0.8rem;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 0.95rem;      /* Slightly smaller font */
    }

    .beneficiary-form button {
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
    }

    /* Message Styling - White Background */
    .message {
        padding: 0.8rem 1rem;
        margin-bottom: 1rem;
        border-radius: 10px;
        background: #fff;   /* White background */
        font-weight: 600;
    }

    .success { color: #27ae60; border: 1px solid #27ae60; }
    .error { color: #c0392b; border: 1px solid #c0392b; }
</style>

</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <a href="dashboard.php"><h1>🏦 Smart Bank</h1></a>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <ul class="nav-menu">
                <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><a href="dashboard.php"><span class="icon">🏠</span> Dashboard</a></li>
                <li class="<?php echo ($current_page == 'accounts.php') ? 'active' : ''; ?>"><a href="accounts.php"><span class="icon">💳</span> My Accounts</a></li>
                <li class="<?php echo ($current_page == 'transfer.php') ? 'active' : ''; ?>"><a href="transfer.php"><span class="icon">💸</span> Transfer</a></li>
                <li class="<?php echo ($current_page == 'deposit.php') ? 'active' : ''; ?>"><a href="deposit.php"><span class="icon">💰</span> Deposit</a></li>
                <li class="<?php echo ($current_page == 'withdraw.php') ? 'active' : ''; ?>"><a href="withdraw.php"><span class="icon">🏧</span> Withdraw</a></li>
                <li class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>"><a href="history.php"><span class="icon">📊</span> History</a></li>
                <li class="<?php echo ($current_page == 'beneficiaries.php') ? 'active' : ''; ?>"><a href="beneficiaries.php"><span class="icon">👥</span> Beneficiaries</a></li>
                <li class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>"><a href="profile.php"><span class="icon">👤</span> Profile</a></li>
                <li class="<?php echo ($current_page == 'statement.php') ? 'active' : ''; ?>"><a href="statement.php"><span class="icon">📄</span> Statement</a></li>
            </ul>

            <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="content-wrapper">
        <h2 style="color:#fff; margin-bottom:1.5rem;">My Beneficiaries</h2>

        <!-- Success/Error Messages -->
        <?php if(isset($success_msg)): ?>
            <div class="message success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <?php if(isset($error_msg)): ?>
            <div class="message error"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <!-- Add New Beneficiary Form -->
        <div class="beneficiary-form">
            <h3>Add New Beneficiary</h3>
            <form method="POST" action="">
                <input type="text" name="beneficiary_name" placeholder="Beneficiary Name" required>
                <input type="text" name="account_number" placeholder="Account Number" required>
                <input type="text" name="bank_name" placeholder="Bank Name">
                <input type="text" name="relationship" placeholder="Relationship">
                <button type="submit" name="add_beneficiary">Add Beneficiary</button>
            </form>
        </div>

        <!-- Beneficiaries Table -->
        <div class="beneficiaries-table">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Account Number</th>
                        <th>Bank Name</th>
                        <th>Relationship</th>
                        <th>Added On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($beneficiaries)): ?>
                        <?php foreach($beneficiaries as $b): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($b['beneficiary_name']); ?></td>
                                <td><?php echo htmlspecialchars($b['account_number']); ?></td>
                                <td><?php echo htmlspecialchars($b['bank_name']); ?></td>
                                <td><?php echo htmlspecialchars($b['relationship']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($b['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center;">No beneficiaries found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-content">
                <p>&copy; <?php echo date('Y'); ?> Smart Bank. All Rights Reserved.</p>
                <p>
                    <a href="about.php">About Us</a> | 
                    <a href="contact.php">Contact</a> | 
                    <a href="terms.php">Terms of Service</a> | 
                    <a href="privacy.php">Privacy Policy</a>
                </p>
                <p>Need help? <a href="support.php">Contact Support</a></p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            document.querySelector('.nav-menu').classList.toggle('active');
        }
    </script>
</body>
</html>
