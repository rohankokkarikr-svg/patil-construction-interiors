<?php
// ============================================================
// Shared Header — includes <head> + nav
// Usage: include at top of every page, pass $pageTitle, $pageDesc
// ============================================================
$pageTitle = $pageTitle ?? 'PATIL’s construction & interior’s | Civil & Structural Engineer';
$pageDesc  = $pageDesc  ?? 'Professional portfolio of PATIL’s construction & interior’s — Civil & Structural Engineer specializing in structural design, BIM, and infrastructure projects.';
$pageName  = $pageName  ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
  <meta name="author" content="PATIL’s construction & interior’s">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://alexcarter.engineer<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDesc) ?>">
  <meta property="og:image" content="/contraction/assets/images/hero/hero-bg.jpg">
  <meta property="og:url" content="https://alexcarter.engineer<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($pageDesc) ?>">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&family=Oswald:wght@400;500;600;700&family=Bebas+Neue&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- AOS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="/contraction/assets/css/style.css">
  <link rel="stylesheet" href="/contraction/assets/css/dark-theme.css">
  <link rel="stylesheet" href="/contraction/assets/css/responsive.css">
</head>
<body>

<!-- Skip Navigation -->
<a href="#main-content" class="skip-nav">Skip to main content</a>

<!-- ===== NAVIGATION ===== -->
<nav id="mainNav" class="navbar navbar-expand-lg fixed-top" aria-label="Main navigation">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="/contraction/index.php" aria-label="PATIL’s construction & interior’s Home">
      <img src="/contraction/assets/images/hero/logo.png" alt="PATIL’s construction & interior’s Logo" style="height: 45px; width: auto; object-fit: contain; margin-right: 12px;">
      <span class="logo-text"><span class="brand-patil">PATIL's</span> <span class="accent">CONSTRUCTION & INTERIOR'S</span></span>
    </a>

    <!-- Hamburger -->
    <button class="navbar-toggler custom-toggler" type="button" id="navToggler"
            aria-expanded="false" aria-controls="navMenu" aria-label="Toggle navigation">
      <span class="toggler-bar"></span>
      <span class="toggler-bar"></span>
      <span class="toggler-bar"></span>
    </button>

    <!-- Links -->
    <div class="navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link <?= $pageName==='home' ? 'active' : '' ?>" href="/contraction/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= $pageName==='about' ? 'active' : '' ?>" href="/contraction/about.php">About</a></li>
        <li class="nav-item"><a class="nav-link <?= $pageName==='projects' ? 'active' : '' ?>" href="/contraction/projects.php">Projects</a></li>
        <li class="nav-item"><a class="nav-link <?= $pageName==='skills' ? 'active' : '' ?>" href="/contraction/skills.php">Skills</a></li>
        <li class="nav-item"><a class="nav-link <?= $pageName==='certifications' ? 'active' : '' ?>" href="/contraction/certifications.php">Certifications</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= in_array($pageName,['estimator','calculator']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Tools</a>
          <ul class="dropdown-menu nav-dropdown">
            <li><a class="dropdown-item" href="/contraction/tools/estimator.php"><i class="fas fa-calculator me-2 accent"></i>Cost Estimator</a></li>
            <li><a class="dropdown-item" href="/contraction/tools/calculator.php"><i class="fas fa-ruler-combined me-2 accent"></i>Material Calculator</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link <?= $pageName==='contact' ? 'active' : '' ?>" href="/contraction/contact.php">Contact</a></li>
        <li class="nav-item ms-lg-2">
          <a class="btn btn-cv" href="/contraction/resume/download.php" id="navDownloadCV">
            <i class="fas fa-download me-1"></i>Download CV
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Mobile Overlay -->
<div class="nav-overlay" id="navOverlay"></div>

<!-- Main Content -->
<main id="main-content">
