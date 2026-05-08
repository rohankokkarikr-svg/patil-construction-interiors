<?php
// ============================================================
// APPOINTMENT HANDLER — includes/appointment_handler.php
// ============================================================
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false,'message'=>'Invalid request.']);
    exit;
}

$name         = trim($_POST['name']          ?? '');
$email        = trim($_POST['email']         ?? '');
$phone        = trim($_POST['phone']         ?? '');
$project_type = trim($_POST['project_type']  ?? '');
$pref_date    = trim($_POST['preferred_date']?? '');
$pref_time    = trim($_POST['preferred_time']?? '');
$notes        = trim($_POST['notes']         ?? '');

// Validate
$errors = [];
if (empty($name))  $errors[] = 'Name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
if (empty($pref_date)) $errors[] = 'Preferred date is required.';
if (empty($pref_time)) $errors[] = 'Preferred time is required.';

// Date must be future
if ($pref_date && $pref_date <= date('Y-m-d')) $errors[] = 'Please select a future date.';

if ($errors) {
    echo json_encode(['success'=>false,'message'=>implode(' ', $errors)]);
    exit;
}

try {
    $db   = getDB();
    $stmt = $db->prepare("INSERT INTO appointments (name, email, phone, project_type, preferred_date, preferred_time, notes) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([
        htmlspecialchars($name),
        htmlspecialchars($email),
        htmlspecialchars($phone),
        htmlspecialchars($project_type),
        $pref_date,
        $pref_time,
        htmlspecialchars($notes),
    ]);
    echo json_encode(['success'=>true,'message'=>'Appointment request received! I\'ll confirm your slot within 24 hours via email.']);
} catch (PDOException $e) {
    echo json_encode(['success'=>false,'message'=>'Server error. Please try again.']);
}
