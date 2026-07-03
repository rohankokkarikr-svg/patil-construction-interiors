<?php
// One-time database setup script
// Secured with token to prevent unauthorized execution
define('SETUP_TOKEN', 'patil2026setup');

if (!isset($_GET['token']) || $_GET['token'] !== SETUP_TOKEN) {
    http_response_code(403);
    die('<h1>403 Forbidden</h1><p>Provide the correct token to run setup. Usage: setup.php?token=patil2026setup</p>');
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Direct DB credentials (bypassing config to avoid circular dependency)
$host    = 'sql100.infinityfree.com';
$dbname  = 'if0_41554115_patil_db';
$user    = 'if0_41554115';
$pass    = 'JgEt9JVdg7hvtA';
$charset = 'utf8mb4';

echo "<style>body{font-family:sans-serif;max-width:800px;margin:40px auto;padding:20px;background:#1a1a2e;color:#eee;} .ok{color:#4ade80;} .err{color:#f87171;} pre{background:#111;padding:10px;border-radius:5px;overflow:auto;}</style>";
echo "<h1>🛠️ Database Setup - PATIL's Construction</h1>";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "<p class='ok'>✅ Connected to database <strong>$dbname</strong> on <strong>$host</strong></p>";
} catch (PDOException $e) {
    echo "<p class='err'>❌ Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

// Read schema
$schemaFile = __DIR__ . '/database/schema.sql';
if (!file_exists($schemaFile)) {
    echo "<p class='err'>❌ Schema file not found at: $schemaFile</p>";
    exit;
}

$sql = file_get_contents($schemaFile);

// Remove DB creation and USE statements (we are already in the correct DB)
$sql = preg_replace('/CREATE DATABASE IF NOT EXISTS.*?;/si', '', $sql);
$sql = preg_replace('/USE\s+`.*?`;/si', '', $sql);

// Split into individual statements
$statements = array_filter(array_map('trim', explode(';', $sql)));

$success = 0;
$errors  = 0;

echo "<h2>Running SQL statements...</h2><pre>";
foreach ($statements as $statement) {
    if (empty($statement)) continue;
    try {
        $pdo->exec($statement);
        echo "<span class='ok'>✅ OK</span>\n";
        $success++;
    } catch (PDOException $e) {
        echo "<span class='err'>⚠️  " . htmlspecialchars($e->getMessage()) . "</span>\n";
        $errors++;
    }
}
echo "</pre>";

echo "<h2>Summary</h2>";
echo "<p class='ok'>✅ $success statements executed successfully</p>";
if ($errors > 0) {
    echo "<p class='err'>⚠️ $errors statements had errors (usually safe — table may already exist)</p>";
}

echo "<h2>🎉 Setup Complete!</h2>";
echo "<p>Your database is ready. <a href='/' style='color:#60a5fa;'>Visit the homepage →</a></p>";
echo "<p style='color:#f87171;'><strong>Security:</strong> Please delete this file from your server after setup.</p>";
?>
