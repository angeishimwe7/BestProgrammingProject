<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Terms of Service - Smart Bank</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="dashboard.php"><h1>🏦 Smart Bank</h1></a>
        </div>
    </header>

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

    <div class="content-wrapper">
        <h2>Terms of Service</h2>
        <p>By using Smart Bank services, you agree to our terms and conditions. Please read carefully before accessing your account.</p>
        <p>We reserve the right to modify these terms at any time without prior notice. Continued use of our services constitutes acceptance of the updated terms.</p>
    </div>

    <footer class="main-footer">
        <p>&copy; <?php echo date('Y'); ?> Smart Bank. All Rights Reserved.</p>
        <p>
            <a href="about.php">About Us</a> | 
            <a href="contact.php">Contact</a> | 
            <a href="terms.php">Terms of Service</a> | 
            <a href="privacy.php">Privacy Policy</a>
        </p>
    </footer>
</body>
</html>
