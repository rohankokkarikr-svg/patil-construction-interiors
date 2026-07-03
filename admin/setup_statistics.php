<?php
// Statistics setup script - run this to create statistics table
require_once __DIR__ . '/../includes/db.php';
$db = getDB();

try {
    // Drop existing statistics table if it exists
    $db->exec("DROP TABLE IF EXISTS site_statistics");
    
    // Create site_statistics table
    $createTable = "CREATE TABLE site_statistics (
        id INT AUTO_INCREMENT PRIMARY KEY,
        stat_key VARCHAR(50) NOT NULL UNIQUE,
        stat_label VARCHAR(100) NOT NULL,
        stat_count INT NOT NULL DEFAULT 0,
        stat_suffix VARCHAR(10) DEFAULT '',
        stat_icon VARCHAR(50) NOT NULL,
        stat_order INT NOT NULL DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $db->exec($createTable);
    
    // Insert default statistics
    $defaultStats = [
        ['buildings_completed', 'Buildings Completed', 22, '+', 'fas fa-building', 1],
        ['years_experience', 'Years Experience', 4, '+', 'fas fa-calendar-alt', 2],
        ['medical_shop_estimates', 'Medical Shop Estimates', 12, '+', 'fas fa-file-medical', 3],
        ['road_projects', 'Road Projects', 3, '', 'fas fa-road', 4],
    ];
    
    $stmt = $db->prepare("INSERT INTO site_statistics (stat_key, stat_label, stat_count, stat_suffix, stat_icon, stat_order) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($defaultStats as $stat) {
        $stmt->execute($stat);
    }
    
    echo "<h3>Statistics Setup Complete!</h3>";
    echo "<p>Statistics table created with default values.</p>";
    echo "<p><strong>Default Statistics:</strong></p>";
    echo "<ul>";
    echo "<li>Buildings Completed: 22+</li>";
    echo "<li>Years Experience: 4+</li>";
    echo "<li>Medical Shop Estimates: 12+</li>";
    echo "<li>Road Projects: 3</li>";
    echo "</ul>";
    echo "<p><a href='statistics.php'>Manage Statistics</a> | <a href='index.php'>Admin Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Statistics Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #0a0e14; color: #fff; }
        h3 { color: #ffb400; }
        p { margin: 10px 0; }
        ul { margin: 15px 0; padding-left: 20px; }
        li { margin: 5px 0; }
        a { color: #ffb400; text-decoration: none; margin-right: 15px; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
</body>
</html>
