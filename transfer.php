<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';

// Auth check - UNCOMMENTED AND FIXED
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Fetch user accounts (sender) - MOVED AFTER AUTH CHECK
$stmt = $conn->prepare("
    SELECT id, account_number, balance 
    FROM accounts 
    WHERE user_id = ? AND status = 'active'
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$accounts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle transfer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_account = intval($_POST['from_account']);
    $to_account_number = trim($_POST['to_account']);
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        $error = "Invalid transfer amount.";
    } else {

        // Get sender account
        $stmt = $conn->prepare("
            SELECT id, balance, account_number
            FROM accounts 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $from_account, $user_id);
        $stmt->execute();
        $sender = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$sender) {
            $error = "Invalid sender account.";
        } elseif ($sender['account_number'] === $to_account_number) {
            $error = "Cannot transfer to the same account.";
        } elseif ($sender['balance'] < $amount) {
            $error = "Insufficient balance.";
        } else {

            // Get receiver account
            $stmt = $conn->prepare("
                SELECT id, user_id
                FROM accounts 
                WHERE account_number = ? AND status = 'active'
            ");
            $stmt->bind_param("s", $to_account_number);
            $stmt->execute();
            $receiver = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$receiver) {
                $error = "Recipient account not found.";
            } else {

                // BEGIN TRANSACTION
                $conn->begin_transaction();

                try {
                    // Generate unique transaction number
                    $transaction_number = 'TXN' . date('YmdHis') . rand(1000, 9999);
                    
                    // Get sender balance before
                    $balance_before_sender = $sender['balance'];
                    $balance_after_sender = $balance_before_sender - $amount;
                    
                    // Deduct sender
                    $stmt = $conn->prepare("
                        UPDATE accounts 
                        SET balance = balance - ? 
                        WHERE id = ?
                    ");
                    $stmt->bind_param("di", $amount, $sender['id']);
                    $stmt->execute();
                    $stmt->close();

                    // Credit receiver - first get receiver's current balance
                    $stmt = $conn->prepare("SELECT balance FROM accounts WHERE id = ?");
                    $stmt->bind_param("i", $receiver['id']);
                    $stmt->execute();
                    $receiver_data = $stmt->get_result()->fetch_assoc();
                    $balance_before_receiver = $receiver_data['balance'];
                    $balance_after_receiver = $balance_before_receiver + $amount;
                    $stmt->close();
                    
                    // Update receiver balance
                    $stmt = $conn->prepare("
                        UPDATE accounts 
                        SET balance = balance + ? 
                        WHERE id = ?
                    ");
                    $stmt->bind_param("di", $amount, $receiver['id']);
                    $stmt->execute();
                    $stmt->close();

                    // Log sender transaction (debit)
                    $fee = 0.00; // You can add fee calculation here if needed
                    $stmt = $conn->prepare("
                        INSERT INTO transactions (transaction_number, from_account_id, to_account_id, transaction_type, amount, fee, balance_before, balance_after, status, created_at)
                        VALUES (?, ?, ?, 'transfer', ?, ?, ?, ?, 'completed', NOW())
                    ");
                    $stmt->bind_param("siidddd", $transaction_number, $sender['id'], $receiver['id'], $amount, $fee, $balance_before_sender, $balance_after_sender);
                    $stmt->execute();
                    $stmt->close();

                    // Log receiver transaction (credit) - same transaction number
                    $stmt = $conn->prepare("
                        INSERT INTO transactions (transaction_number, from_account_id, to_account_id, transaction_type, amount, fee, balance_before, balance_after, status, created_at)
                        VALUES (?, ?, ?, 'transfer', ?, ?, ?, ?, 'completed', NOW())
                    ");
                    $stmt->bind_param("siidddd", $transaction_number, $sender['id'], $receiver['id'], $amount, $fee, $balance_before_receiver, $balance_after_receiver);
                    $stmt->execute();
                    $stmt->close();

                    // COMMIT
                    $conn->commit();
                    $message = "Transfer of $" . number_format($amount, 2) . " completed successfully!";
                    
                    // Refresh accounts data after successful transfer
                    $stmt = $conn->prepare("
                        SELECT id, account_number, balance 
                        FROM accounts 
                        WHERE user_id = ? AND status = 'active'
                    ");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $accounts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();

                } catch (Exception $e) {
                    $conn->rollback();
                    $error = "Transfer failed. Please try again. Error: " . $e->getMessage();
                }
            }
        }
    }
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
    <title>Smart Bank - Transfer</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-card {
            max-width: 500px;
            margin: auto;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.4rem;
            color: #333;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            margin-top: 1rem;
            transition: transform 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .success {
            background: #eafaf1;
            color: #27ae60;
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 600;
        }
        .error {
            background: #ffe5e5;
            color: #c0392b;
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 600;
        }
        .no-accounts {
            text-align: center;
            padding: 2rem;
            color: #666;
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
        <h2>Transfer Money 💸</h2>
        <p class="date-time">Send money securely</p>
    </div>

    <div class="card form-card">

        <?php if ($message): ?>
            <div class="success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (empty($accounts)): ?>
            <div class="no-accounts">
                <p>You don't have any active accounts to transfer from.</p>
                <p>Please contact support to activate an account.</p>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>From Account</label>
                    <select name="from_account" required>
                        <option value="">Select an account</option>
                        <?php foreach ($accounts as $acc): ?>
                            <option value="<?php echo intval($acc['id']); ?>">
                                ****<?php echo htmlspecialchars(substr($acc['account_number'], -4)); ?>
                                (Balance: $<?php echo number_format($acc['balance'], 2); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>To Account Number</label>
                    <input type="text" name="to_account" required placeholder="Enter recipient account number" maxlength="50">
                </div>

                <div class="form-group">
                    <label>Amount ($)</label>
                    <input type="number" name="amount" step="0.01" min="0.01" required placeholder="0.00">
                </div>

                <button type="submit" class="btn-submit">Transfer Money</button>
            </form>
        <?php endif; ?>
    </div>

</div>


</body>
</html>