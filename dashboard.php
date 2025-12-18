<style>
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: #333;
    line-height: 1.6;
}

a {
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

/* Header Styles */
.main-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1.2rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo h1 {
    color: #fff;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: transform 0.3s ease;
}

.logo a:hover h1 {
    transform: scale(1.05);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    color: #fff;
}

.user-info span {
    font-size: 1rem;
    font-weight: 500;
}

.btn-logout {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.6rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-logout:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Navigation Styles */
.main-nav {
    background: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 76px;
    z-index: 999;
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 0.5rem;
    overflow-x: auto;
    scrollbar-width: none;
}

.nav-menu::-webkit-scrollbar {
    display: none;
}

.nav-menu li {
    flex-shrink: 0;
}

.nav-menu li a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    color: #555;
    font-weight: 500;
    position: relative;
    white-space: nowrap;
}

.nav-menu li a .icon {
    font-size: 1.3rem;
}

.nav-menu li a:hover {
    color: #667eea;
    background: rgba(102, 126, 234, 0.05);
}

.nav-menu li.active a {
    color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-bottom: 3px solid #667eea;
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    padding: 0.5rem;
    position: absolute;
    right: 2rem;
    top: 50%;
    transform: translateY(-50%);
}

.mobile-menu-toggle span {
    width: 25px;
    height: 3px;
    background: #667eea;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.mobile-menu-toggle:hover span {
    background: #764ba2;
}

/* Footer Styles */
.main-footer {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: #fff;
    margin-top: auto;
    padding: 2rem 0;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
}

.footer-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

.footer-content {
    text-align: center;
}

.footer-content p {
    margin: 0.8rem 0;
    opacity: 0.9;
}

.footer-content a {
    color: #fff;
    text-decoration: underline;
    font-weight: 500;
}

.footer-content a:hover {
    opacity: 0.8;
    color: #ffd700;
}

/* Content Area Wrapper */
.content-wrapper {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
    min-height: calc(100vh - 300px);
}

/* Card Styles for Dashboard/Content */
.card {
    background: #fff;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .header-container,
    .nav-container,
    .footer-container {
        padding: 1rem 1.5rem;
    }
    
    .content-wrapper {
        padding: 0 1.5rem;
    }
}

@media (max-width: 768px) {
    .logo h1 {
        font-size: 1.5rem;
    }
    
    .user-info span {
        display: none;
    }
    
    .btn-logout {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .mobile-menu-toggle {
        display: flex;
    }
    
    .nav-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        flex-direction: column;
        gap: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .nav-menu.active {
        max-height: 600px;
    }
    
    .nav-menu li {
        width: 100%;
    }
    
    .nav-menu li a {
        padding: 1rem 2rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .nav-menu li.active a {
        border-bottom: 1px solid #667eea;
        border-left: 4px solid #667eea;
    }
    
    .main-nav {
        top: 68px;
    }
}

@media (max-width: 480px) {
    .header-container,
    .nav-container,
    .footer-container {
        padding: 1rem;
    }
    
    .content-wrapper {
        padding: 0 1rem;
        margin: 1rem auto;
    }
    
    .logo h1 {
        font-size: 1.3rem;
    }
    
    .card {
        padding: 1.5rem;
    }
    
    .footer-content p {
        font-size: 0.9rem;
    }
}

/* Animation for page load */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card,
.nav-menu li {
    animation: fadeIn 0.5s ease forwards;
}

.nav-menu li:nth-child(1) { animation-delay: 0.1s; }
.nav-menu li:nth-child(2) { animation-delay: 0.15s; }
.nav-menu li:nth-child(3) { animation-delay: 0.2s; }
.nav-menu li:nth-child(4) { animation-delay: 0.25s; }
.nav-menu li:nth-child(5) { animation-delay: 0.3s; }
.nav-menu li:nth-child(6) { animation-delay: 0.35s; }
.nav-menu li:nth-child(7) { animation-delay: 0.4s; }
.nav-menu li:nth-child(8) { animation-delay: 0.45s; }
.nav-menu li:nth-child(9) { animation-delay: 0.5s; }

/* Dashboard Specific Styles */
.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-header h2 {
    font-size: 2.2rem;
    color: #fff;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.date-time {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
}

/* Balance Overview */
.balance-overview {
    margin-bottom: 2rem;
}

.balance-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2.5rem;
    display: flex;
    align-items: center;
    gap: 2rem;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    color: #fff;
}

.balance-icon {
    font-size: 4rem;
    background: rgba(255, 255, 255, 0.2);
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    backdrop-filter: blur(10px);
}

.balance-info h3 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    opacity: 0.9;
}

.balance-amount {
    font-size: 3rem;
    font-weight: 700;
    margin: 0.5rem 0;
}

.balance-trend {
    font-size: 1rem;
    padding: 0.3rem 1rem;
    border-radius: 20px;
    display: inline-block;
    margin-top: 0.5rem;
}

.balance-trend.positive {
    background: rgba(46, 213, 115, 0.3);
}

/* Quick Actions */
.quick-actions {
    margin-bottom: 2rem;
}

.quick-actions h3 {
    color: #fff;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: #fff;
    padding: 2rem;
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.action-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

.action-icon {
    font-size: 3rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.action-text {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

/* Accounts Section */
.accounts-section {
    margin-bottom: 2rem;
}

.accounts-section h3 {
    color: #fff;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

.accounts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.account-card {
    background: #fff;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.account-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.account-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.account-icon {
    font-size: 2.5rem;
}

.account-number {
    color: #999;
    font-size: 0.9rem;
}

.account-card h4 {
    color: #555;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.account-balance {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin: 1rem 0;
}

.view-details {
    color: #667eea;
    font-weight: 600;
    display: inline-block;
    margin-top: 0.5rem;
}

.view-details:hover {
    color: #764ba2;
}

/* Transactions Section */
.transactions-section {
    margin-bottom: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-header h3 {
    color: #fff;
    font-size: 1.5rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

.view-all {
    color: #fff;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    backdrop-filter: blur(10px);
}

.view-all:hover {
    background: rgba(255, 255, 255, 0.3);
}

.transactions-table {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.transactions-table table {
    width: 100%;
    border-collapse: collapse;
}

.transactions-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

.transactions-table th {
    padding: 1.2rem;
    text-align: left;
    font-weight: 600;
}

.transactions-table td {
    padding: 1.2rem;
    border-bottom: 1px solid #f0f0f0;
}

.transactions-table tbody tr:hover {
    background: rgba(102, 126, 234, 0.05);
}

.transactions-table tbody tr:last-child td {
    border-bottom: none;
}

.amount {
    font-weight: 700;
    font-size: 1.1rem;
}

.amount.credit {
    color: #2ecc71;
}

.amount.debit {
    color: #e74c3c;
}

.status-badge {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.credit {
    background: rgba(46, 204, 113, 0.2);
    color: #27ae60;
}

.status-badge.debit {
    background: rgba(231, 76, 60, 0.2);
    color: #c0392b;
}

/* Tips Section */
.tips-section {
    margin-bottom: 2rem;
}

.tip-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 15px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 4px 20px rgba(240, 147, 251, 0.3);
    color: #fff;
}

.tip-icon {
    font-size: 3rem;
    background: rgba(255, 255, 255, 0.2);
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    flex-shrink: 0;
}

.tip-content h4 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.tip-content p {
    font-size: 1rem;
    opacity: 0.95;
    line-height: 1.6;
}

/* Responsive Adjustments for Dashboard */
@media (max-width: 768px) {
    .dashboard-header h2 {
        font-size: 1.8rem;
    }
    
    .balance-card {
        flex-direction: column;
        text-align: center;
        padding: 2rem;
    }
    
    .balance-icon {
        width: 80px;
        height: 80px;
        font-size: 3rem;
    }
    
    .balance-amount {
        font-size: 2.5rem;
    }
    
    .action-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .accounts-grid {
        grid-template-columns: 1fr;
    }
    
    .transactions-table {
        overflow-x: auto;
    }
    
    .tip-card {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .dashboard-header h2 {
        font-size: 1.5rem;
    }
    
    .action-grid {
        grid-template-columns: 1fr;
    }
    
    .balance-amount {
        font-size: 2rem;
    }
    
    .transactions-table th,
    .transactions-table td {
        padding: 0.8rem;
        font-size: 0.9rem;
    }
}
</style>

<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['full_name'] = 'Test User';
}

// Sample data (in real application, fetch from database)
$accounts = [
    [
        'type' => 'Savings Account',
        'number' => '****1234',
        'balance' => 15750.50,
        'icon' => '💰'
    ],
    [
        'type' => 'Checking Account',
        'number' => '****5678',
        'balance' => 8420.75,
        'icon' => '💳'
    ],
    [
        'type' => 'Credit Card',
        'number' => '****9012',
        'balance' => 2150.00,
        'icon' => '💎'
    ]
];

$recent_transactions = [
    ['date' => '2024-12-18', 'description' => 'Grocery Store', 'amount' => -125.50, 'type' => 'debit'],
    ['date' => '2024-12-17', 'description' => 'Salary Deposit', 'amount' => 3500.00, 'type' => 'credit'],
    ['date' => '2024-12-16', 'description' => 'Electric Bill', 'amount' => -85.00, 'type' => 'debit'],
    ['date' => '2024-12-15', 'description' => 'Online Transfer', 'amount' => -500.00, 'type' => 'debit'],
    ['date' => '2024-12-14', 'description' => 'Refund', 'amount' => 45.00, 'type' => 'credit']
];

$total_balance = array_sum(array_column($accounts, 'balance'));
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Bank - Dashboard</title>
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
            </ul>
            
            <!-- Mobile Menu Toggle -->
            <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <!-- Welcome Section -->
        <div class="dashboard-header">
            <h2>Welcome Back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! 👋</h2>
            <p class="date-time">
                <?php echo date('l, F j, Y'); ?> | <span id="current-time"></span>
            </p>
        </div>

        <!-- Total Balance Card -->
        <div class="balance-overview">
            <div class="balance-card">
                <div class="balance-icon">💼</div>
                <div class="balance-info">
                    <h3>Total Balance</h3>
                    <p class="balance-amount">$<?php echo number_format($total_balance, 2); ?></p>
                    <span class="balance-trend positive">↑ 12.5% from last month</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-grid">
                <a href="transfer.php" class="action-card">
                    <span class="action-icon">💸</span>
                    <span class="action-text">Transfer Money</span>
                </a>
                <a href="deposit.php" class="action-card">
                    <span class="action-icon">💰</span>
                    <span class="action-text">Deposit</span>
                </a>
                <a href="withdraw.php" class="action-card">
                    <span class="action-icon">🏧</span>
                    <span class="action-text">Withdraw</span>
                </a>
                <a href="history.php" class="action-card">
                    <span class="action-icon">📄</span>
                    <span class="action-text">History</span>
                </a>
            </div>
        </div>

        <!-- Accounts Summary -->
        <div class="accounts-section">
            <h3>My Accounts</h3>
            <div class="accounts-grid">
                <?php foreach ($accounts as $account): ?>
                    <div class="account-card">
                        <div class="account-header">
                            <span class="account-icon"><?php echo $account['icon']; ?></span>
                            <span class="account-number"><?php echo $account['number']; ?></span>
                        </div>
                        <h4><?php echo $account['type']; ?></h4>
                        <p class="account-balance">$<?php echo number_format($account['balance'], 2); ?></p>
                        <a href="accounts.php" class="view-details">View Details →</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="transactions-section">
            <div class="section-header">
                <h3>Recent Transactions</h3>
                <a href="history.php" class="view-all">View All →</a>
            </div>
            <div class="transactions-table">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_transactions as $transaction): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($transaction['date'])); ?></td>
                                <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                                <td class="amount <?php echo $transaction['type']; ?>">
                                    <?php echo ($transaction['amount'] > 0 ? '+' : '') . '$' . number_format(abs($transaction['amount']), 2); ?>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $transaction['type']; ?>">
                                        <?php echo ucfirst($transaction['type']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Financial Tips -->
        <div class="tips-section">
            <div class="tip-card">
                <span class="tip-icon">💡</span>
                <div class="tip-content">
                    <h4>Financial Tip of the Day</h4>
                    <p>Consider setting up automatic transfers to your savings account. Even small amounts add up over time!</p>
                </div>
            </div>
        </div>
    </div>

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
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.toggle('active');
        }

        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = timeString;
        }
        
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>