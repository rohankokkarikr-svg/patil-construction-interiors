<?php
// ============================================================
// ADMIN DASHBOARD — admin/index.php
// ============================================================
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$projectCount = getProjectCount();
$contactCount = getContactCount();
$unreadCount  = getContactCount(true);
$db           = getDB();
$certCount    = (int)$db->query("SELECT COUNT(*) FROM certifications")->fetchColumn();
$apptCount    = (int)$db->query("SELECT COUNT(*) FROM appointments WHERE status='pending'")->fetchColumn();
$recentContacts = $db->query("SELECT * FROM contacts ORDER BY submitted_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/png" href="/assets/images/hero/logo.png">
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Dashboard | PATIL’s construction & interior’s Portfolio</title>
  <meta name="robots" content="noindex,nofollow">
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Raleway:wght@500;600&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/dark-theme.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
  <style>
    body { background: var(--clr-bg); }
    .admin-topbar { height: 64px; background: var(--clr-bg2); border-bottom: 1px solid var(--clr-border); display: flex; align-items: center; padding: 0 2rem; position: fixed; top: 0; left: 260px; right: 0; z-index: 50; }
    .page-wrap { display: flex; min-height: 100vh; }
    .content-area { flex: 1; margin-left: 260px; padding: 5rem 2rem 2rem; }
    @media(max-width:1024px){ .admin-sidebar{position:relative;width:100%;min-height:auto;border-right:none;border-bottom:1px solid var(--clr-border);} .content-area,.admin-topbar{margin-left:0;left:0;} .page-wrap{flex-direction:column;} }
  </style>
</head>
<body>

<!-- Sidebar -->
<aside class="admin-sidebar">
  <div class="px-4 mb-4">
    <div class="d-flex align-items-center gap-2">
      <img src="/assets/images/hero/logo.png" alt="Logo" style="height: 30px; width: auto; object-fit: contain; margin-right: 8px;">
      <span style="font-family:var(--font-heading);font-size:1rem;font-weight:700;">Admin Panel</span>
    </div>
  </div>
  <nav aria-label="Admin navigation">
    <a href="/admin/index.php"         class="admin-nav-link active"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="/admin/projects.php"       class="admin-nav-link"><i class="fas fa-building"></i>Projects</a>
    <a href="/admin/certifications.php" class="admin-nav-link"><i class="fas fa-certificate"></i>Certifications</a>
    <a href="/admin/contacts.php"       class="admin-nav-link"><i class="fas fa-envelope"></i>Messages <?php if($unreadCount): ?><span class="ms-auto badge bg-danger"><?= $unreadCount ?></span><?php endif; ?></a>
    <a href="/admin/appointments.php"   class="admin-nav-link"><i class="fas fa-calendar-alt"></i>Appointments</a>
    <a href="/admin/statistics.php"     class="admin-nav-link"><i class="fas fa-chart-bar"></i>Statistics</a>
    <hr style="border-color:var(--clr-border);margin:1rem 1.5rem;">
    <a href="/index.php"               class="admin-nav-link"><i class="fas fa-external-link-alt"></i>View Site</a>
    <a href="/admin/logout.php"        class="admin-nav-link" style="color:var(--clr-error)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a>
  </nav>
</aside>

<!-- Top Bar -->
<div class="admin-topbar">
  <h2 style="font-family:var(--font-heading);font-size:1.2rem;font-weight:700;margin:0;">Dashboard</h2>
  <div class="ms-auto d-flex align-items-center gap-3">
    <span style="font-size:0.85rem;color:var(--clr-text-muted);">Welcome, <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
  </div>
</div>

<div class="content-area">
  <!-- Stat Cards -->
  <div class="row g-4 mb-5">
    <?php
    $cards = [
      ['label'=>'Total Projects',    'value'=>$projectCount,'icon'=>'fas fa-building',     'link'=>'projects.php'],
      ['label'=>'Certifications',    'value'=>$certCount,   'icon'=>'fas fa-certificate',   'link'=>'certifications.php'],
      ['label'=>'Total Messages',    'value'=>$contactCount,'icon'=>'fas fa-envelope',      'link'=>'contacts.php'],
      ['label'=>'Pending Appts.',    'value'=>$apptCount,   'icon'=>'fas fa-calendar-check','link'=>'appointments.php'],
    ];
    foreach ($cards as $card): ?>
      <div class="col-sm-6 col-xl-3">
        <a href="/admin/<?= $card['link'] ?>" style="text-decoration:none;">
          <div class="admin-stat-card card-custom">
            <div class="admin-stat-icon"><i class="<?= $card['icon'] ?>"></i></div>
            <div>
              <div class="admin-stat-num"><?= $card['value'] ?></div>
              <div class="admin-stat-label"><?= $card['label'] ?></div>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Quick Actions -->
  <div class="row g-4 mb-5">
    <div class="col-lg-6">
      <div class="card-custom p-4">
        <h3 class="skill-group-title"><i class="fas fa-bolt me-2"></i>Quick Actions</h3>
        <div class="d-grid gap-2">
          <a href="/admin/projects.php?action=add"       class="btn-accent justify-content-start"><i class="fas fa-plus"></i>Add New Project</a>
          <a href="/admin/certifications.php?action=add" class="btn-outline-accent justify-content-start"><i class="fas fa-plus"></i>Add Certification</a>
          <a href="/admin/contacts.php"                  class="btn-ghost justify-content-start"><i class="fas fa-envelope"></i>View Messages <?php if($unreadCount): ?>(<?= $unreadCount ?> unread)<?php endif; ?></a>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card-custom p-4">
        <h3 class="skill-group-title"><i class="fas fa-clock me-2"></i>Recent Messages</h3>
        <?php if ($recentContacts): ?>
          <?php foreach ($recentContacts as $c): ?>
            <div style="border-bottom:1px solid var(--clr-border);padding:0.6rem 0;font-size:0.87rem;">
              <div style="display:flex;justify-content:space-between;align-items:center;">
                <strong style="color:var(--clr-text);"><?= htmlspecialchars($c['name']) ?></strong>
                <span class="<?= $c['is_read'] ? 'badge-read' : 'badge-unread' ?>"><?= $c['is_read'] ? 'Read' : 'Unread' ?></span>
              </div>
              <div style="color:var(--clr-text-muted);"><?= htmlspecialchars(substr($c['subject'], 0, 50)) ?></div>
              <div style="color:var(--clr-text-muted);font-size:0.75rem;"><?= date('d M Y, H:i', strtotime($c['submitted_at'])) ?></div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color:var(--clr-text-muted);font-size:0.88rem;">No messages yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/admin.js"></script>
</body>
</html>
