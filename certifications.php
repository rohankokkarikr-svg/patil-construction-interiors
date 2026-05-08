<?php
// ============================================================
// CERTIFICATIONS PAGE — certifications.php
// ============================================================
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Certifications | Pradeepgouda B Patil — Civil Engineer';
$pageDesc  = 'Professional certifications of Pradeepgouda B Patil in civil engineering, construction management, and design tools.';
$pageName  = 'certifications';
include 'includes/header.php';

$certs = getAllCertifications();
?>

<div class="bg-grid"></div>

<section class="section" style="padding-top:8rem;" aria-label="Certifications">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Credentials</span>
      <h1 class="section-title">My <span class="accent">Certifications</span></h1>
      <div class="section-divider"></div>
      <p class="mt-3" style="max-width:600px;margin:0 auto;">Professional certifications that validate my expertise across engineering software, project management, and safety domains.</p>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs mb-5" data-aos="fade-up">
      <button class="cert-filter-btn filter-btn active" data-cat="all">All</button>
      <?php
      $catLabels = ['academic'=>'Academic','professional'=>'Professional','software'=>'Software','training'=>'Training','internship'=>'Internship'];
      $usedCats  = array_unique(array_column($certs, 'category'));
      foreach ($catLabels as $key => $label):
        if (in_array($key, $usedCats)): ?>
          <button class="cert-filter-btn filter-btn" data-cat="<?= $key ?>"><?= $label ?></button>
        <?php endif;
      endforeach; ?>
    </div>

    <!-- Cert Grid -->
    <div class="row g-4">
      <?php foreach ($certs as $c):
        $status = certStatus($c['expiry_date']);
        $statusLabel = certStatusLabel($c['expiry_date']);
      ?>
        <div class="col-lg-4 col-md-6 cert-col" data-cat="<?= htmlspecialchars($c['category']) ?>" data-aos="fade-up">
          <div class="card-custom cert-card h-100">
            <span class="cert-validity <?= $status ?>" title="<?= $statusLabel ?>"></span>
            <div class="cert-issuer"><i class="fas fa-award me-1"></i><?= htmlspecialchars($c['issuer']) ?></div>
            <h3 class="cert-title"><?= htmlspecialchars($c['title']) ?></h3>
            <div class="cert-meta">
              <div class="mb-1"><i class="fas fa-calendar-alt me-2" style="color:var(--clr-accent)"></i>Issued: <?= formatDate($c['issue_date']) ?></div>
              <?php if ($c['expiry_date']): ?>
                <div class="mb-1"><i class="fas fa-hourglass-end me-2" style="color:var(--clr-accent)"></i>
                  Expires: <?= formatDate($c['expiry_date']) ?>
                  <span class="ms-2" style="font-size:0.72rem;font-weight:600;color:<?= $status==='valid' ? 'var(--clr-success)' : ($status==='expiring' ? 'var(--clr-warning)' : 'var(--clr-error)') ?>">
                    <?= $statusLabel ?>
                  </span>
                </div>
              <?php else: ?>
                <div class="mb-1"><i class="fas fa-infinity me-2" style="color:var(--clr-accent)"></i>No Expiry</div>
              <?php endif; ?>
              <?php if ($c['credential_id']): ?>
                <div><i class="fas fa-fingerprint me-2" style="color:var(--clr-accent)"></i>ID: <?= htmlspecialchars($c['credential_id']) ?></div>
              <?php endif; ?>
            </div>
            <div class="mt-3 d-flex gap-2 flex-wrap">
              <?php if ($c['verify_url']): ?>
                <a href="<?= htmlspecialchars($c['verify_url']) ?>" target="_blank" rel="noopener"
                   class="btn-outline-accent" style="padding:0.4rem 1rem;font-size:0.8rem;">
                  <i class="fas fa-external-link-alt me-1"></i>Verify
                </a>
              <?php endif; ?>
              <?php if ($c['image_path']): ?>
                <button class="btn-ghost cert-view-btn" style="padding:0.4rem 1rem;font-size:0.8rem;"
                        data-img="/contraction/<?= htmlspecialchars($c['image_path']) ?>"
                        data-title="<?= htmlspecialchars($c['title']) ?>"
                        data-bs-toggle="modal" data-bs-target="#certLightbox"
                        aria-label="View certificate for <?= htmlspecialchars($c['title']) ?>">
                  <i class="fas fa-image me-1"></i>View
                </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <?php if (empty($certs)): ?>
        <div class="col-12 no-results">
          <i class="fas fa-certificate d-block"></i>
          <h3>No certifications yet</h3>
          <p>Certifications will appear here once added via the admin panel.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Certificate Lightbox Modal -->
<div class="modal fade modal-custom" id="certLightbox" tabindex="-1" aria-label="Certificate Viewer" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="lbTitle"></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="lbImg" src="" alt="Certificate" class="w-100" style="border-radius:8px;max-height:80vh;object-fit:contain;">
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
