<?php
// Check admin user setup
require_once __DIR__ . '/../includes/db.php';
$db = getDB();

try {
    // Check if admin_users table exists
    $stmt = $db->prepare("SHOW TABLES LIKE 'admin_users'");
    $stmt->execute();
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        echo "admin_users table does not exist. Creating table...\n";
        
        // Create admin_users table
        $createTable = "CREATE TABLE admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            admin_pin VARCHAR(255) NOT NULL,
            login_attempts INT DEFAULT 0,
            last_attempt DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $db->exec($createTable);
        echo "Table created successfully.\n";
    }
    
    // Check if admin user exists
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if (!$admin) {
        echo "Admin user does not exist. Creating admin user...\n";
        
        // Default PIN: 1234 (hashed)
        $hashedPin = password_hash('1234', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO admin_users (username, admin_pin) VALUES (?, ?)");
        $stmt->execute(['admin', $hashedPin]);
        
        echo "Admin user created with default PIN: 1234\n";
    } else {
        echo "Admin user exists.\n";
        echo "Username: " . $admin['username'] . "\n";
        echo "Login attempts: " . $admin['login_attempts'] . "\n";
        echo "Last attempt: " . ($admin['last_attempt'] ?? 'Never') . "\n";
        
        // Test password verification
        $testPin = '1234';
        if (password_verify($testPin, $admin['admin_pin'])) {
            echo "Default PIN 1234 works!\n";
        } else {
            echo "Default PIN 1234 does not work.\n";
            echo "You may need to reset the PIN.\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
