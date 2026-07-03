<?php
// ============================================================
// Database Connection — PDO
// ============================================================
require_once __DIR__ . '/config.php';

if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'contraction_db');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') ?: '');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');
if (!defined('DB_PORT')) define('DB_PORT', getenv('DB_PORT') ?: '3306');

function getDB(): ?PDO {
    static $pdo = null;
    static $failed = false;
    if ($failed) return null;
    if ($pdo !== null) return $pdo;

    // Support DATABASE_URL format (e.g. PlanetScale / ClearDB on Heroku/Vercel)
    $databaseUrl = getenv('DATABASE_URL');
    if ($databaseUrl) {
        $parts = parse_url($databaseUrl);
        $host    = $parts['host'] ?? DB_HOST;
        $port    = $parts['port'] ?? DB_PORT;
        $dbName  = ltrim($parts['path'] ?? ('/' . DB_NAME), '/');
        $user    = $parts['user'] ?? DB_USER;
        $pass    = $parts['pass'] ?? DB_PASS;
        $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=" . DB_CHARSET;
    } else {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $user = DB_USER;
        $pass = DB_PASS;
    }

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5,
    ];
    try {
        $pdo = new PDO($dsn, $user ?? DB_USER, $pass ?? DB_PASS, $options);
    } catch (PDOException $e) {
        $failed = true;
        // In production/Vercel, silently return null so pages render without DB
        if (getenv('VERCEL') || (defined('ENVIRONMENT') && ENVIRONMENT === 'production')) {
            return null;
        }
        http_response_code(500);
        die('<h2>Database connection failed.</h2><p>Please check your database configuration.</p>');
    }
    return $pdo;
}
