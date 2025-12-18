<?php
session_start();

// Sample session for testing (if not logged in)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['full_name'] = 'Test User';
}

// Get current page name for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Smart Bank</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <a href="dashboard.php"><h1>🏦 Smart Bank</h1></a>
            </div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <ul class="nav-menu">
                <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                    <a href="dashboard.php"><span class="icon">🏠</span> Dashboard</a>
                </li>
                <li class="<?php echo ($current_page == 'accounts.php') ? 'active' : ''; ?>">
                    <a href="accounts.php"><span class="icon">💳</span> My Accounts</a>
                </li>
                <li class="<?php echo ($current_page == 'transfer.php') ? 'active' : ''; ?>">
                    <a href="transfer.php"><span class="icon">💸</span> Transfer</a>
                </li>
                <li class="<?php echo ($current_page == 'deposit.php') ? 'active' : ''; ?>">
                    <a href="deposit.php"><span class="icon">💰</span> Deposit</a>
                </li>
                <li class="<?php echo ($current_page == 'withdraw.php') ? 'active' : ''; ?>">
                    <a href="withdraw.php"><span class="icon">🏧</span> Withdraw</a>
                </li>
                <li class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>">
                    <a href="history.php"><span class="icon">📊</span> History</a>
                </li>
                <li class="<?php echo ($current_page == 'beneficiaries.php') ? 'active' : ''; ?>">
                    <a href="beneficiaries.php"><span class="icon">👥</span> Beneficiaries</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content-wrapper">
        <h2>About Us</h2>
        <p>Smart Bank is a modern online banking platform designed to make your financial life easier. Our mission is to provide secure, fast, and convenient banking services for everyone.</p>
        <p>We offer checking and savings accounts, transfers, deposits, withdrawals, and real-time account monitoring.</p>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-container">
            <p>&copy; <?php echo date('Y'); ?> Smart Bank. All Rights Reserved.</p>
            <p>
                <a href="about.php">About Us</a> | 
                <a href="contact.php">Contact</a> | 
                <a href="terms.php">Terms of Service</a> | 
                <a href="privacy.php">Privacy Policy</a>
            </p>
        </div>
    </footer>
</body>
</html>
