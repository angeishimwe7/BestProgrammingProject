<?php
include 'connection.php';

// Create Users Table
$users_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    date_of_birth DATE,
    id_number VARCHAR(50),
    profile_image VARCHAR(255),
    status VARCHAR(20) DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($users_sql) === TRUE) {
    echo "Table 'users' created successfully<br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "<br>";
}

// Create Accounts Table
$accounts_sql = "CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    account_number VARCHAR(20) NOT NULL UNIQUE,
    account_type VARCHAR(50) NOT NULL,
    balance DECIMAL(15, 2) DEFAULT 0.00,
    currency VARCHAR(10) DEFAULT 'USD',
    status VARCHAR(20) DEFAULT 'active',
    opening_date DATE,
    interest_rate DECIMAL(5, 2) DEFAULT 0.00,
    minimum_balance DECIMAL(15, 2) DEFAULT 0.00,
    full_name VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($accounts_sql) === TRUE) {
    echo "Table 'accounts' created successfully<br>";
} else {
    echo "Error creating table 'accounts': " . $conn->error . "<br>";
}

// Create Transactions Table
$transactions_sql = "CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_number VARCHAR(50) NOT NULL UNIQUE,
    from_account_id INT,
    to_account_id INT,
    transaction_type VARCHAR(20) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    fee DECIMAL(15, 2) DEFAULT 0.00,
    balance_before DECIMAL(15, 2),
    balance_after DECIMAL(15, 2),
    status VARCHAR(20) DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_account_id) REFERENCES accounts(id) ON DELETE SET NULL,
    FOREIGN KEY (to_account_id) REFERENCES accounts(id) ON DELETE SET NULL
)";

if ($conn->query($transactions_sql) === TRUE) {
    echo "Table 'transactions' created successfully<br>";
} else {
    echo "Error creating table 'transactions': " . $conn->error . "<br>";
}

// Create Beneficiaries Table
$beneficiaries_sql = "CREATE TABLE IF NOT EXISTS beneficiaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    beneficiary_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(20) NOT NULL,
    bank_name VARCHAR(100) NOT NULL,
    relationship VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($beneficiaries_sql) === TRUE) {
    echo "Table 'beneficiaries' created successfully<br>";
} else {
    echo "Error creating table 'beneficiaries': " . $conn->error . "<br>";
}

$conn->close();
?>