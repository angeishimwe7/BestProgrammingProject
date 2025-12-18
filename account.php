<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/db.php'; // your DB connection

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
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

// Page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Bank - My Accounts</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navigation.php'; ?>

<div class="content-wrapper">

    <div class="dashboard-header">
        <h2>My Accounts 💳</h2>
        <p class="date-time">
            Manage and view your bank accounts
        </p>
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
                        <span class="account-icon">
                            <?php
                                echo ($account['account_type'] == 'Savings') ? '💰' : '💳';
                            ?>
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
                        <strong style="color: <?php echo $account['status'] == 'active' ? 'green' : 'red'; ?>">
                            <?php echo ucfirst($account['status']); ?>
                        </strong>
                    </p>

                    <a href="account_details.php?id=<?php echo $account['id']; ?>" class="view-details">
                        View Transactions →
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
