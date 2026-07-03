<?php
// ============================================================
// ADMIN APPOINTMENTS — admin/appointments.php
// ============================================================
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$db = getDB();
if (isset($_GET['status']) && isset($_GET['id'])) {
    $s = in_array($_GET['status'], ['confirmed','cancelled','pending']) ? $_GET['status'] : 'pending';
    $db->prepare("UPDATE appointments SET status=? WHERE id=?")->execute([$s, (int)$_GET['id']]);
}
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM appointments WHERE id=?")->execute([(int)$_GET['delete']]);
}
$appts = $db->query("SELECT * FROM appointments ORDER BY preferred_date ASC, preferred_time ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/png" href="/assets/images/hero/logo.png">
  <meta charset="UTF-8"><title>Appointments | Admin</title><meta name="robots" content="noindex,nofollow">
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/dark-theme.css">
  <style>body{background:var(--clr-bg);}.admin-topbar{height:64px;background:var(--clr-bg2);border-bottom:1px solid var(--clr-border);display:flex;align-items:center;padding:0 2rem;position:fixed;top:0;left:260px;right:0;z-index:50;}.content-area{margin-left:260px;padding:5rem 2rem 2rem;}</style>
</head>
<body>
<aside class="admin-sidebar">
  <div class="px-4 mb-4"><div class="d-flex align-items-center gap-2"><img src="/assets/images/hero/logo.png" alt="Logo" style="height: 30px; width: auto; object-fit: contain; margin-right: 8px;"><span style="font-family:var(--font-heading);font-size:1rem;font-weight:700;">Admin Panel</span></div></div>
  <nav>
    <a href="/admin/index.php" class="admin-nav-link"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="/admin/projects.php" class="admin-nav-link"><i class="fas fa-building"></i>Projects</a>
    <a href="/admin/certifications.php" class="admin-nav-link"><i class="fas fa-certificate"></i>Certifications</a>
    <a href="/admin/contacts.php" class="admin-nav-link"><i class="fas fa-envelope"></i>Messages</a>
    <a href="/admin/appointments.php" class="admin-nav-link active"><i class="fas fa-calendar-alt"></i>Appointments</a>
    <hr style="border-color:var(--clr-border);margin:1rem 1.5rem;">
    <a href="/index.php" class="admin-nav-link"><i class="fas fa-external-link-alt"></i>View Site</a>
    <a href="/admin/logout.php" class="admin-nav-link" style="color:var(--clr-error)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a>
  </nav>
</aside>
<div class="admin-topbar"><h2 style="font-family:var(--font-heading);font-size:1.2rem;font-weight:700;margin:0;">Appointment Requests</h2></div>
<div class="content-area">
  <div class="card-custom">
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Project Type</th><th>Date & Time</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <?php foreach ($appts as $a):
            $sColor = $a['status']==='confirmed' ? 'var(--clr-success)' : ($a['status']==='cancelled' ? 'var(--clr-error)' : 'var(--clr-warning)');
          ?>
            <tr>
              <td><?= $a['id'] ?></td>
              <td style="color:var(--clr-text);font-weight:600;"><?= htmlspecialchars($a['name']) ?></td>
              <td><?= htmlspecialchars($a['email']) ?></td>
              <td><?= htmlspecialchars($a['project_type'] ?: '—') ?></td>
              <td><?= date('d M Y', strtotime($a['preferred_date'])) ?> @ <?= date('h:i A', strtotime($a['preferred_time'])) ?></td>
              <td><span style="font-size:0.78rem;font-weight:700;color:<?= $sColor ?>;text-transform:uppercase;"><?= $a['status'] ?></span></td>
              <td>
                <?php if ($a['status'] !== 'confirmed'): ?><a href="?status=confirmed&id=<?= $a['id'] ?>" class="btn-outline-accent" style="padding:0.25rem 0.6rem;font-size:0.72rem;margin-right:0.2rem;" title="Confirm"><i class="fas fa-check"></i></a><?php endif; ?>
                <?php if ($a['status'] !== 'cancelled'): ?><a href="?status=cancelled&id=<?= $a['id'] ?>" class="btn-ghost" style="padding:0.25rem 0.6rem;font-size:0.72rem;margin-right:0.2rem;color:var(--clr-error);border-color:var(--clr-error);" title="Cancel"><i class="fas fa-times"></i></a><?php endif; ?>
                <a href="?delete=<?= $a['id'] ?>" class="btn-ghost" style="padding:0.25rem 0.6rem;font-size:0.72rem;color:var(--clr-error);border-color:var(--clr-error);" onclick="return confirm('Delete?')" title="Delete"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$appts): ?><tr><td colspan="7" class="text-center" style="color:var(--clr-text-muted);padding:2rem;">No appointments yet.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/admin.js"></script>
</body>
</html>
