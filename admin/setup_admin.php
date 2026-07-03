<?php
// Admin setup script - run this to create/reset admin user
require_once __DIR__ . '/../includes/db.php';
$db = getDB();

try {
    // Drop existing admin_users table if it exists
    $db->exec("DROP TABLE IF EXISTS admin_users");
    
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
    
    // Create admin user with PIN: 1234
    $hashedPin = password_hash('1234', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO admin_users (username, admin_pin) VALUES (?, ?)");
    $stmt->execute(['admin', $hashedPin]);
    
    echo "<h3>Admin Setup Complete!</h3>";
    echo "<p>Admin user created successfully.</p>";
    echo "<p><strong>Username:</strong> admin</p>";
    echo "<p><strong>PIN:</strong> 1234</p>";
    echo "<p><a href='login.php'>Go to Admin Login</a></p>";
    
} catch (PDOException $e) {
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        h3 { color: #ffb400; }
        p { margin: 10px 0; }
        a { color: #ffb400; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
</body>
</html>
