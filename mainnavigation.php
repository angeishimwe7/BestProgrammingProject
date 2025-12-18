
<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Bank - <?php echo ucfirst(str_replace('.php', '', $current_page)); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <a href="../index.php">
                    <h1>🏦 Smart Bank</h1>
                </a>
            </div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    <a href="logout.php" class="btn-logout">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <nav class="main-nav">
        <div class="nav-container">
            <ul class="nav-menu">
                <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                    <a href="dashboard.php">
                        <span class="icon">🏠</span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                
                <li class="<?php echo ($current_page == 'accounts.php') ? 'active' : ''; ?>">
                    <a href="accounts.php">
                        <span class="icon">💳</span>
                        <span class="text">My Accounts</span>
                    </a>
                </li>
                
                <li class="<?php echo ($current_page == 'transfer.php') ? 'active' : ''; ?>">
                    <a href="transfer.php">
                        <span class="icon">💸</span>
                        <span class="text">Transfer</span>
                    </a>
                </li>
                
                <li class="<?php echo ($current_page == 'deposit.php') ? 'active' : ''; ?>">
                    <a href="deposit.php">
                        <span class="icon">💰</span>
                        <span class="text">Deposit</span>
                    </a>
                </li>
                
                <li class="<?php echo ($current_page == 'withdraw.php') ? 'active' : ''; ?>">
                    <a href="withdraw.php">
                        <span class="icon">🏧</span>
                        <span class="text">Withdraw</span>
                    </a>
                </li>
                
                <li class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>">
                    <a href="history.php">
                        <span class="icon">📊</span>
                        <span class="text">History</span>
                    </a>
                </li>
                
                <li class="<?php echo ($current_page == 'beneficiaries.php') ? 'active' : ''; ?>">
                    <a href="beneficiaries.php">
                        <span class="icon">👥</span>
                        <span class="text">Beneficiaries</span>
                    </a>
                </li>
                
                <li class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
                    <a href="profile.php">
                        <span class="icon">👤</span>
                        <span class="text">Profile</span>
                    </a>
                </li>
                
                <li class="<?php echo ($current_page == 'statement.php') ? 'active' : ''; ?>">
                    <a href="statement.php">
                        <span class="icon">📄</span>
                        <span class="text">Statement</span>
                    </a>
                </li>
            </ul>
            
            <!-- Mobile Menu Toggle -->
            <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <script>
        function toggleMobileMenu() {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.toggle('active');
        }
    </script>

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
</body>
</html>