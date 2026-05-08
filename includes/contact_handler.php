<?php
// ============================================================
// CONTACT FORM HANDLER — includes/contact_handler.php
// ============================================================
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false,'message'=>'Invalid request.']);
    exit;
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$phone   = trim($_POST['phone']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate
$errors = [];
if (empty($name) || strlen($name) < 2) $errors[] = 'Please enter your full name.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))  $errors[] = 'Please enter a valid email address.';
if (empty($message) || strlen($message) < 10) $errors[] = 'Message must be at least 10 characters.';
if (strlen($name) > 150 || strlen($email) > 200 || strlen($message) > 5000) $errors[] = 'Input exceeds allowed length.';

if ($errors) {
    echo json_encode(['success'=>false,'message'=>implode(' ', $errors)]);
    exit;
}

try {
    $db   = getDB();
    $stmt = $db->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        htmlspecialchars($name),
        htmlspecialchars($email),
        htmlspecialchars($phone),
        htmlspecialchars($subject),
        htmlspecialchars($message),
    ]);
    echo json_encode(['success'=>true,'message'=>'Thank you! Your message has been received. I\'ll get back to you within 24 hours.']);
} catch (PDOException $e) {
    echo json_encode(['success'=>false,'message'=>'A server error occurred. Please try again later.']);
}
