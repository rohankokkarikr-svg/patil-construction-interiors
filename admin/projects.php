<?php
// ============================================================
// ADMIN PROJECTS — admin/projects.php
// ============================================================
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$db   = getDB();
$action = $_GET['action'] ?? 'list';
$msg  = '';
$msgType = 'success';
$editProject = null;

// ── DELETE ──
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $db->prepare("DELETE FROM projects WHERE id=?")->execute([$id]);
    $msg = 'Project deleted.'; $action = 'list';
}

// ── TOGGLE FEATURED ──
if ($action === 'toggle_featured' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $db->prepare("UPDATE projects SET is_featured = NOT is_featured WHERE id=?")->execute([$id]);
    $action = 'list';
}

// ── LOAD FOR EDIT ──
if ($action === 'edit' && isset($_GET['id'])) {
    $editProject = getProjectById((int)$_GET['id']);
    if (!$editProject) { $action = 'list'; $msg = 'Project not found.'; $msgType = 'error'; }
}

// ── SAVE (add/edit) ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = htmlspecialchars(trim($_POST['title']       ?? ''));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));
    $category    = in_array($_POST['category'] ?? '', ['building','structural','infrastructure','internship']) ? $_POST['category'] : 'building';
    $tools_used  = htmlspecialchars(trim($_POST['tools_used']  ?? ''));
    $duration    = htmlspecialchars(trim($_POST['duration']    ?? ''));
    $role        = htmlspecialchars(trim($_POST['role']        ?? ''));
    $client      = htmlspecialchars(trim($_POST['client']      ?? ''));
    $location    = htmlspecialchars(trim($_POST['location']    ?? ''));
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $pid         = (int)($_POST['project_id'] ?? 0);

    // Handle image upload
    $image_path = $_POST['existing_image'] ?? 'assets/images/projects/default.jpg';
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
        $fInfo   = finfo_open(FILEINFO_MIME_TYPE);
        $mime    = finfo_file($fInfo, $_FILES['image']['tmp_name']);
        finfo_close($fInfo);
        if (in_array($mime, $allowed)) {
            $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'proj_' . time() . '_' . rand(100,999) . '.' . $ext;
            $dest     = __DIR__ . '/../uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image_path = 'uploads/' . $filename;
            }
        }
    }

    if (!empty($title) && !empty($description)) {
        if ($pid > 0) {
            $db->prepare("UPDATE projects SET title=?,description=?,category=?,tools_used=?,image_path=?,duration=?,role=?,client=?,location=?,is_featured=? WHERE id=?")
               ->execute([$title,$description,$category,$tools_used,$image_path,$duration,$role,$client,$location,$is_featured,$pid]);
            $msg = 'Project updated successfully.';
        } else {
            $db->prepare("INSERT INTO projects (title,description,category,tools_used,image_path,duration,role,client,location,is_featured) VALUES (?,?,?,?,?,?,?,?,?,?)")
               ->execute([$title,$description,$category,$tools_used,$image_path,$duration,$role,$client,$location,$is_featured]);
            $msg = 'Project added successfully.';
        }
        $action = 'list';
    } else {
        $msg = 'Title and description are required.'; $msgType = 'error'; $action = $pid ? 'edit' : 'add';
        if ($pid) $editProject = getProjectById($pid);
    }
}

$projects = getAllProjects();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/png" href="/assets/images/hero/logo.png">
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Manage Projects | Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Raleway:wght@500;600&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/style.min.css">
  <link rel="stylesheet" href="/assets/css/dark-theme.min.css">
  <link rel="stylesheet" href="/assets/css/responsive.min.css">
  <style>
    body{background:var(--clr-bg);}
    .admin-topbar{height:64px;background:var(--clr-bg2);border-bottom:1px solid var(--clr-border);display:flex;align-items:center;padding:0 2rem;position:fixed;top:0;left:260px;right:0;z-index:50;}
    .content-area{margin-left:260px;padding:5rem 2rem 2rem;}
  </style>
</head>
<body>

<aside class="admin-sidebar">
  <div class="px-4 mb-4"><div class="d-flex align-items-center gap-2"><img src="/assets/images/hero/logo.png" alt="Logo" style="height: 30px; width: auto; object-fit: contain; margin-right: 8px;"><span style="font-family:var(--font-heading);font-size:1rem;font-weight:700;">Admin Panel</span></div></div>
  <nav>
    <a href="/admin/index.php"         class="admin-nav-link"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="/admin/projects.php"       class="admin-nav-link active"><i class="fas fa-building"></i>Projects</a>
    <a href="/admin/certifications.php" class="admin-nav-link"><i class="fas fa-certificate"></i>Certifications</a>
    <a href="/admin/contacts.php"       class="admin-nav-link"><i class="fas fa-envelope"></i>Messages</a>
    <a href="/admin/appointments.php"   class="admin-nav-link"><i class="fas fa-calendar-alt"></i>Appointments</a>
    <hr style="border-color:var(--clr-border);margin:1rem 1.5rem;">
    <a href="/index.php"  class="admin-nav-link"><i class="fas fa-external-link-alt"></i>View Site</a>
    <a href="/admin/logout.php" class="admin-nav-link" style="color:var(--clr-error)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a>
  </nav>
</aside>

<div class="admin-topbar">
  <h2 style="font-family:var(--font-heading);font-size:1.2rem;font-weight:700;margin:0;">Manage Projects</h2>
  <a href="?action=add" class="btn-accent ms-auto" style="padding:0.5rem 1.2rem;font-size:0.85rem;"><i class="fas fa-plus me-1"></i>Add Project</a>
</div>

<div class="content-area">
  <?php if ($msg): ?>
    <div class="alert-custom alert-<?= $msgType === 'error' ? 'error' : 'success' ?> mb-4">
      <i class="fas fa-<?= $msgType === 'error' ? 'exclamation-circle' : 'check-circle' ?>"></i><?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  <?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- ADD / EDIT FORM -->
    <div class="card-custom p-4 mb-4">
      <h3 class="skill-group-title mb-4"><i class="fas fa-<?= $action === 'edit' ? 'edit' : 'plus' ?> me-2"></i><?= $action === 'edit' ? 'Edit Project' : 'Add New Project' ?></h3>
      <form method="POST" enctype="multipart/form-data">
        <?php if ($editProject): ?><input type="hidden" name="project_id" value="<?= $editProject['id'] ?>"><?php endif; ?>
        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editProject['image_path'] ?? 'assets/images/projects/default.jpg') ?>">
        <div class="row g-3">
          <div class="col-sm-8">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($editProject['title'] ?? '') ?>"
                   style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
          </div>
          <div class="col-sm-4">
            <label class="form-label">Category *</label>
            <select name="category" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              <?php foreach (['building','structural','infrastructure','internship'] as $cat): ?>
                <option value="<?= $cat ?>" <?= ($editProject['category'] ?? '') === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Description *</label>
            <textarea name="description" class="form-control" rows="4" required
                      style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;"><?= htmlspecialchars($editProject['description'] ?? '') ?></textarea>
          </div>
          <div class="col-sm-6">
            <label class="form-label">Tools Used (comma-separated)</label>
            <input type="text" name="tools_used" class="form-control" value="<?= htmlspecialchars($editProject['tools_used'] ?? '') ?>"
                   style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;" placeholder="AutoCAD, Revit, STAAD Pro">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Duration</label>
            <input type="text" name="duration" class="form-control" value="<?= htmlspecialchars($editProject['duration'] ?? '') ?>"
                   style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;" placeholder="Jan 2024 – Mar 2024">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Your Role</label>
            <input type="text" name="role" class="form-control" value="<?= htmlspecialchars($editProject['role'] ?? '') ?>"
                   style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Client</label>
            <input type="text" name="client" class="form-control" value="<?= htmlspecialchars($editProject['client'] ?? '') ?>"
                   style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($editProject['location'] ?? '') ?>"
                   style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Project Image (JPEG/PNG/WebP)</label>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp"
                   style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
          </div>
          <div class="col-12">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1"
                     <?= ($editProject['is_featured'] ?? 0) ? 'checked' : '' ?>>
              <label class="form-check-label" for="is_featured" style="color:var(--clr-text-muted);font-size:0.9rem;">Show on Home Page (Featured)</label>
            </div>
          </div>
          <div class="col-12 d-flex gap-3">
            <button type="submit" class="btn-accent"><i class="fas fa-save me-1"></i>Save Project</button>
            <a href="?action=list" class="btn-ghost">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  <?php endif; ?>

  <!-- PROJECT LIST -->
  <div class="card-custom">
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead><tr>
          <th>#</th><th>Title</th><th>Category</th><th>Featured</th><th>Added</th><th>Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($projects as $p): ?>
            <tr>
              <td><?= $p['id'] ?></td>
              <td><strong style="color:var(--clr-text);"><?= htmlspecialchars($p['title']) ?></strong></td>
              <td><span class="card-category"><?= ucfirst($p['category']) ?></span></td>
              <td>
                <a href="?action=toggle_featured&id=<?= $p['id'] ?>" title="Toggle featured">
                  <i class="fas fa-star" style="color:<?= $p['is_featured'] ? 'var(--clr-accent)' : 'var(--clr-border)' ?>;font-size:1.1rem;"></i>
                </a>
              </td>
              <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
              <td>
                <a href="?action=edit&id=<?= $p['id'] ?>" class="btn-outline-accent" style="padding:0.3rem 0.8rem;font-size:0.78rem;margin-right:0.4rem;"><i class="fas fa-edit"></i></a>
                <a href="?action=delete&id=<?= $p['id'] ?>" class="btn-ghost" style="padding:0.3rem 0.8rem;font-size:0.78rem;color:var(--clr-error);border-color:var(--clr-error);"
                   onclick="return confirm('Delete this project?')"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$projects): ?><tr><td colspan="6" class="text-center" style="color:var(--clr-text-muted);padding:2rem;">No projects yet.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/admin.min.js"></script>
</body>
</html>
