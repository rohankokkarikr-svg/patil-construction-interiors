<?php
if (($_GET['pin'] ?? '') !== '7676') {
    die("Access denied. Please specify the correct ?pin=XXXX parameter.");
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$db = getDB();
if (!$db) {
    die("Database connection failed.\n");
}

function compressBase64($base64Data) {
    if (strpos($base64Data, 'data:') !== 0) {
        return $base64Data; // Not a base64 string
    }

    // Extract mime type and content
    preg_match('/^data:([^;]+);base64,(.+)$/', $base64Data, $matches);
    if (count($matches) < 3) {
        return $base64Data;
    }

    $mimeType = $matches[1];
    $data = base64_decode($matches[2]);
    
    // Create temporary file to use with GD
    $tmpFile = tempnam(sys_get_temp_dir(), 'img_');
    file_put_contents($tmpFile, $data);
    
    $compressed = compressImageToBase64($tmpFile, $mimeType);
    unlink($tmpFile);
    
    return $compressed;
}

// Compress projects
$projects = $db->query("SELECT id, image_path FROM projects")->fetchAll();
foreach ($projects as $p) {
    if (strpos($p['image_path'], 'data:') === 0) {
        $oldSize = strlen($p['image_path']);
        if ($oldSize > 150000) { // Only compress if larger than 150KB
            echo "Compressing project ID {$p['id']} image (original size: " . round($oldSize/1024) . " KB)...\n";
            $newPath = compressBase64($p['image_path']);
            $db->prepare("UPDATE projects SET image_path=? WHERE id=?")->execute([$newPath, $p['id']]);
            echo "  ✓ Done. New size: " . round(strlen($newPath)/1024) . " KB\n";
        }
    }
}

// Compress certifications
$certs = $db->query("SELECT id, image_path FROM certifications")->fetchAll();
foreach ($certs as $c) {
    if ($c['image_path'] && strpos($c['image_path'], 'data:') === 0) {
        $oldSize = strlen($c['image_path']);
        if ($oldSize > 150000) { // Only compress if larger than 150KB
            echo "Compressing cert ID {$c['id']} image (original size: " . round($oldSize/1024) . " KB)...\n";
            $newPath = compressBase64($c['image_path']);
            $db->prepare("UPDATE certifications SET image_path=? WHERE id=?")->execute([$newPath, $c['id']]);
            echo "  ✓ Done. New size: " . round(strlen($newPath)/1024) . " KB\n";
        }
    }
}

echo "Database compression check complete!\n";
