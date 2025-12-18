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
    ['date' => '2025-12-18', 'description' => 'Grocery Store', 'amount' => -125.50, 'type' => 'debit'],
    ['date' => '2025-12-17', 'description' => 'Salary Deposit', 'amount' => 3500.00, 'type' => 'credit'],
    ['date' => '2025-12-16', 'description' => 'Electric Bill', 'amount' => -85.00, 'type' => 'debit'],
    ['date' => '2025-12-15', 'description' => 'Online Transfer', 'amount' => -500.00, 'type' => 'debit'],
    ['date' => '2025-12-14', 'description' => 'Refund', 'amount' => 45.00, 'type' => 'credit']
];

$total_balance = array_sum(array_column($accounts, 'balance'));

// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Bank - Dashboard</title>
<style>
/* ========== RESET & BASE ========== */
* {margin:0; padding:0; box-sizing:border-box;}
body {
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height:100vh; color:#fff; line-height:1.6;
}
a {text-decoration:none; color:inherit; transition:all 0.3s ease;}

/* ========== HEADER ========== */
.main-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    box-shadow:0 4px 20px rgba(0,0,0,0.1);
    position:sticky; top:0; z-index:1000;
}
.header-container {
    max-width:1400px; margin:0 auto;
    padding:1.2rem 2rem;
    display:flex; justify-content:space-between; align-items:center;
}
.logo h1 {
    color:#fff; font-size:2rem; font-weight:700;
    display:flex; align-items:center; gap:0.5rem;
    transition: transform 0.3s ease;
}
.logo a:hover h1 {transform:scale(1.05);}
.user-info {display:flex; align-items:center; gap:1.5rem; color:#fff;}
.user-info span {font-size:1rem; font-weight:500;}
.btn-logout {
    background: rgba(255,255,255,0.2); padding:0.6rem 1.5rem; border-radius:25px;
    font-weight:600; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.3);
}
.btn-logout:hover {
    background: rgba(255,255,255,0.3); transform:translateY(-2px);
    box-shadow:0 4px 12px rgba(0,0,0,0.2);
}

/* ========== NAVIGATION ========== */
.main-nav {
    background:#fff; color:#555; box-shadow:0 2px 10px rgba(0,0,0,0.1);
    position:sticky; top:76px; z-index:999;
}
.nav-container {max-width:1400px; margin:0 auto; padding:0 2rem; position:relative;}
.nav-menu {display:flex; list-style:none; gap:0.5rem; overflow-x:auto; scrollbar-width:none;}
.nav-menu::-webkit-scrollbar {display:none;}
.nav-menu li {flex-shrink:0;}
.nav-menu li a {
    display:flex; align-items:center; gap:0.5rem; padding:1rem 1.5rem;
    color:#555; font-weight:500; position:relative; white-space:nowrap;
}
.nav-menu li a .icon {font-size:1.3rem;}
.nav-menu li a:hover {color:#667eea; background:rgba(102,126,234,0.05);}
.nav-menu li.active a {
    color:#667eea; background:linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
    border-bottom:3px solid #667eea;
}
.mobile-menu-toggle {display:none; flex-direction:column; gap:5px; cursor:pointer; padding:0.5rem; position:absolute; right:2rem; top:50%; transform:translateY(-50%);}
.mobile-menu-toggle span {width:25px; height:3px; background:#667eea; border-radius:3px; transition:all 0.3s ease;}
.mobile-menu-toggle:hover span {background:#764ba2;}

/* ========== CONTENT WRAPPER ========== */
.content-wrapper {max-width:1400px; margin:2rem auto; padding:0 2rem; min-height:calc(100vh - 300px);}

/* ========== BALANCE CARD ========== */
.balance-overview .balance-card {
    background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius:20px; padding:2.5rem; display:flex; align-items:center; gap:2rem;
    box-shadow:0 10px 40px rgba(102,126,234,0.3); color:#fff; margin-bottom:2rem;
}
.balance-icon {font-size:4rem; background:rgba(255,255,255,0.2); width:100px; height:100px; display:flex; align-items:center; justify-content:center; border-radius:20px; backdrop-filter:blur(10px);}
.balance-info h3 {font-size:1.2rem; margin-bottom:0.5rem; opacity:0.9;}
.balance-amount {font-size:3rem; font-weight:700; margin:0.5rem 0;}
.balance-trend.positive {background: rgba(46,213,115,0.3); padding:0.3rem 1rem; border-radius:20px; display:inline-block; margin-top:0.5rem;}

/* ========== REPORT SECTION ========== */
.report-section {
    background: rgba(255,255,255,0.1); backdrop-filter:blur(15px);
    border-radius:15px; padding:2rem; margin-bottom:2rem;
    box-shadow:0 10px 30px rgba(0,0,0,0.2); color:#fff;
}
.report-section h3 {
    font-size:1.8rem; margin-bottom:1.5rem; text-shadow:1px 1px 4px rgba(0,0,0,0.3);
}
.report-table-container {overflow-x:auto;}
.report-table {
    width:100%; border-collapse:collapse;
    background: rgba(255,255,255,0.05); border-radius:10px;
}
.report-table th, .report-table td {
    padding:1rem; text-align:left; border-bottom:1px solid rgba(255,255,255,0.2); font-size:1rem;
}
.report-table th {
    background: rgba(102,126,234,0.7); color:#fff; text-transform:uppercase;
}
.report-table tr:hover {background: rgba(255,255,255,0.1);}
.transaction-list {list-style:none; padding-left:0; margin:0;}
.transaction-list li {margin-bottom:0.3rem; font-size:0.95rem;}
.transaction-list li .credit {color:#2ecc71; font-weight:600;}
.transaction-list li .debit {color:#e74c3c; font-weight:600;}

/* ========== FOOTER ========== */
.main-footer {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: #fff; margin-top: auto; padding:2rem 0; box-shadow:0 -4px 20px rgba(0,0,0,0.1);
}
.footer-container {max-width:1400px; margin:0 auto; padding:0 2rem;}
.footer-content {text-align:center;}
.footer-content p {margin:0.8rem 0; opacity:0.9;}
.footer-content a {color:#fff; text-decoration:underline; font-weight:500;}
.footer-content a:hover {opacity:0.8; color:#ffd700;}

/* ========== RESPONSIVE ========== */
@media (max-width:1024px){.header-container,.nav-container,.footer-container{padding:1rem 1.5rem;}.content-wrapper{padding:0 1.5rem;}}
@media (max-width:768px){
    .logo h1{font-size:1.5rem;}
    .user-info span{display:none;}
    .btn-logout{padding:0.5rem 1rem; font-size:0.9rem;}
    .mobile-menu-toggle{display:flex;}
    .nav-menu{position:absolute; top:100%; left:0; right:0; background:#fff; flex-direction:column; gap:0; max-height:0; overflow:hidden; transition:max-height 0.3s ease; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
    .nav-menu.active{max-height:600px;}
    .nav-menu li a{padding:1rem 2rem; border-bottom:1px solid #f0f0f0;}
    .nav-menu li.active a{border-bottom:1px solid #667eea; border-left:4px solid #667eea;}
    .main-nav{top:68px;}
}
@media (max-width:480px){
    .logo h1{font-size:1.3rem;}
    .content-wrapper{padding:0 1rem; margin:1rem auto;}
    .report-table th,.report-table td{padding:0.8rem; font-size:0.9rem;}
}

/* ========== ANIMATION ========== */
@keyframes fadeIn {from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);}}
</style>
</head>
<body>

<!-- HEADER -->
<header class="main-header">
<div class="header-container">
    <div class="logo"><a href="#"><h1>🏦 Smart Bank</h1></a></div>
    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="user-info">
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
    <?php endif; ?>
</div>
</header>

<!-- NAVIGATION -->
<nav class="main-nav">
<div class="nav-container">
    <ul class="nav-menu">
        <li class="<?php echo ($current_page=='dashboard.php')?'active':'';?>"><a href="dashboard.php"><span class="icon">🏠</span> Dashboard</a></li>
        <li class="<?php echo ($current_page=='accounts.php')?'active':'';?>"><a href="accounts.php"><span class="icon">💳</span> My Accounts</a></li>
        <li class="<?php echo ($current_page=='transfer.php')?'active':'';?>"><a href="transfer.php"><span class="icon">💸</span> Transfer</a></li>
        <li class="<?php echo ($current_page=='deposit.php')?'active':'';?>"><a href="deposit.php"><span class="icon">💰</span> Deposit</a></li>
        <li class="<?php echo ($current_page=='withdraw.php')?'active':'';?>"><a href="withdraw.php"><span class="icon">🏧</span> Withdraw</a></li>
        <li class="<?php echo ($current_page=='history.php')?'active':'';?>"><a href="history.php"><span class="icon">📊</span> History</a></li>
        <li class="<?php echo ($current_page=='beneficiaries.php')?'active':'';?>"><a href="beneficiaries.php"><span class="icon">👥</span> Beneficiaries</a></li>

    </ul>
    <div class="mobile-menu-toggle" onclick="toggleMobileMenu()"><span></span><span></span><span></span></div>
</div>
</nav>

<!-- CONTENT -->
<div class="content-wrapper">
    <!-- Welcome -->
    <div class="dashboard-header">
        <h2>Welcome Back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! 👋</h2>
        <p class="date-time"><?php echo date('l, F j, Y'); ?> | <span id="current-time"></span></p>
    </div>

    <!-- Total Balance -->
    <div class="balance-overview">
        <div class="balance-card">
            <div class="balance-icon">💼</div>
            <div class="balance-info">
                <h3>Total Balance</h3>
                <p class="balance-amount">$<?php echo number_format($total_balance,2);?></p>
                <span class="balance-trend positive">↑ 12.5% from last month</span>
            </div>
        </div>
    </div>

    <!-- REPORT TABLE -->
    <div class="report-section">
        <h3>Account & Transaction Report</h3>
        <div class="report-table-container">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account Type</th>
                        <th>Account Number</th>
                        <th>Balance</th>
                        <th>Recent Transactions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($accounts as $account): ?>
                    <tr>
                        <td><?php echo $account['type']; ?></td>
                        <td><?php echo $account['number']; ?></td>
                        <td>$<?php echo number_format($account['balance'],2);?></td>
                        <td>
                            <ul class="transaction-list">
                                <?php foreach($recent_transactions as $t): ?>
                                <li><?php echo date('M d',strtotime($t['date'])); ?> - <?php echo htmlspecialchars($t['description']); ?>: 
                                <span class="<?php echo $t['type']; ?>">
                                    <?php echo ($t['amount']>0?'+':'').'$'.number_format(abs($t['amount']),2);?>
                                </span></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> Smart Bank. All Rights Reserved.</p>
            <p><a href="about.php">About Us</a> | <a href="contact.php">Contact</a> | <a href="terms.php">Terms</a> | <a href="privacy.php">Privacy</a></p>
            <p>Need help? <a href="support.php">Contact Support</a></p>
        </div>
    </div>
</footer>

<script>
function toggleMobileMenu() {document.querySelector('.nav-menu').classList.toggle('active');}

// Current time
function updateTime(){
    const now=new Date();
    const timeString=now.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
    document.getElementById('current-time').textContent=timeString;
}
updateTime(); setInterval(updateTime,1000);
</script>
</body>
</html>
