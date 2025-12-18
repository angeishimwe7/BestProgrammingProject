<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user accounts
$sql = "SELECT id, account_number, account_type, balance, status 
        FROM accounts 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$accounts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Bank - My Accounts</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Accounts page specific styles */
        .content-wrapper {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .dashboard-header h2 {
            margin-bottom: 0.2rem;
        }
        .dashboard-header p {
            margin-top: 0;
            color: #666;
        }
        .accounts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .account-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .account-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .account-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .account-icon {
            font-size: 2rem;
        }
        .account-number {
            font-size: 0.9rem;
            color: #666;
        }
        .account-card h4 {
            margin: 0 0 0.5rem 0;
        }
        .account-balance {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }
        .view-details {
            display: inline-block;
            margin-top: 0.8rem;
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
        }
        .view-details:hover {
            text-decoration: underline;
        }
        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Include standard header/navigation -->
<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <a href="../index.php">
                <h1>🏦 Smart Bank</h1>
            </a>
        </div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
</header>

<nav class="main-nav">
    <div class="nav-container">
        <ul class="nav-menu">
            <li class="<?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <a href="dashboard.php"><span class="icon">🏠</span> <span class="text">Dashboard</span></a>
            </li>
            <li class="<?= ($current_page == 'accounts.php') ? 'active' : ''; ?>">
                <a href="accounts.php"><span class="icon">💳</span> <span class="text">My Accounts</span></a>
            </li>
            <li class="<?= ($current_page == 'transfer.php') ? 'active' : ''; ?>">
                <a href="transfer.php"><span class="icon">💸</span> <span class="text">Transfer</span></a>
            </li>
            <li class="<?= ($current_page == 'deposit.php') ? 'active' : ''; ?>">
                <a href="deposit.php"><span class="icon">💰</span> <span class="text">Deposit</span></a>
            </li>
            <li class="<?= ($current_page == 'withdraw.php') ? 'active' : ''; ?>">
                <a href="withdraw.php"><span class="icon">🏧</span> <span class="text">Withdraw</span></a>
            </li>
            <li class="<?= ($current_page == 'history.php') ? 'active' : ''; ?>">
                <a href="history.php"><span class="icon">📊</span> <span class="text">History</span></a>
            </li>
            <li class="<?= ($current_page == 'beneficiaries.php') ? 'active' : ''; ?>">
                <a href="beneficiaries.php"><span class="icon">👥</span> <span class="text">Beneficiaries</span></a>
            </li>
            <li class="<?= ($current_page == 'profile.php') ? 'active' : ''; ?>">
                <a href="profile.php"><span class="icon">👤</span> <span class="text">Profile</span></a>
            </li>
            <li class="<?= ($current_page == 'statement.php') ? 'active' : ''; ?>">
                <a href="statement.php"><span class="icon">📄</span> <span class="text">Statement</span></a>
            </li>
        </ul>
        <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <span></span><span></span><span></span>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    document.querySelector('.nav-menu').classList.toggle('active');
}
</script>

<div class="content-wrapper">
    <div class="dashboard-header">
        <h2>My Accounts 💳</h2>
        <p>Manage and view your bank accounts</p>
    </div>

    <?php if (empty($accounts)): ?>
        <div class="card">
            <p>No accounts found.</p>
        </div>
    <?php else: ?>
<div class="accounts-grid">
    <?php foreach ($accounts as $account): ?>
        <div class="account-card">
    <div class="account-header">
      <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
        <span class="account-icon">
            <?php echo ($account['account_type'] == 'Savings') ? '💰' : '💳'; ?>
        </span>
        <span class="account-number">
            ****<?php echo substr($account['account_number'], -4); ?>
        </span>
    </div>

    <h4><?php echo htmlspecialchars($account['account_type']); ?> Account</h4>

    <p class="account-balance">
        $<?php echo number_format($account['balance'], 2); ?>
    </p>

    <p>
        Status: 
        <strong style="color: <?= $account['status'] == 'active' ? 'green' : 'red'; ?>">
            <?= ucfirst(htmlspecialchars($account['status'])); ?>
        </strong>
    </p>

    <a href="create_account.php" class="view-details">➕ Create New Account</a>
</div>

    <?php endforeach; ?>
</div>

    <?php endif; ?>
</div>

<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-content">
            <p>&copy; <?= date('Y'); ?> Smart Bank. All Rights Reserved.</p>
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

<?php $conn->close(); ?>
</body>
</html>
