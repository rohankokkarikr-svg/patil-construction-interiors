<?php
// ============================================================
// ABOUT PAGE — about.php
// ============================================================
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'About Pradeepgouda B Patil | Civil Engineer & Construction Specialist';
$pageDesc  = 'Learn about Pradeepgouda B Patil — Consulting Civil Engineer at Patil\'s Construction & Interiors, Gokak. Expertise in building projects, road works, interior design and government contracts.';
$pageName  = 'about';
include 'includes/header.php';
?>

<div class="bg-grid"></div>

<!-- Page Head -->
<section class="section" style="padding-top:8rem;" aria-label="About Hero">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="section-label">About Me</span>
      <h1 class="section-title"><span class="brand-patil">Pradeepgouda</span> <span class="accent">B Patil</span></h1>
      <div class="section-divider"></div>
    </div>

    <div class="row gy-5 align-items-center">
      <!-- Profile Photo -->
      <div class="col-lg-4 text-center" data-aos="fade-right">
        <div class="profile-hex-wrap" style="display:inline-block;">
          <div class="hex-frame">
            <div class="hex-frame-inner">
              <img src="/assets/images/profile/patil-profile.jpg"
                   alt="PATIL’s construction & interior’s — Civil Engineer professional headshot"
                   onerror="this.style.display='none'; this.parentElement.style.background='var(--clr-bg3)';">
            </div>
          </div>
          <div class="hex-pulse"></div>
        </div>
        <div class="mt-4">
          <a href="/resume/download.php" class="btn-accent" id="aboutDownloadCV">
            <i class="fas fa-download"></i>Download Resume
          </a>
        </div>
        <!-- Fun Facts -->
        <div class="row g-2 mt-3">
          <?php
          $facts = [
            ['icon'=>'fas fa-building',         'label'=>'Projects Completed', 'value'=>'22+ Buildings'],
            ['icon'=>'fas fa-road',              'label'=>'Road Projects',      'value'=>'3 Govt. Road Works'],
            ['icon'=>'fas fa-paint-roller',      'label'=>'Specialty',          'value'=>'Interior & Exterior Design'],
            ['icon'=>'fas fa-bolt',              'label'=>'Innovation',         'value'=>'Zero Energy Buildings'],
          ];
          foreach ($facts as $f): ?>
            <div class="col-12">
              <div class="card-custom fun-fact-card d-flex align-items-center gap-3 text-start p-3">
                <i class="<?= $f['icon'] ?> fun-fact-icon" style="font-size:1.5rem;color:var(--clr-accent);"></i>
                <div>
                  <div class="fun-fact-label"><?= $f['label'] ?></div>
                  <div class="fun-fact-value"><?= $f['value'] ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Bio -->
      <div class="col-lg-8" data-aos="fade-left">
        <div class="deco-line"><span class="deco-line-text">My Story</span></div>
        <h2 class="section-title mb-4">Building the <span class="accent">Future</span>, One Structure at a Time</h2>

        <p class="mb-4">I'm <strong style="color:var(--clr-text)"><span class="brand-patil" style="font-size:inherit;">Pradeepgouda B Patil</span></strong>, a Civil Engineer and Construction & Interior Specialist based in Gokak, Karnataka, India. I bring hands-on expertise in both government and private construction projects &mdash; from road works and school buildings to premium interior and exterior structures.</p>

        <p class="mb-4">Through my work at <strong style="color:var(--clr-text)">Patil's Construction &amp; Interiors</strong>, I have successfully delivered 22+ building projects, managed complex site operations, and handled all aspects of project estimation and technical problem-solving. I also bring experience in zero energy building design and government-contracted infrastructure works.</p>

        <p class="mb-5">My toolkit spans AutoCAD, Autodesk Revit, 3ds Max, V-Ray, Lumion, STAAD Pro, and Microsoft Office &mdash; allowing me to move seamlessly from structural design to photorealistic visualization and project management.</p>

        <div class="row g-3">
          <?php
          $details = [
            ['label'=>'Full Name', 'value'=>'<span class="brand-patil" style="font-size:inherit;">Pradeepgouda B Patil</span>'],
            ['label'=>'Location',  'value'=>'Gokak, Karnataka, India'],
            ['label'=>'Email',     'value'=>'patilpradeep754@gmail.com'],
            ['label'=>'Phone',     'value'=>'+91 8747061867'],
            ['label'=>'Degree',    'value'=>'BE Civil Engineering &mdash; VTU (2019)'],
            ['label'=>'Status',    'value'=>'Open to Opportunities'],
          ];
          foreach ($details as $d): ?>
            <div class="col-sm-6">
              <div style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:0.2rem;"><?= $d['label'] ?></div>
              <div style="font-weight:600;font-size:0.95rem;"><?= $d['value'] ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div><!-- /.col-lg-8 bio -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>

<!-- ===== EDUCATION TIMELINE ===== -->
<section class="section section-alt" aria-label="Education">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Academic Background</span>
      <h2 class="section-title">Education <span class="accent">Timeline</span></h2>
      <div class="section-divider"></div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8" data-aos="fade-up">
        <div class="timeline">
          <?php
          $edu = [
            ['date'=>'2015 – 2019','title'=>'BE — Civil Engineering','sub'=>'VTU / VSMIT Nippani — CGPA: 6.3','desc'=>'Bachelor of Engineering in Civil Engineering from Visvesvaraya Technological University. Gained strong foundations in structural design, construction management, surveying, and engineering mechanics.'],
            ['date'=>'2013 – 2015','title'=>'PUC — Science (PCM)','sub'=>'Prerana PU College, Hubli — 70%','desc'=>'Pre-University Course with Physics, Chemistry, and Mathematics as core subjects. Built the analytical and scientific foundation for an engineering career.'],
            ['date'=>'2012 – 2013','title'=>'SSLC','sub'=>'KLE School, Athani — CGPA: 8.0','desc'=>'Secondary School Leaving Certificate. Demonstrated consistent academic performance and developed a strong interest in mathematics and science at an early stage.'],
          ];
          foreach ($edu as $e): ?>
            <div class="timeline-item" data-aos="fade-up">
              <div class="timeline-date"><?= $e['date'] ?></div>
              <div class="timeline-title"><?= $e['title'] ?></div>
              <div class="timeline-sub"><?= $e['sub'] ?></div>
              <p class="timeline-desc"><?= $e['desc'] ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== EXPERIENCE TIMELINE ===== -->
<section class="section" aria-label="Experience">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Work History</span>
      <h2 class="section-title">Experience <span class="accent">Timeline</span></h2>
      <div class="section-divider"></div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8" data-aos="fade-up">
        <div class="timeline">
          <?php
          $exp = [
            ['date'=>'2019 – Present','title'=>'Consulting Civil Engineer','sub'=>"Patil's Construction & Interiors, Gokak",'desc'=>'Successfully completed 22+ building projects covering interior & exterior design and execution. Managed full site operations including technical problem-solving and subcontractor coordination. Prepared estimates and approval documentation for 12+ medical shop projects. Worked on ISHA Hospital renewal project and implemented concepts for zero energy / energy-efficient buildings.'],
            ['date'=>'2019 – 2022','title'=>'Civil Engineer — Government Contract','sub'=>'SLC Gokak','desc'=>'Executed 3 road construction projects under government contract, ensuring compliance with PWD specifications and quality standards. Supervised the construction of government school building projects, managing timelines, material procurement, and on-site workforce.'],
          ];
          foreach ($exp as $e): ?>
            <div class="timeline-item" data-aos="fade-up">
              <div class="timeline-date"><?= $e['date'] ?></div>
              <div class="timeline-title"><?= $e['title'] ?></div>
              <div class="timeline-sub"><?= $e['sub'] ?></div>
              <p class="timeline-desc"><?= $e['desc'] ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
$schema = [
  '@context' => 'https://schema.org', '@type' => 'Person',
  'name'     => 'Pradeepgouda B Patil',
  'jobTitle' => 'Civil Engineer & Construction Specialist',
  'address'  => ['@type'=>'PostalAddress','addressLocality'=>'Gokak','addressRegion'=>'Karnataka','addressCountry'=>'IN'],
  'email'    => 'patilpradeep754@gmail.com',
  'url'      => 'http://localhost/about.php',
];
echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
include 'includes/footer.php';
?>
