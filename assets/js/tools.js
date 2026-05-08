'use strict';
/* ============================================================
   TOOLS.JS — Project Cost Estimator Logic
   ============================================================ */

// ── Cost rates (INR per sq ft) by [type][quality]
const BASE_RATES = {
  residential: { basic: 1400, standard: 1900, premium: 2800, luxury: 4500 },
  commercial:  { basic: 1600, standard: 2200, premium: 3200, luxury: 5200 },
  industrial:  { basic: 900,  standard: 1300, premium: 1800, luxury: 2500 },
  road:        { basic: 800,  standard: 1100, premium: 1500, luxury: 2000 },
  bridge:      { basic: 3500, standard: 5000, premium: 7000, luxury: 10000 },
};

// ── Cost components (%) of total
const COMPONENT_PCT = {
  residential: { 'Civil Work': 45, 'Finishing & Interior': 25, 'MEP Services': 15, 'Furniture & Fixtures': 10, 'Miscellaneous': 5 },
  commercial:  { 'Civil Work': 40, 'Finishing & Interior': 20, 'MEP Services': 22, 'Furniture & Fixtures': 12, 'Miscellaneous': 6 },
  industrial:  { 'Civil Work': 55, 'Finishing & Interior': 10, 'MEP Services': 20, 'Equipment & Utilities': 10, 'Miscellaneous': 5 },
  road:        { 'Earthwork & Base': 35, 'Surface Course': 35, 'Drainage Works': 15, 'Structures': 10, 'Miscellaneous': 5 },
  bridge:      { 'Foundation Work': 30, 'Substructure': 25, 'Superstructure': 30, 'Protective Works': 10, 'Miscellaneous': 5 },
};

// ── Region multipliers
const REGION_MUL    = { metro: 1.25, tier2: 1.0, tier3: 0.80 };
// ── Structural system add-on
const STRUCTURE_ADD = { rcc: 0, loadbearing: -0.05, steel: 0.08, peb: 0.12 };
// ── Floor multiplier (each floor adds a little)
const floorMul      = (f) => 1 + (f - 1) * 0.03;
// ── Extras add-ons (INR)
const EXTRAS        = { basement: 500000, lift: 1200000, pool: 2500000 };
// ── USD exchange rate
const USD_RATE      = 83.5;

let currentUnit     = 'sqft';
let currentCurrency = 'INR';
let pieChart        = null;
let lastResult      = null;

function setUnit(u) {
  const areaInput = document.getElementById('area');
  const val = parseFloat(areaInput.value) || 0;
  if (u === 'sqm' && currentUnit === 'sqft') areaInput.value = Math.round(val / 10.764);
  if (u === 'sqft' && currentUnit === 'sqm') areaInput.value = Math.round(val * 10.764);
  currentUnit = u;
  document.getElementById('unitSqft').classList.toggle('active', u === 'sqft');
  document.getElementById('unitSqm').classList.toggle('active', u === 'sqm');
}

function setCurrency(c) {
  currentCurrency = c;
  document.getElementById('currINR').classList.toggle('active', c === 'INR');
  document.getElementById('currUSD').classList.toggle('active', c === 'USD');
  if (lastResult) renderResult(lastResult);
}

function formatMoney(inr) {
  if (currentCurrency === 'USD') {
    const usd = inr / USD_RATE;
    return '$' + new Intl.NumberFormat('en-US', {maximumFractionDigits:0}).format(usd);
  }
  if (inr >= 10000000) return '₹' + (inr / 10000000).toFixed(2) + ' Cr';
  if (inr >= 100000)   return '₹' + (inr / 100000).toFixed(2) + ' L';
  return '₹' + new Intl.NumberFormat('en-IN').format(Math.round(inr));
}

function calculateCost() {
  const type     = document.getElementById('projectType').value;
  let   areaSqft = parseFloat(document.getElementById('area').value) || 0;
  if (currentUnit === 'sqm') areaSqft = areaSqft * 10.764;
  const floors   = parseInt(document.getElementById('floors').value, 10) || 1;
  const quality  = document.getElementById('quality').value;
  const region   = document.getElementById('region').value;
  const struct   = document.getElementById('structure').value;

  const basement = document.getElementById('chkBasement')?.checked ? EXTRAS.basement : 0;
  const lift     = document.getElementById('chkLift')?.checked ? (EXTRAS.lift * floors) : 0;
  const pool     = document.getElementById('chkPool')?.checked ? EXTRAS.pool : 0;

  const baseRate = BASE_RATES[type][quality];
  const rate     = baseRate * REGION_MUL[region] * (1 + STRUCTURE_ADD[struct]) * floorMul(floors);
  const totalArea= areaSqft * floors;
  const baseCost = totalArea * rate;
  const extraCost= basement + lift + pool;
  const total    = baseCost + extraCost;
  const minTotal = Math.round(total * 0.9);
  const maxTotal = Math.round(total * 1.1);

  const comps = COMPONENT_PCT[type] || COMPONENT_PCT.residential;
  const breakdown = Object.entries(comps).map(([name, pct]) => ({
    name, pct, amount: Math.round(total * pct / 100)
  }));

  lastResult = { minTotal, maxTotal, total, breakdown, totalArea, rate };
  renderResult(lastResult);
}

function renderResult({ minTotal, maxTotal, total, breakdown, totalArea, rate }) {
  document.getElementById('placeholderPanel').style.display = 'none';
  document.getElementById('resultPanel').style.display      = 'block';

  document.getElementById('costRange').textContent = `${formatMoney(minTotal)} – ${formatMoney(maxTotal)}`;
  document.getElementById('costPerSqft').textContent =
    `Based on ${Math.round(totalArea).toLocaleString()} sq ft @ ${formatMoney(rate)} per sq ft`;

  // Breakdown table
  const tbody = document.getElementById('breakdownBody');
  tbody.innerHTML = '';
  breakdown.forEach(({ name, pct, amount }) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${name}</td><td style="text-align:right;font-weight:600;color:var(--clr-text);">${formatMoney(amount)}</td><td style="text-align:right;color:var(--clr-accent);">${pct}%</td>`;
    tbody.appendChild(tr);
  });
  const totalRow = document.createElement('tr');
  totalRow.innerHTML = `<td style="font-weight:700;color:var(--clr-text);border-top:2px solid var(--clr-accent);padding-top:0.6rem;">TOTAL</td><td style="text-align:right;font-weight:700;color:var(--clr-accent);border-top:2px solid var(--clr-accent);padding-top:0.6rem;">${formatMoney(total)}</td><td style="text-align:right;border-top:2px solid var(--clr-accent);padding-top:0.6rem;">100%</td>`;
  tbody.appendChild(totalRow);

  // Pie chart
  const ctx = document.getElementById('estimatorChart').getContext('2d');
  if (pieChart) pieChart.destroy();
  pieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: breakdown.map(b => b.name),
      datasets: [{ data: breakdown.map(b => b.amount), backgroundColor: ['#F5A623','#E8892B','#FF6B35','#C0392B','#8E44AD'], borderWidth: 0, hoverOffset: 6 }]
    },
    options: {
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${formatMoney(ctx.raw)}` } } },
      cutout: '65%',
    }
  });
}

function downloadPDF() {
  if (!lastResult || typeof jspdf === 'undefined') return;
  const { jsPDF } = jspdf;
  const doc = new jsPDF();

  doc.setFillColor(26, 26, 46);
  doc.rect(0, 0, 210, 297, 'F');
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(22); doc.setTextColor(245, 166, 35);
  doc.text('Construction Cost Estimate', 20, 30);
  doc.setFontSize(11); doc.setTextColor(170, 170, 170);
  doc.text('PATIL’s construction & interior’s | Civil & Structural Engineer', 20, 40);
  doc.text('Generated: ' + new Date().toLocaleDateString('en-IN'), 20, 48);

  doc.setTextColor(255,255,255); doc.setFontSize(14); doc.setFont('helvetica','bold');
  doc.text('Estimated Total Cost', 20, 65);
  doc.setFontSize(18); doc.setTextColor(245,166,35);
  doc.text(`${formatMoney(lastResult.minTotal)} – ${formatMoney(lastResult.maxTotal)}`, 20, 75);

  doc.setFontSize(11); doc.setTextColor(170,170,170); doc.setFont('helvetica','normal');
  doc.text('Cost Breakdown:', 20, 95);
  let y = 105;
  lastResult.breakdown.forEach(({ name, amount, pct }) => {
    doc.setTextColor(255,255,255); doc.text(name, 20, y);
    doc.setTextColor(245,166,35);  doc.text(formatMoney(amount), 130, y, { align: 'right' });
    doc.setTextColor(170,170,170); doc.text(pct + '%', 170, y, { align: 'right' });
    y += 10;
  });
  y += 5;
  doc.setDrawColor(245,166,35); doc.line(20, y, 190, y);
  y += 8;
  doc.setTextColor(255,255,255); doc.setFont('helvetica','bold'); doc.text('TOTAL', 20, y);
  doc.setTextColor(245,166,35); doc.text(formatMoney(lastResult.total), 130, y, { align: 'right' });
  y += 20;
  doc.setFontSize(9); doc.setTextColor(100,100,100); doc.setFont('helvetica','normal');
  doc.text('DISCLAIMER: This is an approximate estimate only. Actual costs may vary.', 20, y);
  doc.text('Contact PATIL’s construction & interior’s for a detailed, site-specific quotation.', 20, y + 6);
  doc.text('Patilpradeep754@gmail.com | +91 8747061867 | Gokak, Karnataka, India', 20, y + 12);

  doc.save('Alex-Carter-Cost-Estimate.pdf');
}
