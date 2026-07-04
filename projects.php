<?php
// ============================================================
// PROJECTS PAGE — projects.php
// ============================================================
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Projects | Pradeepgouda B Patil — Civil Engineer & Construction Specialist';
$pageDesc  = 'Explore completed building projects, road works, and interior/exterior designs by Pradeepgouda B Patil at Patil\'s Construction & Interiors, Gokak.';
$pageName  = 'projects';
include 'includes/header.php';

$projects = getAllProjects();
?>

<div class="bg-grid"></div>

<section class="section" style="padding-top:8rem;" aria-label="Projects">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Portfolio</span>
      <h1 class="section-title">My <span class="accent">Projects</span></h1>
      <div class="section-divider"></div>
      <p class="mt-3" style="max-width:600px;margin:0 auto;">A curated collection of real construction work spanning building projects, road infrastructure, interior design, and government contract works across Gokak &amp; Karnataka.</p>
    </div>

    <!-- Controls -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" data-aos="fade-up">
      <div class="filter-tabs">
        <button class="filter-btn active" data-filter="all">All (<?= count($projects) ?>)</button>
        <?php
        $categories = ['building'=>'Building','interior'=>'Interior','road'=>'Road Work','government'=>'Govt. Contract'];
        foreach ($categories as $key => $label):
          $count = count(array_filter($projects, fn($p) => $p['category'] === $key));
          if ($count > 0): ?>
            <button class="filter-btn" data-filter="<?= $key ?>"><?= $label ?> (<?= $count ?>)</button>
          <?php endif;
        endforeach; ?>
      </div>
      <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="search" id="projectSearch" placeholder="Search projects or tools…" aria-label="Search projects">
      </div>
    </div>

    <!-- Grid -->
    <div class="row g-4" id="projectsGrid">
      <?php foreach ($projects as $p):
        $searchStr = implode(' ', [$p['title'], $p['description'], $p['tools_used'], $p['category']]);
      ?>
        <div class="col-lg-4 col-md-6 project-col" data-category="<?= htmlspecialchars($p['category']) ?>">
          <div class="card-custom project-card h-100"
               data-category="<?= htmlspecialchars($p['category']) ?>"
               data-search="<?= htmlspecialchars(strtolower($searchStr)) ?>"
               data-title="<?= htmlspecialchars($p['title']) ?>"
               data-desc="<?= htmlspecialchars($p['description']) ?>"
               data-tools="<?= htmlspecialchars($p['tools_used']) ?>"
               data-duration="<?= htmlspecialchars($p['duration'] ?? '') ?>"
               data-role="<?= htmlspecialchars($p['role'] ?? '') ?>"
               data-client="<?= htmlspecialchars($p['client'] ?? '') ?>"
               data-location="<?= htmlspecialchars($p['location'] ?? '') ?>"
               data-img="<?= get_img_src($p['image_path']) ?>"
               data-aos="fade-up">
            <div class="card-img-wrap">
              <img src="<?= get_img_src($p['image_path']) ?>"
                   alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy"
                   onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'200\' viewBox=\'0 0 300 200\'%3E%3Crect width=\'300\' height=\'200\' fill=\'%2316213E\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23F5A623\' font-size=\'40\'%3E🏗️%3C/text%3E%3C/svg%3E'">
            </div>
            <div class="card-body d-flex flex-column">
              <span class="card-category"><?= ucfirst(htmlspecialchars($p['category'])) ?></span>
              <h2 class="card-title"><?= htmlspecialchars($p['title']) ?></h2>
              <p class="card-desc flex-grow-1"><?= htmlspecialchars(substr($p['description'], 0, 120)) ?>…</p>
              <div class="mb-3"><?= toolBadges($p['tools_used'] ?? '') ?></div>
              <?php if ($p['duration']): ?>
                <p class="mb-3" style="font-size:0.78rem;color:var(--clr-text-muted);">
                  <i class="fas fa-calendar-alt me-1" style="color:var(--clr-accent)"></i><?= htmlspecialchars($p['duration']) ?>
                </p>
              <?php endif; ?>
              <button class="btn-outline-accent btn-sm view-details-btn mt-auto"
                      data-bs-toggle="modal" data-bs-target="#projectModal"
                      aria-label="View details for <?= htmlspecialchars($p['title']) ?>">
                <i class="fas fa-eye me-1"></i>View Details
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- No Results -->
    <div id="noResults" class="no-results" style="display:none;">
      <i class="fas fa-search-minus d-block"></i>
      <h3>No projects found</h3>
      <p>Try a different filter or search term.</p>
    </div>
  </div>
</section>

<!-- Project Detail Modal (same as index.php) -->
<div class="modal fade modal-custom" id="projectModal" tabindex="-1" aria-label="Project Details" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <span class="card-category" id="modalCategory"></span>
          <h3 class="modal-title mt-1" id="modalTitle"></h3>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modalImg" src="" alt="Project image" class="w-100 mb-4"
             style="border-radius:8px;max-height:300px;object-fit:cover;"
             onerror="this.style.display='none'">
        <p id="modalDesc" style="color:var(--clr-text-muted);line-height:1.8;"></p>
        <div class="row mt-3">
          <div class="col-sm-6 mb-3">
            <div style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.3rem;">Duration</div>
            <div id="modalDuration" style="font-weight:600;"></div>
          </div>
          <div class="col-sm-6 mb-3">
            <div style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.3rem;">My Role</div>
            <div id="modalRole" style="font-weight:600;"></div>
          </div>
          <div class="col-sm-6 mb-3">
            <div style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.3rem;">Client</div>
            <div id="modalClient" style="font-weight:600;"></div>
          </div>
          <div class="col-sm-6 mb-3">
            <div style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.3rem;">Location</div>
            <div id="modalLocation" style="font-weight:600;"></div>
          </div>
        </div>
        <div>
          <div style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.5rem;">Tools Used</div>
          <div id="modalTools"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-ghost" data-bs-dismiss="modal">Close</button>
        <a href="/contact.php" class="btn-accent"><i class="fas fa-envelope me-1"></i>Discuss This Project</a>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
