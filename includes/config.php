<?php
// ============================================================
// CONFIGURATION FILE - includes/config.php
// ============================================================

// Load environment variables
function loadEnv($file = '.env') {
    // 1. First, load existing environment variables from the system
    $systemEnvVars = [
        'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET',
        'SITE_URL', 'SITE_NAME', 'SITE_EMAIL', 'SITE_PHONE',
        'ADMIN_PIN', 'ADMIN_EMAIL',
        'SMTP_HOST', 'SMTP_PORT', 'SMTP_USERNAME', 'SMTP_PASSWORD', 'SMTP_FROM_EMAIL', 'SMTP_FROM_NAME',
        'UPLOAD_MAX_SIZE', 'UPLOAD_ALLOWED_TYPES', 'UPLOAD_PATH',
        'SESSION_LIFETIME', 'CSRF_TOKEN_LENGTH', 'PASSWORD_MIN_LENGTH',
        'ENVIRONMENT', 'DEBUG', 'LOG_ERRORS',
        'RECAPTCHA_SITE_KEY', 'RECAPTCHA_SECRET_KEY', 'GOOGLE_MAPS_API_KEY',
        'CACHE_ENABLED', 'CACHE_LIFETIME',
        'BACKUP_ENABLED', 'BACKUP_PATH', 'BACKUP_FREQUENCY'
    ];
    
    foreach ($systemEnvVars as $var) {
        $val = getenv($var);
        if ($val !== false) {
            if (!defined($var)) {
                define($var, $val);
            }
        }
    }

    $envFile = __DIR__ . '/../' . $file;
    
    // In Vercel or other production servers, if .env doesn't exist, we don't want to fall back to .env.example
    // if system environment variables are already set.
    if (!file_exists($envFile)) {
        if (getenv('VERCEL') || (defined('ENVIRONMENT') && ENVIRONMENT === 'production') || getenv('ENVIRONMENT') === 'production') {
            return;
        }
        $envFile = __DIR__ . '/../.env.example';
    }
    
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            if (!defined($key)) {
                define($key, $value);
            }
        }
    }
}

// Load environment variables
loadEnv();

// Database Configuration (fallback to hardcoded values if not in .env)
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'contraction_db');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

// Site Configuration
if (!defined('SITE_URL')) define('SITE_URL', 'http://localhost/');
if (!defined('SITE_NAME')) define('SITE_NAME', "PATIL's Construction & Interior's");
if (!defined('SITE_EMAIL')) define('SITE_EMAIL', 'info@patilconstruction.com');
if (!defined('SITE_PHONE')) define('SITE_PHONE', '+91 XXXXX XXXXX');

// Admin Configuration
if (!defined('ADMIN_PIN')) define('ADMIN_PIN', '7676');
if (!defined('ADMIN_EMAIL')) define('ADMIN_EMAIL', 'admin@patilconstruction.com');

// Email Configuration
if (!defined('SMTP_HOST')) define('SMTP_HOST', 'smtp.gmail.com');
if (!defined('SMTP_PORT')) define('SMTP_PORT', '587');
if (!defined('SMTP_USERNAME')) define('SMTP_USERNAME', '');
if (!defined('SMTP_PASSWORD')) define('SMTP_PASSWORD', '');
if (!defined('SMTP_FROM_EMAIL')) define('SMTP_FROM_EMAIL', 'noreply@patilconstruction.com');
if (!defined('SMTP_FROM_NAME')) define('SMTP_FROM_NAME', "PATIL's Construction");

// File Upload Configuration
if (!defined('UPLOAD_MAX_SIZE')) define('UPLOAD_MAX_SIZE', 5242880); // 5MB
if (!defined('UPLOAD_ALLOWED_TYPES')) define('UPLOAD_ALLOWED_TYPES', 'jpg,jpeg,png,pdf,doc,docx');
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', 'uploads/');

// Security Configuration
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 3600); // 1 hour
if (!defined('CSRF_TOKEN_LENGTH')) define('CSRF_TOKEN_LENGTH', 32);
if (!defined('PASSWORD_MIN_LENGTH')) define('PASSWORD_MIN_LENGTH', 8);

// Development/Production
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'development');
if (!defined('DEBUG')) define('DEBUG', true);
if (!defined('LOG_ERRORS')) define('LOG_ERRORS', true);

// API Keys
if (!defined('RECAPTCHA_SITE_KEY')) define('RECAPTCHA_SITE_KEY', '');
if (!defined('RECAPTCHA_SECRET_KEY')) define('RECAPTCHA_SECRET_KEY', '');
if (!defined('GOOGLE_MAPS_API_KEY')) define('GOOGLE_MAPS_API_KEY', '');

// Cache Configuration
if (!defined('CACHE_ENABLED')) define('CACHE_ENABLED', false);
if (!defined('CACHE_LIFETIME')) define('CACHE_LIFETIME', 3600);

// Backup Configuration
if (!defined('BACKUP_ENABLED')) define('BACKUP_ENABLED', false);
if (!defined('BACKUP_PATH')) define('BACKUP_PATH', 'backup/');
if (!defined('BACKUP_FREQUENCY')) define('BACKUP_FREQUENCY', 'daily');

// ============================================================
// Helper Functions
// ============================================================

/**
 * Get environment variable with fallback
 */
function env($key, $default = null) {
    return defined($key) ? constant($key) : $default;
}

/**
 * Check if in development mode
 */
function isDevelopment() {
    return env('ENVIRONMENT') === 'development';
}

/**
 * Check if in production mode
 */
function isProduction() {
    return env('ENVIRONMENT') === 'production';
}

/**
 * Get site URL with proper protocol
 */
function getSiteUrl() {
    $url = env('SITE_URL', 'http://localhost/');
    return rtrim($url, '/');
}

/**
 * Get upload path
 */
function getUploadPath() {
    return env('UPLOAD_PATH', 'uploads/');
}

/**
 * Error logging function
 */
function logError($message, $file = 'error.log') {
    if (env('LOG_ERRORS', true)) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        file_put_contents(__DIR__ . '/../logs/' . $file, $logMessage, FILE_APPEND | LOCK_EX);
    }
}

/**
 * Debug output (only in development)
 */
function debug($var, $label = '') {
    if (isDevelopment()) {
        echo '<pre style="background: #f0f0f0; padding: 10px; margin: 10px 0; border-left: 4px solid #007bff;">';
        if ($label) echo "<strong>$label:</strong> ";
        echo print_r($var, true);
        echo '</pre>';
    }
}
?>
