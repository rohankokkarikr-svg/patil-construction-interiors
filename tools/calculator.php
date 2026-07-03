<?php
// ============================================================
// MATERIAL CALCULATOR TOOL — tools/calculator.php
// ============================================================
require_once '../includes/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Material Calculator | PATIL’s construction & interior’s — Civil Engineer';
$pageDesc  = 'Free construction material calculator — concrete mix, brickwork, steel, plastering, tiling and paint quantity calculator using IS-code formulas.';
$pageName  = 'calculator';
include '../includes/header.php';
?>

<div class="bg-grid"></div>

<section class="section" style="padding-top:8rem;">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Interactive Tool</span>
      <h1 class="section-title">Material <span class="accent">Calculator</span></h1>
      <div class="section-divider"></div>
      <p class="mt-3" style="max-width:640px;margin:0 auto;">Calculate quantities of construction materials using IS-code formulas. Results update live as you type.</p>
    </div>

    <!-- Unit Toggle -->
    <div class="text-center mb-4" data-aos="fade-up">
      <span style="font-size:0.85rem;color:var(--clr-text-muted);margin-right:0.75rem;">Unit System:</span>
      <button class="unit-toggle-btn active" id="btnMetric"  onclick="setCalcUnit('metric')">Metric (m, kg)</button>
      <button class="unit-toggle-btn"        id="btnImperial" onclick="setCalcUnit('imperial')">Imperial (ft, lb)</button>
    </div>

    <div class="row gy-4" data-aos="fade-up">
      <div class="col-lg-3">
        <!-- Calculator Tabs -->
        <div class="card-custom p-3">
          <nav class="nav flex-column calc-tabs" id="calcNav">
            <button class="nav-link active" id="tab-concrete"   onclick="showCalc('concrete')"><i class="fas fa-industry me-2"></i>Concrete Mix</button>
            <button class="nav-link"        id="tab-brick"      onclick="showCalc('brick')"><i class="fas fa-th-large me-2"></i>Brickwork</button>
            <button class="nav-link"        id="tab-steel"      onclick="showCalc('steel')"><i class="fas fa-wrench me-2"></i>Steel Rebar</button>
            <button class="nav-link"        id="tab-plaster"    onclick="showCalc('plaster')"><i class="fas fa-paint-roller me-2"></i>Plastering</button>
            <button class="nav-link"        id="tab-tile"       onclick="showCalc('tile')"><i class="fas fa-border-all me-2"></i>Tile Flooring</button>
            <button class="nav-link"        id="tab-paint"      onclick="showCalc('paint')"><i class="fas fa-fill-drip me-2"></i>Paint Coverage</button>
          </nav>
        </div>
      </div>

      <div class="col-lg-9">
        <!-- CONCRETE MIX -->
        <div class="calc-panel" id="panel-concrete">
          <div class="card-custom p-4">
            <h3 class="tool-section-title"><i class="fas fa-industry me-2 accent"></i>Concrete Mix Calculator</h3>
            <p style="font-size:0.85rem;color:var(--clr-text-muted);margin-bottom:1.5rem;">Based on IS 10262. Dry volume factor = 1.54. Cement density = 1440 kg/m³.</p>
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label">Volume of Concrete (<span class="unit-label">m³</span>) *</label>
                <input type="number" id="c_volume" class="form-control calc-input" value="1" min="0.1" step="0.1"
                       style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              </div>
              <div class="col-sm-6">
                <label class="form-label">Mix Ratio</label>
                <select id="c_mix" class="form-select"
                        style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="m15" data-ratio="1:2:4">M15 (1:2:4)</option>
                  <option value="m20" selected data-ratio="1:1.5:3">M20 (1:1.5:3)</option>
                  <option value="m25" data-ratio="1:1:2">M25 (1:1:2)</option>
                  <option value="m30" data-ratio="1:0.75:1.5">M30 (1:0.75:1.5)</option>
                </select>
              </div>
            </div>
            <div class="calc-result" id="r_concrete">
              <div class="calc-result-item"><span>Cement Bags (50 kg each)</span><span class="calc-result-value" id="cr_bags">—</span></div>
              <div class="calc-result-item"><span>Sand (Fine Aggregate)</span><span class="calc-result-value" id="cr_sand">—</span></div>
              <div class="calc-result-item"><span>Coarse Aggregate</span><span class="calc-result-value" id="cr_agg">—</span></div>
              <div class="calc-result-item"><span>Water (approx. W/C = 0.45)</span><span class="calc-result-value" id="cr_water">—</span></div>
            </div>
          </div>
        </div>

        <!-- BRICKWORK -->
        <div class="calc-panel" id="panel-brick" style="display:none;">
          <div class="card-custom p-4">
            <h3 class="tool-section-title"><i class="fas fa-th-large me-2 accent"></i>Brickwork Calculator</h3>
            <p style="font-size:0.85rem;color:var(--clr-text-muted);margin-bottom:1.5rem;">Standard brick size: 190×90×90 mm with 10 mm mortar. Mortar volume = 30% of brickwork.</p>
            <div class="row g-3">
              <div class="col-sm-4">
                <label class="form-label">Wall Area (<span class="unit-label">m²</span>)</label>
                <input type="number" id="b_area" class="form-control calc-input" value="10" min="1"
                       style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              </div>
              <div class="col-sm-4">
                <label class="form-label">Wall Thickness</label>
                <select id="b_thick" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="0.1">Half Brick (100 mm)</option>
                  <option value="0.2" selected>Full Brick (200 mm)</option>
                  <option value="0.3">1.5 Brick (300 mm)</option>
                </select>
              </div>
              <div class="col-sm-4">
                <label class="form-label">Mortar Ratio</label>
                <select id="b_mortar" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="1:3">1:3 (Cement:Sand)</option>
                  <option value="1:4">1:4</option>
                  <option value="1:6" selected>1:6</option>
                </select>
              </div>
            </div>
            <div class="calc-result">
              <div class="calc-result-item"><span>Number of Bricks</span><span class="calc-result-value" id="br_bricks">—</span></div>
              <div class="calc-result-item"><span>Cement Bags (50 kg)</span><span class="calc-result-value" id="br_cement">—</span></div>
              <div class="calc-result-item"><span>Sand Volume</span><span class="calc-result-value" id="br_sand">—</span></div>
            </div>
          </div>
        </div>

        <!-- STEEL REBAR -->
        <div class="calc-panel" id="panel-steel" style="display:none;">
          <div class="card-custom p-4">
            <h3 class="tool-section-title"><i class="fas fa-wrench me-2 accent"></i>Steel Reinforcement Calculator</h3>
            <p style="font-size:0.85rem;color:var(--clr-text-muted);margin-bottom:1.5rem;">Slab reinforcement estimate. Steel density = 7850 kg/m³. Typical steel % of concrete volume for slabs.</p>
            <div class="row g-3">
              <div class="col-sm-4">
                <label class="form-label">Slab Area (<span class="unit-label">m²</span>)</label>
                <input type="number" id="s_area" class="form-control calc-input" value="50" min="1"
                       style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              </div>
              <div class="col-sm-4">
                <label class="form-label">Slab Thickness (mm)</label>
                <input type="number" id="s_thick" class="form-control calc-input" value="150" min="100" max="400" step="25"
                       style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              </div>
              <div class="col-sm-4">
                <label class="form-label">Bar Diameter (mm)</label>
                <select id="s_dia" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="8">8 mm</option>
                  <option value="10">10 mm</option>
                  <option value="12" selected>12 mm</option>
                  <option value="16">16 mm</option>
                </select>
              </div>
            </div>
            <div class="calc-result">
              <div class="calc-result-item"><span>Total Steel Weight</span><span class="calc-result-value" id="st_weight">—</span></div>
              <div class="calc-result-item"><span>Approx. Bar Count (6 m bars)</span><span class="calc-result-value" id="st_bars">—</span></div>
              <div class="calc-result-item"><span>Steel % of Concrete Volume</span><span class="calc-result-value" id="st_pct">1.0%</span></div>
            </div>
          </div>
        </div>

        <!-- PLASTERING -->
        <div class="calc-panel" id="panel-plaster" style="display:none;">
          <div class="card-custom p-4">
            <h3 class="tool-section-title"><i class="fas fa-paint-roller me-2 accent"></i>Plastering Calculator</h3>
            <p style="font-size:0.85rem;color:var(--clr-text-muted);margin-bottom:1.5rem;">Dry volume factor = 1.3. Wastage = 5%. Cement density = 1440 kg/m³.</p>
            <div class="row g-3">
              <div class="col-sm-4">
                <label class="form-label">Surface Area (<span class="unit-label">m²</span>)</label>
                <input type="number" id="p_area" class="form-control calc-input" value="20" min="1"
                       style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              </div>
              <div class="col-sm-4">
                <label class="form-label">Plaster Thickness (mm)</label>
                <select id="p_thick" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="6">6 mm (Internal)</option>
                  <option value="12" selected>12 mm (Standard)</option>
                  <option value="20">20 mm (External)</option>
                </select>
              </div>
              <div class="col-sm-4">
                <label class="form-label">Mix Ratio</label>
                <select id="p_mix" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="1:3">1:3</option>
                  <option value="1:4" selected>1:4</option>
                  <option value="1:6">1:6</option>
                </select>
              </div>
            </div>
            <div class="calc-result">
              <div class="calc-result-item"><span>Cement Bags (50 kg)</span><span class="calc-result-value" id="pl_cement">—</span></div>
              <div class="calc-result-item"><span>Sand Volume</span><span class="calc-result-value" id="pl_sand">—</span></div>
            </div>
          </div>
        </div>

        <!-- TILING -->
        <div class="calc-panel" id="panel-tile" style="display:none;">
          <div class="card-custom p-4">
            <h3 class="tool-section-title"><i class="fas fa-border-all me-2 accent"></i>Tile Flooring Calculator</h3>
            <p style="font-size:0.85rem;color:var(--clr-text-muted);margin-bottom:1.5rem;">Includes 10% waste allowance for cuts and breakage.</p>
            <div class="row g-3">
              <div class="col-sm-4">
                <label class="form-label">Room Area (<span class="unit-label">m²</span>)</label>
                <input type="number" id="t_area" class="form-control calc-input" value="20" min="1"
                       style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              </div>
              <div class="col-sm-4">
                <label class="form-label">Tile Size</label>
                <select id="t_size" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="0.09">300×300 mm</option>
                  <option value="0.16">400×400 mm</option>
                  <option value="0.25" selected>500×500 mm</option>
                  <option value="0.36">600×600 mm</option>
                  <option value="0.64">800×800 mm</option>
                  <option value="1.00">1000×1000 mm</option>
                </select>
              </div>
              <div class="col-sm-4">
                <label class="form-label">Wastage %</label>
                <select id="t_waste" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="5">5% (Simple layout)</option>
                  <option value="10" selected>10% (Standard)</option>
                  <option value="15">15% (Diagonal cut)</option>
                </select>
              </div>
            </div>
            <div class="calc-result">
              <div class="calc-result-item"><span>Net Tiles Required</span><span class="calc-result-value" id="ti_net">—</span></div>
              <div class="calc-result-item"><span>Tiles to Order (with waste)</span><span class="calc-result-value" id="ti_order">—</span></div>
              <div class="calc-result-item"><span>Tile Adhesive (approx.)</span><span class="calc-result-value" id="ti_adhesive">—</span></div>
            </div>
          </div>
        </div>

        <!-- PAINT -->
        <div class="calc-panel" id="panel-paint" style="display:none;">
          <div class="card-custom p-4">
            <h3 class="tool-section-title"><i class="fas fa-fill-drip me-2 accent"></i>Paint Coverage Calculator</h3>
            <p style="font-size:0.85rem;color:var(--clr-text-muted);margin-bottom:1.5rem;">Standard coverage: 12 sq m per litre per coat. Includes 10% waste.</p>
            <div class="row g-3">
              <div class="col-sm-4">
                <label class="form-label">Wall Area (<span class="unit-label">m²</span>)</label>
                <input type="number" id="pa_area" class="form-control calc-input" value="60" min="1"
                       style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
              </div>
              <div class="col-sm-4">
                <label class="form-label">Number of Coats</label>
                <select id="pa_coats" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="1">1 Coat</option>
                  <option value="2" selected>2 Coats</option>
                  <option value="3">3 Coats</option>
                </select>
              </div>
              <div class="col-sm-4">
                <label class="form-label">Paint Type</label>
                <select id="pa_type" class="form-select" style="background:var(--clr-bg);border:1px solid var(--clr-border);color:var(--clr-text);border-radius:8px;padding:0.7rem 1rem;">
                  <option value="12">Interior Emulsion (12 m²/L)</option>
                  <option value="10">Exterior Paint (10 m²/L)</option>
                  <option value="8">Texture / Putty (8 m²/L)</option>
                  <option value="15">Primer (15 m²/L)</option>
                </select>
              </div>
            </div>
            <div class="calc-result">
              <div class="calc-result-item"><span>Paint Quantity (net)</span><span class="calc-result-value" id="pa_net">—</span></div>
              <div class="calc-result-item"><span>Paint to Buy (with 10% waste)</span><span class="calc-result-value" id="pa_total">—</span></div>
              <div class="calc-result-item"><span>Estimated Litres Breakdown</span><span class="calc-result-value" id="pa_break">—</span></div>
            </div>
          </div>
        </div>

        <!-- Save / Print buttons -->
        <div class="d-flex gap-3 mt-3">
          <button class="btn-outline-accent" onclick="saveCalculation()" id="saveCalcBtn"><i class="fas fa-save me-2"></i>Save Result</button>
          <button class="btn-ghost" onclick="window.print()" id="printCalcBtn"><i class="fas fa-print me-2"></i>Print</button>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.unit-toggle-btn { background: var(--clr-bg); border: 1px solid var(--clr-border); color: var(--clr-text-muted); border-radius: 4px; padding: 0.3rem 0.8rem; font-size: 0.82rem; cursor: pointer; transition: all 0.2s; margin: 0 0.2rem; }
.unit-toggle-btn.active { background: var(--clr-accent); border-color: var(--clr-accent); color: #000; font-weight: 700; }
</style>

<script src="/assets/js/calculator.js"></script>
<?php include '../includes/footer.php'; ?>
