<?php
// ============================================================
// ADMIN CERTIFICATIONS — admin/certifications.php
// ============================================================
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

$db     = getDB();
$action = $_GET['action'] ?? 'list';
$msg    = ''; $msgType = 'success'; $editCert = null;

if ($action === 'delete' && isset($_GET['id'])) {
    $db->prepare("DELETE FROM certifications WHERE id=?")->execute([(int)$_GET['id']]);
    $msg = 'Certification deleted.'; $action = 'list';
}
if ($action === 'edit' && isset($_GET['id'])) {
    $s = $db->prepare("SELECT * FROM certifications WHERE id=?");
    $s->execute([(int)$_GET['id']]); $editCert = $s->fetch();
    if (!$editCert) { $action='list'; $msg='Not found.'; $msgType='error'; }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = htmlspecialchars(trim($_POST['title']       ?? ''));
    $issuer      = htmlspecialchars(trim($_POST['issuer']      ?? ''));
    $category    = $_POST['category']    ?? 'professional';
    $issue_date  = $_POST['issue_date']  ?? '';
    $expiry_date = $_POST['expiry_date'] ?: null;
    $credential  = htmlspecialchars(trim($_POST['credential_id'] ?? ''));
    $verify_url  = htmlspecialchars(trim($_POST['verify_url']    ?? ''));
    $cid         = (int)($_POST['cert_id'] ?? 0);
    $image_path  = $_POST['existing_image'] ?? null;

    if (!empty($_FILES['image']['name'])) {
        $allowed=['image/jpeg','image/png','image/webp'];
        $fInfo=finfo_open(FILEINFO_MIME_TYPE); $mime=finfo_file($fInfo,$_FILES['image']['tmp_name']); finfo_close($fInfo);
        if (in_array($mime,$allowed)) {
            $ext=pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
            $fn='cert_'.time().'_'.rand(100,999).'.'.$ext;
            $dest=__DIR__.'/../uploads/'.$fn;
            if (move_uploaded_file($_FILES['image']['tmp_name'],$dest)) $image_path='uploads/'.$fn;
        }
    }

    $validCats = ['academic','professional','software','training','internship'];
    if (!in_array($category,$validCats)) $category='professional';

    if (!empty($title) && !empty($issuer) && !empty($issue_date)) {
        if ($cid > 0) {
            $db->prepare("UPDATE certifications SET title=?,issuer=?,category=?,issue_date=?,expiry_date=?,credential_id=?,verify_url=?,image_path=? WHERE id=?")
               ->execute([$title,$issuer,$category,$issue_date,$expiry_date,$credential,$verify_url,$image_path,$cid]);
            $msg='Certification updated.';
        } else {
            $db->prepare("INSERT INTO certifications (title,issuer,category,issue_date,expiry_date,credential_id,verify_url,image_path) VALUES (?,?,?,?,?,?,?,?)")
               ->execute([$title,$issuer,$category,$issue_date,$expiry_date,$credential,$verify_url,$image_path]);
            $msg='Certification added.';
        }
        $action='list';
    } else {
        $msg='Title, issuer and issue date are required.'; $msgType='error'; $action=$cid?'edit':'add';
        if ($cid) { $s=$db->prepare("SELECT * FROM certifications WHERE id=?"); $s->execute([$cid]); $editCert=$s->fetch(); }
    }
}
$certs = $db->query("SELECT * FROM certifications ORDER BY issue_date DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><title>Certifications | Admin</title><meta name="robots" content="noindex,nofollow">
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="/contraction/assets/css/style.css">
  <link rel="stylesheet" href="/contraction/assets/css/dark-theme.css">
  <style>body{background:var(--clr-bg);}.admin-topbar{height:64px;background:var(--clr-bg2);border-bottom:1px solid var(--clr-border);display:flex;align-items:center;padding:0 2rem;position:fixed;top:0;left:260px;right:0;z-index:50;}.content-area{margin-left:260px;padding:5rem 2rem 2rem;}
  .fc{background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;width:100%;}</style>
</head>
<body>
<aside class="admin-sidebar">
  <div class="px-4 mb-4"><div class="d-flex align-items-center gap-2"><span class="logo-initials sm">AC</span><span style="font-family:var(--font-heading);font-size:1rem;font-weight:700;">Admin Panel</span></div></div>
  <nav>
    <a href="/contraction/admin/index.php" class="admin-nav-link"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="/contraction/admin/projects.php" class="admin-nav-link"><i class="fas fa-building"></i>Projects</a>
    <a href="/contraction/admin/certifications.php" class="admin-nav-link active"><i class="fas fa-certificate"></i>Certifications</a>
    <a href="/contraction/admin/contacts.php" class="admin-nav-link"><i class="fas fa-envelope"></i>Messages</a>
    <a href="/contraction/admin/appointments.php" class="admin-nav-link"><i class="fas fa-calendar-alt"></i>Appointments</a>
    <hr style="border-color:var(--clr-border);margin:1rem 1.5rem;">
    <a href="/contraction/index.php" class="admin-nav-link"><i class="fas fa-external-link-alt"></i>View Site</a>
    <a href="/contraction/admin/logout.php" class="admin-nav-link" style="color:var(--clr-error)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a>
  </nav>
</aside>
<div class="admin-topbar"><h2 style="font-family:var(--font-heading);font-size:1.2rem;font-weight:700;margin:0;">Certifications</h2><a href="?action=add" class="btn-accent ms-auto" style="padding:0.5rem 1.2rem;font-size:0.85rem;"><i class="fas fa-plus me-1"></i>Add Certification</a></div>
<div class="content-area">
  <?php if ($msg): ?><div class="alert-custom alert-<?= $msgType==='error'?'error':'success' ?> mb-4"><i class="fas fa-<?= $msgType==='error'?'exclamation-circle':'check-circle' ?>"></i><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="card-custom p-4 mb-4">
      <h3 class="skill-group-title mb-4"><?= $action==='edit'?'Edit':'Add' ?> Certification</h3>
      <form method="POST" enctype="multipart/form-data">
        <?php if ($editCert): ?><input type="hidden" name="cert_id" value="<?= $editCert['id'] ?>"><?php endif; ?>
        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editCert['image_path'] ?? '') ?>">
        <div class="row g-3">
          <div class="col-sm-8"><label class="form-label">Title *</label><input type="text" name="title" class="fc" required value="<?= htmlspecialchars($editCert['title'] ?? '') ?>"></div>
          <div class="col-sm-4"><label class="form-label">Category</label>
            <select name="category" class="fc">
              <?php foreach (['academic','professional','software','training','internship'] as $cat): ?>
                <option value="<?= $cat ?>" <?= ($editCert['category'] ?? '') === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-sm-6"><label class="form-label">Issuer *</label><input type="text" name="issuer" class="fc" required value="<?= htmlspecialchars($editCert['issuer'] ?? '') ?>"></div>
          <div class="col-sm-3"><label class="form-label">Issue Date *</label><input type="date" name="issue_date" class="fc" required value="<?= $editCert['issue_date'] ?? '' ?>"></div>
          <div class="col-sm-3"><label class="form-label">Expiry Date</label><input type="date" name="expiry_date" class="fc" value="<?= $editCert['expiry_date'] ?? '' ?>"></div>
          <div class="col-sm-6"><label class="form-label">Credential ID</label><input type="text" name="credential_id" class="fc" value="<?= htmlspecialchars($editCert['credential_id'] ?? '') ?>"></div>
          <div class="col-sm-6"><label class="form-label">Verify URL</label><input type="url" name="verify_url" class="fc" value="<?= htmlspecialchars($editCert['verify_url'] ?? '') ?>"></div>
          <div class="col-12"><label class="form-label">Certificate Image</label><input type="file" name="image" class="fc" accept="image/jpeg,image/png,image/webp"></div>
          <div class="col-12 d-flex gap-3"><button type="submit" class="btn-accent"><i class="fas fa-save me-1"></i>Save</button><a href="?action=list" class="btn-ghost">Cancel</a></div>
        </div>
      </form>
    </div>
  <?php endif; ?>

  <div class="card-custom">
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead><tr><th>#</th><th>Title</th><th>Issuer</th><th>Category</th><th>Issue Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <?php foreach ($certs as $c):
            $s = certStatus($c['expiry_date']); $sl = certStatusLabel($c['expiry_date']); ?>
            <tr>
              <td><?= $c['id'] ?></td>
              <td style="color:var(--clr-text);font-weight:600;"><?= htmlspecialchars($c['title']) ?></td>
              <td><?= htmlspecialchars($c['issuer']) ?></td>
              <td><span class="card-category"><?= ucfirst($c['category']) ?></span></td>
              <td><?= date('d M Y', strtotime($c['issue_date'])) ?></td>
              <td><span style="font-size:0.78rem;font-weight:600;color:<?= $s==='valid'?'var(--clr-success)':($s==='expiring'?'var(--clr-warning)':'var(--clr-error)') ?>"><?= $sl ?></span></td>
              <td>
                <a href="?action=edit&id=<?= $c['id'] ?>" class="btn-outline-accent" style="padding:0.3rem 0.8rem;font-size:0.78rem;margin-right:0.3rem;"><i class="fas fa-edit"></i></a>
                <a href="?action=delete&id=<?= $c['id'] ?>" class="btn-ghost" style="padding:0.3rem 0.8rem;font-size:0.78rem;color:var(--clr-error);border-color:var(--clr-error);" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$certs): ?><tr><td colspan="7" class="text-center" style="color:var(--clr-text-muted);padding:2rem;">No certifications yet.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
