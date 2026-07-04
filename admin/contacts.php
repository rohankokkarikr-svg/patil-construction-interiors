<?php
// ============================================================
// ADMIN CONTACTS — admin/contacts.php
// ============================================================
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$db = getDB();
if (isset($_GET['mark_read'])) {
    $db->prepare("UPDATE contacts SET is_read=1 WHERE id=?")->execute([(int)$_GET['mark_read']]);
}
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM contacts WHERE id=?")->execute([(int)$_GET['delete']]);
}

$view = null;
if (isset($_GET['view'])) {
    $view = $db->prepare("SELECT * FROM contacts WHERE id=?");
    $view->execute([(int)$_GET['view']]);
    $view = $view->fetch();
    if ($view && !$view['is_read']) {
        $db->prepare("UPDATE contacts SET is_read=1 WHERE id=?")->execute([$view['id']]);
    }
}

$contacts = $db->query("SELECT * FROM contacts ORDER BY submitted_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/png" href="/assets/images/hero/logo.png">
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Messages | Admin</title><meta name="robots" content="noindex,nofollow">
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/style.min.css">
  <link rel="stylesheet" href="/assets/css/dark-theme.min.css">
  <link rel="stylesheet" href="/assets/css/responsive.min.css">
  <style>body{background:var(--clr-bg);}.admin-topbar{height:64px;background:var(--clr-bg2);border-bottom:1px solid var(--clr-border);display:flex;align-items:center;padding:0 2rem;position:fixed;top:0;left:260px;right:0;z-index:50;}.content-area{margin-left:260px;padding:5rem 2rem 2rem;}</style>
</head>
<body>
<aside class="admin-sidebar">
  <div class="px-4 mb-4"><div class="d-flex align-items-center gap-2"><img src="/assets/images/hero/logo.png" alt="Logo" style="height: 30px; width: auto; object-fit: contain; margin-right: 8px;"><span style="font-family:var(--font-heading);font-size:1rem;font-weight:700;">Admin Panel</span></div></div>
  <nav>
    <a href="/admin/index.php" class="admin-nav-link"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="/admin/projects.php" class="admin-nav-link"><i class="fas fa-building"></i>Projects</a>
    <a href="/admin/certifications.php" class="admin-nav-link"><i class="fas fa-certificate"></i>Certifications</a>
    <a href="/admin/contacts.php" class="admin-nav-link active"><i class="fas fa-envelope"></i>Messages</a>
    <a href="/admin/appointments.php" class="admin-nav-link"><i class="fas fa-calendar-alt"></i>Appointments</a>
    <hr style="border-color:var(--clr-border);margin:1rem 1.5rem;">
    <a href="/index.php" class="admin-nav-link"><i class="fas fa-external-link-alt"></i>View Site</a>
    <a href="/admin/logout.php" class="admin-nav-link" style="color:var(--clr-error)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a>
  </nav>
</aside>
<div class="admin-topbar"><h2 style="font-family:var(--font-heading);font-size:1.2rem;font-weight:700;margin:0;">Contact Messages</h2></div>
<div class="content-area">
  <?php if ($view): ?>
    <div class="card-custom p-4 mb-4">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <h3 style="font-family:var(--font-heading);font-size:1.3rem;"><?= htmlspecialchars($view['subject'] ?: '(No subject)') ?></h3>
          <p style="color:var(--clr-text-muted);font-size:0.88rem;margin:0;">From: <strong style="color:var(--clr-accent)"><?= htmlspecialchars($view['name']) ?></strong> &lt;<?= htmlspecialchars($view['email']) ?>&gt; · Phone: <strong style="color:var(--clr-text)"><?= htmlspecialchars($view['phone'] ?: 'N/A') ?></strong> · <?= date('d M Y H:i', strtotime($view['submitted_at'])) ?></p>
        </div>
        <a href="contacts.php" class="btn-ghost" style="padding:0.4rem 1rem;font-size:0.82rem;">← Back</a>
      </div>
      <div style="background:var(--clr-bg);border-radius:8px;padding:1.5rem;line-height:1.8;color:var(--clr-text-muted);"><?= nl2br(htmlspecialchars($view['message'])) ?></div>
      <div class="mt-3">
        <a href="mailto:<?= htmlspecialchars($view['email']) ?>?subject=Re: <?= urlencode($view['subject']) ?>" class="btn-accent"><i class="fas fa-reply me-1"></i>Reply via Email</a>
      </div>
    </div>
  <?php endif; ?>

  <div class="card-custom">
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <?php foreach ($contacts as $c): ?>
            <tr style="<?= !$c['is_read'] ? 'background:rgba(245,166,35,0.04)' : '' ?>">
              <td><?= $c['id'] ?></td>
              <td style="color:var(--clr-text);font-weight:<?= !$c['is_read'] ? '700' : '400' ?>"><?= htmlspecialchars($c['name']) ?></td>
              <td><?= htmlspecialchars($c['email']) ?></td>
              <td><?= htmlspecialchars($c['phone'] ?: '—') ?></td>
              <td><?= htmlspecialchars(substr($c['subject'], 0, 40)) ?></td>
              <td style="font-size:0.8rem;"><?= date('d M Y', strtotime($c['submitted_at'])) ?></td>
              <td><span class="<?= $c['is_read'] ? 'badge-read' : 'badge-unread' ?>"><?= $c['is_read'] ? 'Read' : 'Unread' ?></span></td>
              <td>
                <a href="?view=<?= $c['id'] ?>" class="btn-outline-accent" style="padding:0.3rem 0.7rem;font-size:0.75rem;margin-right:0.3rem;" title="View"><i class="fas fa-eye"></i></a>
                <?php if (!$c['is_read']): ?>
                  <a href="?mark_read=<?= $c['id'] ?>" class="btn-ghost" style="padding:0.3rem 0.7rem;font-size:0.75rem;margin-right:0.3rem;" title="Mark read"><i class="fas fa-check"></i></a>
                <?php endif; ?>
                <a href="?delete=<?= $c['id'] ?>" class="btn-ghost" style="padding:0.3rem 0.7rem;font-size:0.75rem;color:var(--clr-error);border-color:var(--clr-error);" onclick="return confirm('Delete message?')" title="Delete"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$contacts): ?><tr><td colspan="7" class="text-center" style="color:var(--clr-text-muted);padding:2rem;">No messages yet.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/admin.min.js"></script>
</body>
</html>
