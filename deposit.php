<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';


// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$success_msg = $error_msg = "";

// Fetch user's active accounts
$stmt = $conn->prepare("
    SELECT id, account_number, account_type, balance 
    FROM accounts 
    WHERE user_id = ? AND status = 'active'
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$accounts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_id = intval($_POST['account_id']);
    $amount = floatval($_POST['amount']);
    $deposit_method = trim($_POST['deposit_method']);
    $reference = trim($_POST['reference']);

    if ($amount <= 0) {
        $error_msg = "Invalid deposit amount.";
    } elseif ($amount > 1000000) {
        $error_msg = "Deposit amount cannot exceed $1,000,000.";
    } elseif (empty($deposit_method)) {
        $error_msg = "Please select a deposit method.";
    } else {
        // Verify account belongs to user
        $stmt = $conn->prepare("
            SELECT id, account_number, balance 
            FROM accounts 
            WHERE id = ? AND user_id = ? AND status = 'active'
        ");
        $stmt->bind_param("ii", $account_id, $user_id);
        $stmt->execute();
        $account = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$account) {
            $error_msg = "Invalid account selected.";
        } else {
            // BEGIN TRANSACTION
            $conn->begin_transaction();

            try {
                // Current and new balance
                $current_balance = $account['balance'];
                $new_balance = $current_balance + $amount;
                $fee = 0.00;

                // Generate unique transaction number
                $transaction_number = 'TXN' . time() . rand(1000, 9999);
                $transaction_type = 'credit'; // Deposit

                // Insert into transactions
                $stmt = $conn->prepare("
                    INSERT INTO transactions
                    (transaction_number, from_account_id, to_account_id, transaction_type, amount, fee, balance_before, balance_after)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param(
                    "siiisddd", 
                    $transaction_number, 
                    $account_id, // from_account_id can be same as deposit account
                    $account_id, // to_account_id
                    $transaction_type, 
                    $amount, 
                    $fee, 
                    $current_balance, 
                    $new_balance
                );
                $stmt->execute();
                $stmt->close();

                // Update account balance
                $stmt = $conn->prepare("UPDATE accounts SET balance = ? WHERE id = ?");
                $stmt->bind_param("di", $new_balance, $account_id);
                $stmt->execute();
                $stmt->close();

                // COMMIT
                $conn->commit();
                $success_msg = "Deposit of $" . number_format($amount, 2) . " completed successfully!";

                // Refresh accounts data
                $stmt = $conn->prepare("
                    SELECT id, account_number, account_type, balance 
                    FROM accounts 
                    WHERE user_id = ? AND status = 'active'
                ");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $accounts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();

            } catch (Exception $e) {
                $conn->rollback();
                $error_msg = "Deposit failed. Please try again. Error: " . $e->getMessage();
            }
        }
    }
}
?>

}

$conn->close();

// Page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Bank - Deposit Money</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-card {
            max-width: 600px;
            margin: auto;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-size: 14px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.9rem;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .deposit-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .method-option {
            position: relative;
        }
        .method-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        .method-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.2rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        .method-option input[type="radio"]:checked + .method-label {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        }
        .method-label:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }
        .method-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .method-name {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }
        .amount-input-wrapper {
            position: relative;
        }
        .currency-symbol {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 600;
            color: #666;
            font-size: 16px;
        }
        .amount-input-wrapper input {
            width: 300px;
            padding-left: 35px;
            font-size: 14px; /* Reduced size */
            font-weight: 600;
            height: 20px; /* Adjusted height */
        }
        .quick-amounts {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.8rem;
            margin-top: 0.8rem;
        }
        .quick-amount-btn {
            padding: 0.6rem;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            transition: all 0.2s;
        }
        .quick-amount-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            margin-top: 1.5rem;
            transition: transform 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        .success {
            background: #eafaf1;
            color: #27ae60;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 600;
            border-left: 4px solid #27ae60;
        }
        .error {
            background: #ffe5e5;
            color: #c0392b;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 600;
            border-left: 4px solid #c0392b;
        }
        .no-accounts {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        .no-accounts-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .account-info {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        .account-info h3 {
            margin: 0 0 0.5rem 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .account-info .balance {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
    </style>
</head>
<body>

    
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
                <a href="dashboard.php">🏠 Dashboard</a>
            </li>

            <li class="<?= ($current_page == 'accounts.php') ? 'active' : ''; ?>">
                <a href="accounts.php">💳 My Accounts</a>
            </li>

            <li class="<?= ($current_page == 'transfer.php') ? 'active' : ''; ?>">
                <a href="transfer.php">💸 Transfer</a>
            </li>

            <li class="<?= ($current_page == 'deposit.php') ? 'active' : ''; ?>">
                <a href="deposit.php">💰 Deposit</a>
            </li>

            <li class="<?= ($current_page == 'withdraw.php') ? 'active' : ''; ?>">
                <a href="withdraw.php">🏧 Withdraw</a>
            </li>

            <li class="<?= ($current_page == 'history.php') ? 'active' : ''; ?>">
                <a href="history.php">📊 History</a>
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
        <h2>Deposit Money 💵</h2>
        <p class="date-time">Add funds to your account securely</p>
    </div>

    <div class="card form-card">

        <?php if ($success_msg): ?>
            <div class="success">✅ <?php echo htmlspecialchars($success_msg); ?></div>
        <?php endif; ?>
        
        <?php if ($error_msg): ?>
            <div class="error">❌ <?php echo htmlspecialchars($error_msg); ?></div>
        <?php endif; ?>

        <?php if (empty($accounts)): ?>
            <div class="no-accounts">
                <div class="no-accounts-icon">🏦</div>
                <h3>No Active Accounts</h3>
                <p>You don't have any active accounts to deposit into.</p>
                <p>Please contact support to activate an account.</p>
            </div>
        <?php else: ?>
            <form method="POST" id="depositForm">
                <div class="form-group">
                    <label>Select Account</label>
                    <select name="account_id" id="accountSelect" required onchange="updateAccountInfo()">
                        <option value="">Choose an account</option>
                        <?php foreach ($accounts as $acc): ?>
                            <option value="<?php echo intval($acc['id']); ?>" 
                                    data-balance="<?php echo $acc['balance']; ?>"
                                    data-type="<?php echo htmlspecialchars($acc['account_type']); ?>"
                                    data-number="<?php echo htmlspecialchars($acc['account_number']); ?>">
                                <?php echo htmlspecialchars($acc['account_type']); ?> - 
                                ****<?php echo htmlspecialchars(substr($acc['account_number'], -4)); ?>
                                (Balance: $<?php echo number_format($acc['balance'], 2); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="accountInfo" class="account-info" style="display: none;">
                    <h3>Current Balance</h3>
                    <p class="balance">$<span id="currentBalance">0.00</span></p>
                </div>

                <div class="form-group">
                    <label>Deposit Method</label>
                    <div class="deposit-methods">
                        <div class="method-option">
                            <input type="radio" name="deposit_method" id="cash" value="Cash" required>
                            <label for="cash" class="method-label">
                                <span class="method-icon">💵</span>
                                <span class="method-name">Cash</span>
                            </label>
                        </div>
                        <div class="method-option">
                            <input type="radio" name="deposit_method" id="check" value="Check">
                            <label for="check" class="method-label">
                                <span class="method-icon">📝</span>
                                <span class="method-name">Check</span>
                            </label>
                        </div>
                        <div class="method-option">
                            <input type="radio" name="deposit_method" id="wire" value="Wire Transfer">
                            <label for="wire" class="method-label">
                                <span class="method-icon">🔄</span>
                                <span class="method-name">Wire Transfer</span>
                            </label>
                        </div>
                        <div class="method-option">
                            <input type="radio" name="deposit_method" id="mobile" value="Mobile Money">
                            <label for="mobile" class="method-label">
                                <span class="method-icon">📱</span>
                                <span class="method-name">Mobile Money</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Amount ($)</label>
                    <div class="amount-input-wrapper">
                        <span class="currency-symbol">$</span>
                        <input type="number" name="amount" id="amountInput" step="0.01" min="0.01" max="1000000" placeholder="0.00" required>
                    </div>
                    <div class="quick-amounts">
                        <button type="button" class="quick-amount-btn" onclick="setAmount(50)">$50</button>
                        <button type="button" class="quick-amount-btn" onclick="setAmount(100)">$100</button>
                        <button type="button" class="quick-amount-btn" onclick="setAmount(500)">$500</button>
                        <button type="button" class="quick-amount-btn" onclick="setAmount(1000)">$1000</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Reference Number (Optional)</label>
                    <input type="text" name="reference" placeholder="Enter reference or check number" maxlength="100">
                </div>

                <button type="submit" class="btn-submit">💰 Deposit Money</button>
            </form>
        <?php endif; ?>
    </div>

</div>

<script>
function setAmount(amount) {
    document.getElementById('amountInput').value = amount;
}

function updateAccountInfo() {
    const select = document.getElementById('accountSelect');
    const selectedOption = select.options[select.selectedIndex];
    const accountInfo = document.getElementById('accountInfo');
    const balanceSpan = document.getElementById('currentBalance');
    
    if (selectedOption.value) {
        const balance = parseFloat(selectedOption.dataset.balance);
        balanceSpan.textContent = balance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        accountInfo.style.display = 'block';
    } else {
        accountInfo.style.display = 'none';
    }
}
</script>

</body>
</html>
