<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';

try {
    $db = getDB();
    $sql = file_get_contents('database/schema.sql');
    
    // Remove database creation and selection statements since we are already connected to the proper database
    $sql = preg_replace('/CREATE DATABASE IF NOT EXISTS.*?;/i', '', $sql);
    $sql = preg_replace('/USE `.*?`;/i', '', $sql);
    
    // Execute SQL
    $db->exec($sql);
    echo "<h1>SUCCESS: Database schema imported successfully!</h1>";
    
    // Self-destruct for security
    unlink(__FILE__);
    echo "<p>Security cleanup: Migration script deleted from server.</p>";
} catch (Exception $e) {
    echo "<h1>ERROR: Database import failed</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
