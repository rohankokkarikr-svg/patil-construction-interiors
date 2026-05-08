'use strict';
/* ============================================================
   CALCULATOR.JS — Material Calculator (IS-code formulas)
   Live update on every input change, unit toggle, localStorage save
   ============================================================ */

let calcUnit = 'metric'; // 'metric' | 'imperial'
let activePanel = 'concrete';

// ── Unit helpers ──
const toM2  = (v) => calcUnit === 'imperial' ? v * 0.0929 : v;
const toM3  = (v) => calcUnit === 'imperial' ? v * 0.0283 : v;
const fmtM2 = () => calcUnit === 'imperial' ? 'sq ft' : 'm²';
const fmtM3 = () => calcUnit === 'imperial' ? 'cu ft' : 'm³';
const fmtKg = (v) => calcUnit === 'imperial' ? (v * 2.2046).toFixed(1) + ' lb' : v.toFixed(1) + ' kg';
const fmtVol= (m3) => calcUnit === 'imperial' ? (m3 * 35.3147).toFixed(2) + ' cu ft' : m3.toFixed(3) + ' m³';
const fmtL  = (l)  => l.toFixed(1) + ' L';

function setCalcUnit(u) {
  calcUnit = u;
  document.getElementById('btnMetric').classList.toggle('active', u === 'metric');
  document.getElementById('btnImperial').classList.toggle('active', u === 'imperial');
  document.querySelectorAll('.unit-label').forEach(el => {
    if (el.textContent.includes('m²') || el.textContent.includes('sq ft')) el.textContent = fmtM2();
    if (el.textContent.includes('m³') || el.textContent.includes('cu ft')) el.textContent = fmtM3();
  });
  calcAll();
}

function showCalc(name) {
  document.querySelectorAll('.calc-panel').forEach(p => p.style.display = 'none');
  document.querySelectorAll('#calcNav .nav-link').forEach(b => b.classList.remove('active'));
  document.getElementById('panel-' + name).style.display = '';
  document.getElementById('tab-' + name).classList.add('active');
  activePanel = name;
}

// ── CONCRETE MIX (IS 10262) ──
function calcConcrete() {
  let vol = parseFloat(document.getElementById('c_volume').value) || 0;
  if (calcUnit === 'imperial') vol = toM3(vol);

  const mixEl = document.getElementById('c_mix');
  const ratioStr = mixEl.selectedOptions[0].dataset.ratio; // e.g., "1:1.5:3"
  const parts = ratioStr.split(':').map(Number);
  const [c, fa, ca] = parts; // cement : fine agg : coarse agg
  const total = c + fa + ca;
  const dryVol = vol * 1.54; // dry volume factor

  const cementVol = (c / total) * dryVol;
  const sandVol   = (fa / total) * dryVol;
  const aggVol    = (ca / total) * dryVol;

  const cementKg   = cementVol * 1440;
  const cementBags = Math.ceil(cementKg / 50);
  const water      = cementKg * 0.45;

  setText('cr_bags',  cementBags + ' bags (' + Math.round(cementKg) + ' kg)');
  setText('cr_sand',  fmtVol(sandVol));
  setText('cr_agg',   fmtVol(aggVol));
  setText('cr_water', water.toFixed(0) + ' L');
  saveLS('concrete', { vol, ratio: ratioStr, bags: cementBags, sand: sandVol, agg: aggVol });
}

// ── BRICKWORK ──
function calcBrick() {
  let area = parseFloat(document.getElementById('b_area').value) || 0;
  if (calcUnit === 'imperial') area = toM2(area);
  const thick = parseFloat(document.getElementById('b_thick').value) || 0.2;
  const mortarRatio = document.getElementById('b_mortar').value; // "1:6"

  const volume = area * thick;
  // Standard brick 190x90x90mm + 10mm mortar = 200x100x100mm
  const brickVol = 0.2 * 0.1 * 0.1; // m³ per brick (with mortar)
  const bricks   = Math.ceil(volume / brickVol);

  const mortarVol = volume * 0.30; // 30% of brickwork is mortar
  const dryMortar = mortarVol * 1.30;
  const parts     = mortarRatio.split(':').map(Number);
  const sum       = parts[0] + parts[1];
  const cVol      = (parts[0] / sum) * dryMortar;
  const sVol      = (parts[1] / sum) * dryMortar;
  const cBags     = Math.ceil(cVol * 1440 / 50);

  setText('br_bricks', bricks.toLocaleString() + ' bricks');
  setText('br_cement', cBags + ' bags');
  setText('br_sand',   fmtVol(sVol));
  saveLS('brick', { area, thick, bricks, cBags, sVol });
}

// ── STEEL REBAR ──
function calcSteel() {
  let area = parseFloat(document.getElementById('s_area').value) || 0;
  if (calcUnit === 'imperial') area = toM2(area);
  const thickMm = parseFloat(document.getElementById('s_thick').value) || 150;
  const dia     = parseInt(document.getElementById('s_dia').value, 10) || 12;

  const thickM  = thickMm / 1000;
  const concreteVol = area * thickM;
  const steelPct    = 0.01; // 1% steel of concrete volume (typical for slabs)
  const steelVol    = concreteVol * steelPct;
  const steelKg     = steelVol * 7850;

  // Weight per metre of bar
  const wpm    = (dia * dia * 0.00616).toFixed(3); // kg/m
  const barLen = 6; // metres
  const bars   = Math.ceil(steelKg / (wpm * barLen));

  setText('st_weight', fmtKg(steelKg));
  setText('st_bars',   bars + ' bars (6 m, Ø' + dia + ' mm)');
  setText('st_pct',    '1.0% (standard for slabs)');
  saveLS('steel', { area, thickMm, dia, kg: steelKg, bars });
}

// ── PLASTERING ──
function calcPlaster() {
  let area = parseFloat(document.getElementById('p_area').value) || 0;
  if (calcUnit === 'imperial') area = toM2(area);
  const thickMm = parseInt(document.getElementById('p_thick').value, 10) || 12;
  const mixStr  = document.getElementById('p_mix').value; // "1:4"

  const vol     = area * (thickMm / 1000);
  const dryVol  = vol * 1.30 * 1.05; // 1.30 dry factor + 5% waste
  const parts   = mixStr.split(':').map(Number);
  const sum     = parts[0] + parts[1];
  const cVol    = (parts[0] / sum) * dryVol;
  const sVol    = (parts[1] / sum) * dryVol;
  const cBags   = Math.ceil(cVol * 1440 / 50);

  setText('pl_cement', cBags + ' bags');
  setText('pl_sand',   fmtVol(sVol));
  saveLS('plaster', { area, thickMm, cBags, sVol });
}

// ── TILING ──
function calcTile() {
  let area = parseFloat(document.getElementById('t_area').value) || 0;
  if (calcUnit === 'imperial') area = toM2(area);
  const tileSize = parseFloat(document.getElementById('t_size').value) || 0.25;
  const wastePct = parseInt(document.getElementById('t_waste').value, 10) || 10;

  const netTiles     = Math.ceil(area / tileSize);
  const orderTiles   = Math.ceil(netTiles * (1 + wastePct / 100));
  const adhesiveKg   = Math.ceil(area * 4); // ~4 kg/m²

  setText('ti_net',      netTiles.toLocaleString() + ' tiles');
  setText('ti_order',    orderTiles.toLocaleString() + ' tiles (+' + wastePct + '% waste)');
  setText('ti_adhesive', adhesiveKg + ' kg tile adhesive');
  saveLS('tile', { area, tileSize, netTiles, orderTiles });
}

// ── PAINT ──
function calcPaint() {
  let area = parseFloat(document.getElementById('pa_area').value) || 0;
  if (calcUnit === 'imperial') area = toM2(area);
  const coats    = parseInt(document.getElementById('pa_coats').value, 10) || 2;
  const coverage = parseInt(document.getElementById('pa_type').value, 10) || 12;

  const netL   = (area * coats) / coverage;
  const totalL = netL * 1.10;
  const cans4L = Math.ceil(totalL / 4);
  const cans1L = Math.ceil(totalL);

  setText('pa_net',   netL.toFixed(1) + ' L');
  setText('pa_total', totalL.toFixed(1) + ' L (with 10% waste)');
  setText('pa_break', cans4L + ' × 4L cans  OR  ' + cans1L + ' × 1L cans');
  saveLS('paint', { area, coats, coverage, totalL });
}

// ── Helper: set result text ──
function setText(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val;
}

// ── Calculate all panels on input change ──
function calcAll() {
  calcConcrete(); calcBrick(); calcSteel();
  calcPlaster(); calcTile(); calcPaint();
}

// ── LocalStorage save / restore ──
function saveLS(key, data) {
  try { localStorage.setItem('calc_' + key, JSON.stringify(data)); } catch(e) {}
}
function saveCalculation() {
  const btn = document.getElementById('saveCalcBtn');
  btn.innerHTML = '<i class="fas fa-check me-2"></i>Saved!';
  setTimeout(() => btn.innerHTML = '<i class="fas fa-save me-2"></i>Save Result', 2000);
}

// ── Wire up all inputs for live recalculation ──
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.calc-input, #c_mix, #b_thick, #b_mortar, #p_thick, #p_mix, #t_size, #t_waste, #pa_coats, #pa_type, #s_dia').forEach(el => {
    el.addEventListener('input', calcAll);
    el.addEventListener('change', calcAll);
  });
  calcAll(); // initial calculation
});
