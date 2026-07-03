<?php
// Direct PIN reset - run this to set PIN to 7676
require_once __DIR__ . '/../includes/db.php';

try {
    $db = getDB();
    
    // First, clear any existing admin users
    $db->exec("DELETE FROM admin_users");
    
    // Create new admin user with PIN 7676
    $hashedPin = password_hash('7676', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO admin_users (username, admin_pin, login_attempts) VALUES (?, ?, 0)");
    $stmt->execute(['admin', $hashedPin]);
    
    echo "<h3 style='color: #ffb400;'>✅ PIN Reset Complete!</h3>";
    echo "<div style='background: #141b25; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<p><strong>Username:</strong> admin</p>";
    echo "<p><strong>New PIN:</strong> 7676</p>";
    echo "<p style='color: #27ae60;'>PIN has been successfully updated!</p>";
    echo "<hr style='border-color: #ffb400; margin: 20px 0;'>";
    echo "<p><a href='login.php' style='background: #ffb400; color: #000; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Go to Login</a></p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: #e74c3c;'>❌ Database Error</h3>";
    echo "<p style='color: #fff;'>" . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PIN Reset Complete</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #0a0e14; 
            color: #fff; 
            margin: 0; 
            padding: 20px;
        }
        h3 { 
            color: #ffb400; 
            margin-bottom: 20px;
        }
        a { 
            color: #ffb400; 
            text-decoration: none; 
        }
        a:hover { 
            text-decoration: underline; 
        }
    </style>
</head>
<body>
</body>
</html>
