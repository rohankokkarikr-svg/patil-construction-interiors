<?php
// Check current admin PIN
require_once __DIR__ . '/../includes/db.php';
$db = getDB();

try {
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = 'admin' LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<h3>Current Admin PIN Status</h3>";
        echo "<p><strong>Username:</strong> " . htmlspecialchars($admin['username']) . "</p>";
        echo "<p><strong>Login Attempts:</strong> " . $admin['login_attempts'] . "</p>";
        echo "<p><strong>Last Attempt:</strong> " . ($admin['last_attempt'] ?? 'Never') . "</p>";
        echo "<p><strong>PIN Hash:</strong> " . substr($admin['admin_pin'], 0, 20) . "...</p>";
        
        // Test if 7676 works
        if (password_verify('7676', $admin['admin_pin'])) {
            echo "<p style='color: #27ae60; font-weight: bold;'>✅ PIN 7676 is VERIFIED and working!</p>";
        } else {
            echo "<p style='color: #e74c3c; font-weight: bold;'>❌ PIN 7676 is NOT working</p>";
            echo "<p style='color: #ffb400;'>Current PIN might still be 1234 or something else</p>";
        }
        
        // Test if 1234 works (old PIN)
        if (password_verify('1234', $admin['admin_pin'])) {
            echo "<p style='color: #ffb400;'>⚠️ Old PIN 1234 is still working</p>";
        }
        
        echo "<hr>";
        echo "<p><a href='update_admin_pin.php'>Update PIN to 7676</a></p>";
        echo "<p><a href='login.php'>Test Login</a></p>";
        
    } else {
        echo "<h3>No Admin User Found</h3>";
        echo "<p><a href='setup_admin.php'>Create Admin User</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<h3>Database Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check PIN Status</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #0a0e14; color: #fff; }
        h3 { color: #ffb400; }
        p { margin: 10px 0; }
        hr { border-color: #ffb400; margin: 20px 0; }
        a { color: #ffb400; text-decoration: none; margin-right: 15px; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
</body>
</html>
