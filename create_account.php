<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

/* ======================
   AUTH CHECK
====================== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$message_type = "";

/* ======================
   HANDLE FORM SUBMIT
====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Safe POST values
    $full_name       = trim($_POST['full_name'] ?? '');
    $account_type    = $_POST['account_type'] ?? 'savings';
    $currency        = $_POST['currency'] ?? 'USD';
    $initial_deposit = floatval($_POST['initial_deposit'] ?? 0);

    // Simple validation
    if ($full_name === '' || $initial_deposit < 0) {
        $message = "❌ Please fill all required fields correctly.";
        $message_type = "error";
    } else {

        // Generate random account number
        $account_number = 'SB' . rand(10000000, 99999999);

        // Interest & minimum balance (simple rules)
        $interest_rate = ($account_type === 'savings') ? 2.50 : 0.00;
        $minimum_balance = 0.00;

        // Insert query
        $sql = "INSERT INTO accounts 
                (user_id, account_number, account_type, balance, currency, status,
                 opening_date, interest_rate, minimum_balance, full_name)
                VALUES (?, ?, ?, ?, ?, 'active', CURDATE(), ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param(
            "issdsdds",
            $user_id,
            $account_number,
            $account_type,
            $initial_deposit,
            $currency,
            $interest_rate,
            $minimum_balance,
            $full_name
        );

        if ($stmt->execute()) {
            $message = "✅ Account created successfully for <strong>$full_name</strong>";
            $message_type = "success";
        } else {
            $message = "❌ Failed to create account.";
            $message_type = "error";
        }

        $stmt->close();
    }
}

// Page name for navigation
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Account</title>
<link rel="stylesheet" href="style.css">

<style>
.content-wrapper {
    max-width: 600px;
    margin: 2rem auto;
}

.card {
    background: white;
    padding: 1.8rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.form-group {
    margin-bottom: 1rem;
}

label {
    font-weight: 600;
    display: block;
    margin-bottom: 0.3rem;
}

input, select {
    width: 100%;
    padding: 0.7rem;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.btn {
    width: 100%;
    padding: 0.8rem;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.message.success {
    background: #27ae60;
    color: white;
    padding: 0.8rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.message.error {
    background: #e74c3c;
    color: white;
    padding: 0.8rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}
</style>
</head>

<body>

<!-- HEADER -->
<header class="main-header">
    <div class="header-container">
        <h1>🏦 Smart Bank</h1>
        <div>
            Welcome, <?= htmlspecialchars($_SESSION['full_name']); ?>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>

<!-- NAVIGATION -->
<nav class="main-nav">
    <ul class="nav-menu">
        <li><a href="dashboard.php">🏠 Dashboard</a></li>
        <li class="active"><a href="accounts.php">💳 Accounts</a></li>
        <li><a href="transfer.php">💸 Transfer</a></li>
        <li><a href="history.php">📊 History</a></li>
    </ul>
</nav>

<div class="content-wrapper">

    <div class="card">
        <h2>➕ Create New Account</h2>

        <?php if ($message): ?>
            <div class="message <?= $message_type; ?>">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <!-- FULL NAME (VISIBLE) -->
            <div class="form-group">
                <label>Account Holder Full Name</label>
                <input type="text" name="full_name" required placeholder="e.g. John Doe">
            </div>

            <!-- ACCOUNT TYPE -->
            <div class="form-group">
                <label>Account Type</label>
                <select name="account_type">
                    <option value="savings">Savings</option>
                    <option value="checking">Checking</option>
                    <option value="business">Business</option>
                </select>
            </div>

            <!-- INITIAL DEPOSIT -->
            <div class="form-group">
                <label>Initial Deposit</label>
                <input type="number" step="0.01" name="initial_deposit" required>
            </div>

            <!-- CURRENCY -->
            <div class="form-group">
                <label>Currency</label>
                <select name="currency">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="RWF">RWF</option>
                </select>
            </div>

            <button class="btn">Create Account</button>

        </form>
    </div>

</div>

</body>
</html>
