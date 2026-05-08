<?php
// ============================================================
// COST ESTIMATOR TOOL — tools/estimator.php
// ============================================================
require_once '../includes/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Project Cost Estimator | PATIL’s construction & interior’s — Civil Engineer';
$pageDesc  = 'Free construction cost estimator tool — get instant project cost estimates for residential, commercial, and infrastructure projects in India.';
$pageName  = 'estimator';
include '../includes/header.php';
?>

<div class="bg-grid"></div>

<section class="section" style="padding-top:8rem;">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Interactive Tool</span>
      <h1 class="section-title">Project Cost <span class="accent">Estimator</span></h1>
      <div class="section-divider"></div>
      <p class="mt-3" style="max-width:640px;margin:0 auto;">Get a quick ballpark estimate for your construction project. Fill in the details below and get an instant cost range with a full breakdown.</p>
    </div>

    <div class="row gy-5">
      <!-- INPUT PANEL -->
      <div class="col-lg-5" data-aos="fade-right">
        <div class="card-custom p-4">
          <h3 class="skill-group-title mb-4"><i class="fas fa-sliders-h me-2"></i>Project Details</h3>
          <div class="mb-3">
            <label class="form-label" style="color:var(--clr-text-muted);font-size:0.85rem;font-weight:600;">Project Type</label>
            <select id="projectType" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              <option value="residential">Residential House / Apartment</option>
              <option value="commercial">Commercial Office / Retail</option>
              <option value="industrial">Industrial / Warehouse</option>
              <option value="road">Road / Pavement</option>
              <option value="bridge">Bridge / Culvert</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color:var(--clr-text-muted);font-size:0.85rem;font-weight:600;">
              Total Built-up Area
              <span style="float:right;">
                <button class="unit-toggle-btn active" id="unitSqft" onclick="setUnit('sqft')">sq ft</button>
                <button class="unit-toggle-btn" id="unitSqm" onclick="setUnit('sqm')">sq m</button>
              </span>
            </label>
            <input type="number" id="area" class="form-control" value="1200" min="100" max="500000"
                   style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
          </div>
          <div class="mb-3">
            <label class="form-label" style="color:var(--clr-text-muted);font-size:0.85rem;font-weight:600;">Number of Floors</label>
            <select id="floors" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              <?php for($i=1;$i<=20;$i++) echo "<option value='$i'>$i Floor" . ($i>1?'s':'') . "</option>"; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color:var(--clr-text-muted);font-size:0.85rem;font-weight:600;">Construction Quality</label>
            <select id="quality" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              <option value="basic">Basic</option>
              <option value="standard" selected>Standard</option>
              <option value="premium">Premium</option>
              <option value="luxury">Luxury</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color:var(--clr-text-muted);font-size:0.85rem;font-weight:600;">Location / Region</label>
            <select id="region" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              <option value="metro">Metro City (Mumbai, Delhi, Bengaluru)</option>
              <option value="tier2">Tier-2 City (Pune, Hyderabad, Ahmedabad)</option>
              <option value="tier3">Tier-3 / Rural</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" style="color:var(--clr-text-muted);font-size:0.85rem;font-weight:600;">Structural System</label>
            <select id="structure" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              <option value="rcc">RCC Frame</option>
              <option value="loadbearing">Load Bearing</option>
              <option value="steel">Steel Frame</option>
              <option value="peb">Pre-engineered (PEB)</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label" style="color:var(--clr-text-muted);font-size:0.85rem;font-weight:600;">Special Requirements</label>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:0.5rem;">
              <?php
              $extras = [['id'=>'chkBasement','label'=>'Basement'],['id'=>'chkLift','label'=>'Passenger Lift'],['id'=>'chkPool','label'=>'Swimming Pool']];
              foreach ($extras as $e): ?>
                <label style="display:flex;align-items:center;gap:0.4rem;background:var(--clr-bg);border:1px solid var(--clr-border);border-radius:6px;padding:0.5rem 0.8rem;cursor:pointer;font-size:0.85rem;">
                  <input type="checkbox" id="<?= $e['id'] ?>" style="accent-color:var(--clr-accent);">
                  <?= $e['label'] ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="mb-3" style="display:flex;align-items:center;gap:0.5rem;">
            <label style="font-size:0.85rem;color:var(--clr-text-muted);font-weight:600;margin:0;">Currency</label>
            <button class="unit-toggle-btn active" id="currINR" onclick="setCurrency('INR')">₹ INR</button>
            <button class="unit-toggle-btn" id="currUSD" onclick="setCurrency('USD')">$ USD</button>
          </div>
          <button class="btn-accent w-100 justify-content-center mt-2" onclick="calculateCost()" id="calcBtn">
            <i class="fas fa-calculator me-2"></i>Calculate Estimate
          </button>
        </div>
      </div>

      <!-- OUTPUT PANEL -->
      <div class="col-lg-7" data-aos="fade-left">
        <div id="resultPanel" style="display:none;">
          <div class="estimate-output mb-4">
            <div style="font-size:0.75rem;color:var(--clr-accent);text-transform:uppercase;letter-spacing:2px;">Estimated Total Cost</div>
            <div class="estimate-range" id="costRange">₹0 – ₹0</div>
            <div style="font-size:0.82rem;color:var(--clr-text-muted);" id="costPerSqft"></div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-7">
              <div class="card-custom p-3">
                <h4 style="font-family:var(--font-sub);font-size:1rem;font-weight:700;margin-bottom:1rem;">Cost Breakdown</h4>
                <table class="estimate-breakdown w-100" id="breakdownTable">
                  <thead><tr>
                    <th style="font-size:0.75rem;text-transform:uppercase;color:var(--clr-accent);padding-bottom:0.5rem;">Component</th>
                    <th style="text-align:right;font-size:0.75rem;text-transform:uppercase;color:var(--clr-accent);padding-bottom:0.5rem;">Amount</th>
                    <th style="text-align:right;font-size:0.75rem;text-transform:uppercase;color:var(--clr-accent);padding-bottom:0.5rem;">%</th>
                  </tr></thead>
                  <tbody id="breakdownBody"></tbody>
                </table>
              </div>
            </div>
            <div class="col-md-5">
              <div class="card-custom p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                <canvas id="estimatorChart"></canvas>
              </div>
            </div>
          </div>

          <div class="card-custom p-3 mb-4" style="border-left:3px solid var(--clr-warning);">
            <p style="font-size:0.82rem;color:var(--clr-text-muted);margin:0;">
              <i class="fas fa-exclamation-triangle me-2" style="color:var(--clr-warning)"></i>
              <strong style="color:var(--clr-text)">Disclaimer:</strong> This is an approximate estimate only. Actual costs may vary based on soil conditions, design complexity, material prices and market conditions. Contact <span class="brand-patil" style="font-size:inherit;">PATIL’s</span> construction & interior’s for a detailed, site-specific quote.
            </p>
          </div>

          <div class="d-flex gap-3 flex-wrap">
            <a href="/contraction/contact.php?subject=Detailed+Quote+Request" class="btn-accent" id="requestQuoteBtn">
              <i class="fas fa-envelope me-2"></i>Request Detailed Quote
            </a>
            <button class="btn-outline-accent" onclick="downloadPDF()" id="downloadEstimateBtn">
              <i class="fas fa-file-pdf me-2"></i>Download PDF
            </button>
          </div>
        </div>

        <!-- Placeholder before calculation -->
        <div id="placeholderPanel" class="card-custom p-5 text-center" style="min-height:350px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
          <i class="fas fa-calculator" style="font-size:4rem;color:var(--clr-accent);opacity:0.3;margin-bottom:1.5rem;"></i>
          <h3 style="font-family:var(--font-sub);font-size:1.2rem;color:var(--clr-text-muted);">Fill in project details</h3>
          <p style="color:var(--clr-text-muted);font-size:0.88rem;">Configure the parameters on the left and click <strong style="color:var(--clr-accent)">Calculate Estimate</strong> to get your instant cost breakdown.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.unit-toggle-btn { background: var(--clr-bg); border: 1px solid var(--clr-border); color: var(--clr-text-muted); border-radius: 4px; padding: 0.2rem 0.6rem; font-size: 0.78rem; cursor: pointer; transition: all 0.2s; }
.unit-toggle-btn.active { background: var(--clr-accent); border-color: var(--clr-accent); color: #000; font-weight: 700; }
</style>

<script src="/contraction/assets/js/tools.js"></script>

<?php include '../includes/footer.php'; ?>
