<?php
// ============================================================
// HOME PAGE — index.php
// ============================================================
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'PATIL’s Construction & Interior’s | Civil & Structural Engineer — Portfolio';
$pageDesc = 'Welcome to the professional portfolio of PATIL’s construction & interior’s — Civil & Structural Engineer specializing in structural design, BIM, infrastructure, and construction project management.';
$pageName = 'home';
include 'includes/header.php';

$featuredProjects = getFeaturedProjects();
?>

<div class="bg-grid"></div>

<!-- ===== HERO ===== -->
<section class="hero-section" id="hero" aria-label="Hero">
  <div class="hero-bg" role="img" aria-label="Construction site at dusk"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content container text-center">
    <div data-aos="fade-down" data-aos-duration="800">
      <span class="hero-label"><i class="fas fa-hard-hat me-2"></i>Civil & Construction Engineering</span>
    </div>
    <h1 class="hero-name" data-aos="fade-up" data-aos-delay="100">
      <span class="brand-patil">PATIL’s</span> <span class="accent">Construction & Interior’s</span>
    </h1>
    <p class="hero-typewriter" data-aos="fade-up" data-aos-delay="200">
      <span class="typewriter-text" id="typewriterText"></span><span class="cursor" aria-hidden="true"></span>
    </p>
    <p class="mt-2 mb-4" data-aos="fade-up" data-aos-delay="250"
      style="color:var(--clr-text-muted); font-size:1.05rem; letter-spacing:0.5px;">
      <i class="fas fa-vr-cardboard me-2" style="color:var(--clr-accent)"></i>COMMITTED TO BUILD BETTER
    </p>
    <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
      <a href="/projects.php" class="btn-accent" id="heroViewProjects">
        <i class="fas fa-folder-open"></i>View My Projects
      </a>
      <a href="/resume/download.php" class="btn-ghost" id="heroDownloadResume">
        <i class="fas fa-download"></i>Download Resume
      </a>
    </div>
    <a href="#stats" class="scroll-chevron" aria-label="Scroll down"><i class="fas fa-chevron-down"></i></a>
  </div>
</section>

<!-- ===== STATS ===== -->
<section class="section section-alt" id="stats" aria-label="Statistics">
  <div class="container">
    <div class="stats-grid">
      <?php
      // Fetch statistics from database
      $db = getDB();
      $stats = $db ? $db->query("SELECT * FROM site_statistics WHERE is_active = 1 ORDER BY stat_order ASC")->fetchAll() : [];
      
      foreach ($stats as $s): ?>
        <div class="stat-card" data-aos="zoom-in">
          <div class="stat-icon"><i class="<?= htmlspecialchars($s['stat_icon']) ?>"></i></div>
          <div class="stat-number" data-count="<?= $s['stat_count'] ?>" data-suffix="<?= htmlspecialchars($s['stat_suffix']) ?>">0</div>
          <div class="stat-label"><?= htmlspecialchars($s['stat_label']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== FEATURED PROJECTS ===== -->
<section class="section" id="featured-projects" aria-label="Featured Projects">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Portfolio</span>
      <h2 class="section-title">Featured <span class="accent">Projects</span></h2>
      <div class="section-divider"></div>
    </div>
    <div class="row gy-4">
      <?php foreach ($featuredProjects as $p): ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up">
          <div class="card-custom project-card h-100" data-category="<?= htmlspecialchars($p['category']) ?>"
            data-title="<?= htmlspecialchars($p['title']) ?>" data-desc="<?= htmlspecialchars($p['description']) ?>"
            data-tools="<?= htmlspecialchars($p['tools_used']) ?>" data-duration="<?= htmlspecialchars($p['duration']) ?>"
            data-role="<?= htmlspecialchars($p['role']) ?>" data-client="<?= htmlspecialchars($p['client']) ?>"
            data-location="<?= htmlspecialchars($p['location']) ?>"
            data-img="/<?= htmlspecialchars($p['image_path']) ?>">
            <div class="card-img-wrap">
          <img src="/<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['title']) ?>"
                loading="lazy">
            </div>
            <div class="card-body">
              <span class="card-category"><?= ucfirst(htmlspecialchars($p['category'])) ?></span>
              <h3 class="card-title"><?= htmlspecialchars($p['title']) ?></h3>
              <p class="card-desc"><?= htmlspecialchars($p['description']) ?></p>
              <div class="mb-3"><?= toolBadges($p['tools_used'] ?? '') ?></div>
              <button class="btn-outline-accent btn-sm view-details-btn" data-bs-toggle="modal"
                data-bs-target="#projectModal" aria-label="View details for <?= htmlspecialchars($p['title']) ?>">
                <i class="fas fa-eye me-1"></i>View Details
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5" data-aos="fade-up">
      <a href="/projects.php" class="btn-accent" id="viewAllProjects">
        <i class="fas fa-arrow-right"></i>View All Projects
      </a>
    </div>
  </div>
</section>

<!-- ===== SKILLS SNAPSHOT ===== -->
<section class="section section-alt" aria-label="Skills Snapshot">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Expertise</span>
      <h2 class="section-title">Core <span class="accent">Skills</span></h2>
      <div class="section-divider"></div>
    </div>
    <div class="row g-3 justify-content-center">
      <?php
      $skills = [
        ['icon' => 'fas fa-drafting-compass', 'name' => 'AutoCAD'],
        ['icon' => 'fas fa-cube',             'name' => 'Autodesk Revit'],
        ['icon' => 'fas fa-industry',         'name' => 'STAAD Pro'],
        ['icon' => 'fas fa-cube',             'name' => '3ds Max / V-Ray'],
        ['icon' => 'fas fa-film',             'name' => 'Lumion'],
        ['icon' => 'fas fa-tasks',            'name' => 'MS Office & Project'],
      ];
      foreach ($skills as $sk): ?>
        <div class="col-6 col-sm-4 col-md-2" data-aos="zoom-in">
          <div class="soft-skill-badge">
            <i class="<?= $sk['icon'] ?>"></i>
            <span><?= $sk['name'] ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4" data-aos="fade-up">
      <a href="/skills.php" class="btn-outline-accent">See All Skills <i
          class="fas fa-arrow-right ms-1"></i></a>
    </div>
  </div>
</section>

<!-- ===== TOOLS PROMO ===== -->
<section class="section" aria-label="Interactive Tools">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Tools</span>
      <h2 class="section-title">Interactive <span class="accent">Tools</span></h2>
      <div class="section-divider"></div>
      <p class="mt-3" style="max-width:600px;margin:0 auto;">Practical engineering tools to help you estimate project
        costs and calculate material quantities — instantly.</p>
    </div>
    <div class="row gy-4">
      <div class="col-md-6" data-aos="fade-right">
        <div class="card-custom p-4 h-100 text-center">
          <div class="mb-3"><i class="fas fa-calculator" style="font-size:3rem;color:var(--clr-accent)"></i></div>
          <h3 class="section-title mb-3">Project Cost <span class="accent">Estimator</span></h3>
          <p>Get an instant rough cost estimate for your construction project — residential, commercial or
            infrastructure. Includes a detailed cost breakdown and pie chart.</p>
          <a href="/tools/estimator.php" class="btn-accent mt-3" id="homeEstimatorBtn">
            <i class="fas fa-calculator"></i>Try Cost Estimator
          </a>
        </div>
      </div>
      <div class="col-md-6" data-aos="fade-left">
        <div class="card-custom p-4 h-100 text-center">
          <div class="mb-3"><i class="fas fa-ruler-combined" style="font-size:3rem;color:var(--clr-accent)"></i></div>
          <h3 class="section-title mb-3">Material <span class="accent">Calculator</span></h3>
          <p>Calculate quantities of concrete, bricks, steel, plaster, tiles and paint using IS-code formulas. Live
            updates, unit toggle, and downloadable results.</p>
          <a href="/tools/calculator.php" class="btn-accent mt-3" id="homeCalcBtn">
            <i class="fas fa-ruler-combined"></i>Try Material Calc
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== PROJECT DETAIL MODAL ===== -->
<div class="modal fade modal-custom" id="projectModal" tabindex="-1" aria-label="Project Details" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <span class="card-category" id="modalCategory"></span>
          <h4 class="modal-title mt-1" id="modalTitle"></h4>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modalImg" src="" alt="Project image" class="w-100 mb-4"
          style="border-radius:8px;max-height:280px;object-fit:cover;">
        <p id="modalDesc" style="color:var(--clr-text-muted);line-height:1.8;"></p>
        <div class="row mt-3">
          <div class="col-sm-6 mb-3">
            <div
              style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.3rem;">
              Duration</div>
            <div id="modalDuration" style="font-weight:600;"></div>
          </div>
          <div class="col-sm-6 mb-3">
            <div
              style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.3rem;">
              My Role</div>
            <div id="modalRole" style="font-weight:600;"></div>
          </div>
          <div class="col-sm-6 mb-3">
            <div
              style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.3rem;">
              Client</div>
            <div id="modalClient" style="font-weight:600;"></div>
          </div>
          <div class="col-sm-6 mb-3">
            <div
              style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.3rem;">
              Location</div>
            <div id="modalLocation" style="font-weight:600;"></div>
          </div>
        </div>
        <div>
          <div
            style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.5rem;">
            Tools Used</div>
          <div id="modalTools"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-ghost" data-bs-dismiss="modal">Close</button>
        <a href="/contact.php" class="btn-accent"><i class="fas fa-envelope me-1"></i>Discuss This
          Project</a>
      </div>
    </div>
  </div>
</div>

<?php
// ── JSON-LD WebSite Schema ──
$schema = [
  '@context' => 'https://schema.org',
  '@type' => 'WebSite',
  'name' => 'PATIL’s construction & interior’s — Civil & Structural Engineer Portfolio',
  'url' => 'http://localhost/',
  'description' => $pageDesc,
];
echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';

include 'includes/footer.php';
?>