<?php
// Retrieve requested URL path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// Base directory (root directory of the project)
$base_dir = dirname(__DIR__);

// Helper to check and require file
function routeTo($file) {
    if (file_exists($file)) {
        require $file;
        exit;
    }
}

// 1. Serve root / index
if ($uri === '' || $uri === 'index.php' || $uri === 'index') {
    routeTo($base_dir . '/index.php');
}

// 2. Normalize and check root-level PHP files
$clean_uri = preg_replace('/\.php$/', '', $uri);
if (in_array($clean_uri, ['about', 'contact', 'certifications', 'projects', 'skills'])) {
    routeTo($base_dir . '/' . $clean_uri . '.php');
}

// 3. Handle subdirectories: admin, tools, includes, resume
if (preg_match('/^admin(?:\/(.*))?$/', $uri, $matches)) {
    $subpath = isset($matches[1]) ? $matches[1] : '';
    $subpath = preg_replace('/\.php$/', '', $subpath);
    if ($subpath === '' || $subpath === 'index') {
        routeTo($base_dir . '/admin/index.php');
    } else {
        routeTo($base_dir . '/admin/' . $subpath . '.php');
    }
}

if (preg_match('/^tools\/(.*)$/', $uri, $matches)) {
    $subpath = preg_replace('/\.php$/', '', $matches[1]);
    routeTo($base_dir . '/tools/' . $subpath . '.php');
}

if (preg_match('/^includes\/(.*)$/', $uri, $matches)) {
    $subpath = preg_replace('/\.php$/', '', $matches[1]);
    routeTo($base_dir . '/includes/' . $subpath . '.php');
}

if (preg_match('/^resume\/(.*)$/', $uri, $matches)) {
    $subpath = preg_replace('/\.php$/', '', $matches[1]);
    routeTo($base_dir . '/resume/' . $subpath . '.php');
}

// 4. General fallback: check if file exists directly under base_dir
$direct_file = $base_dir . '/' . $uri;
if (file_exists($direct_file) && !is_dir($direct_file)) {
    routeTo($direct_file);
}

// Check with .php appended if not present
if (substr($uri, -4) !== '.php') {
    routeTo($base_dir . '/' . $uri . '.php');
}

// 5. 404 page fallback
http_response_code(404);
echo "<h1>404 Not Found</h1>";
echo "<p>The requested page /" . htmlspecialchars($uri) . " was not found on this server.</p>";
