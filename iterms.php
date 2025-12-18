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
        <ul class="nav-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="accounts.php">My Accounts</a></li>
            <li><a href="transfer.php">Transfer</a></li>
            <li><a href="deposit.php">Deposit</a></li>
            <li><a href="withdraw.php">Withdraw</a></li>
            <li><a href="history.php">History</a></li>
            <li><a href="beneficiaries.php">Beneficiaries</a></li>
        </ul>
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
