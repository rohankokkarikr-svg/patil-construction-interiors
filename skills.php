<?php
// ============================================================
// SKILLS PAGE — skills.php
// ============================================================
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Skills & Expertise | Pradeepgouda B Patil — Civil Engineer & Construction Specialist';
$pageDesc  = 'Explore the professional skills of Pradeepgouda B Patil — AutoCAD, Revit, 3ds Max, V-Ray, Lumion, STAAD Pro, and hands-on construction & interior design expertise.';
$pageName  = 'skills';
include 'includes/header.php';

$softwareSkills = [
  ['name'=>'AutoCAD',         'icon'=>'fas fa-drafting-compass', 'pct'=>92],
  ['name'=>'Autodesk Revit',  'icon'=>'fas fa-cube',             'pct'=>85],
  ['name'=>'3ds Max',         'icon'=>'fas fa-cube',             'pct'=>78],
  ['name'=>'V-Ray',           'icon'=>'fas fa-sun',              'pct'=>75],
  ['name'=>'Lumion',          'icon'=>'fas fa-film',             'pct'=>80],
  ['name'=>'STAAD Pro',       'icon'=>'fas fa-industry',         'pct'=>78],
  ['name'=>'Microsoft Office','icon'=>'fas fa-file-word',        'pct'=>88],
];

$technicalSkills = [
  ['name'=>'Building Construction',   'icon'=>'fas fa-building',      'pct'=>90],
  ['name'=>'Interior Design',         'icon'=>'fas fa-paint-roller',   'pct'=>88],
  ['name'=>'Exterior Design',         'icon'=>'fas fa-home',           'pct'=>85],
  ['name'=>'Site Management',         'icon'=>'fas fa-hard-hat',       'pct'=>88],
  ['name'=>'Estimation & BOQ',        'icon'=>'fas fa-file-alt',       'pct'=>85],
  ['name'=>'Project Management',      'icon'=>'fas fa-tasks',          'pct'=>82],
  ['name'=>'Govt. Contract Works',    'icon'=>'fas fa-landmark',       'pct'=>80],
  ['name'=>'Road Construction',       'icon'=>'fas fa-road',           'pct'=>78],
];

$softSkills = [
  ['icon'=>'fas fa-users',          'name'=>'Team Leadership'],
  ['icon'=>'fas fa-comments',       'name'=>'Communication'],
  ['icon'=>'fas fa-lightbulb',      'name'=>'Problem Solving'],
  ['icon'=>'fas fa-clock',          'name'=>'Time Management'],
  ['icon'=>'fas fa-handshake',      'name'=>'Client Relations'],
  ['icon'=>'fas fa-sync-alt',       'name'=>'Adaptability'],
  ['icon'=>'fas fa-check-double',   'name'=>'Attention to Detail'],
  ['icon'=>'fas fa-chart-line',     'name'=>'Critical Thinking'],
];
?>

<div class="bg-grid"></div>

<section class="section" style="padding-top:8rem;" aria-label="Skills">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Expertise</span>
      <h1 class="section-title">Skills & <span class="accent">Proficiency</span></h1>
      <div class="section-divider"></div>
      <p class="mt-3" style="max-width:600px;margin:0 auto;">Professional skills built through real construction projects, interior design work, and hands-on site management experience across government and private sectors.</p>
    </div>

    <div class="row gy-5">
      <!-- Software Skills -->
      <div class="col-lg-6" data-aos="fade-right">
        <div class="card-custom p-4">
          <h3 class="skill-group-title"><i class="fas fa-laptop-code me-2"></i>Software Skills</h3>
          <?php foreach ($softwareSkills as $sk): ?>
            <div class="skill-item">
              <div class="skill-header">
                <span class="skill-name"><i class="<?= $sk['icon'] ?>"></i><?= $sk['name'] ?></span>
                <span class="skill-pct">0%</span>
              </div>
              <div class="skill-track">
                <div class="skill-bar" data-pct="<?= $sk['pct'] ?>"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Technical Skills -->
      <div class="col-lg-6" data-aos="fade-left">
        <div class="card-custom p-4">
          <h3 class="skill-group-title"><i class="fas fa-hard-hat me-2"></i>Technical Skills</h3>
          <?php foreach ($technicalSkills as $sk): ?>
            <div class="skill-item">
              <div class="skill-header">
                <span class="skill-name"><i class="<?= $sk['icon'] ?>"></i><?= $sk['name'] ?></span>
                <span class="skill-pct">0%</span>
              </div>
              <div class="skill-track">
                <div class="skill-bar" data-pct="<?= $sk['pct'] ?>"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Soft Skills -->
      <div class="col-12" data-aos="fade-up">
        <h3 class="skill-group-title text-center mb-4"><i class="fas fa-users me-2"></i>Soft Skills & Management</h3>
        <div class="row g-3">
          <?php foreach ($softSkills as $sk): ?>
            <div class="col-6 col-sm-4 col-md-3">
              <div class="soft-skill-badge" data-aos="zoom-in">
                <i class="<?= $sk['icon'] ?>"></i>
                <span><?= $sk['name'] ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Standards & Codes -->
    <div class="mt-6" data-aos="fade-up" style="margin-top:4rem;">
      <div class="card-custom p-4">
        <h3 class="skill-group-title"><i class="fas fa-book me-2"></i>Standards & Codes</h3>
        <div class="row g-2">
          <?php
          $codes = ['IS 456 (RCC Design)','IS 800 (Steel Structures)','IS 875 (Load Standards)','IS 1893 (Seismic Design)','IS 2911 (Pile Foundations)','PWD Specifications','CPWD Guidelines','National Building Code (NBC 2016)','Karnataka Building Bylaws','RERA Compliance'];
          foreach ($codes as $c): ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
              <div class="tool-badge" style="display:block;text-align:center;padding:0.5rem;"><?= $c ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
