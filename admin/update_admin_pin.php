<?php
// Update admin PIN script
require_once '../includes/db.php';
$db = getDB();

try {
    // Update admin PIN to 7676
    $hashedPin = password_hash('7676', PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE admin_users SET admin_pin = ?, login_attempts = 0, last_attempt = NULL WHERE username = 'admin'");
    $stmt->execute([$hashedPin]);
    
    echo "<h3>Admin PIN Updated!</h3>";
    echo "<p>Admin PIN has been successfully updated to: <strong>7676</strong></p>";
    echo "<p><a href='login.php'>Go to Admin Login</a></p>";
    
} catch (PDOException $e) {
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Admin PIN</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #0a0e14; color: #fff; }
        h3 { color: #ffb400; }
        p { margin: 10px 0; }
        a { color: #ffb400; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
</body>
</html>
