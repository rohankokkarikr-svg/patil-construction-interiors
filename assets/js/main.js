'use strict';
/* ============================================================
   MAIN.JS — Nav, hamburger, typewriter, modals, search
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  // ── Nav scroll effect ──
  const nav = document.getElementById('mainNav');
  const onScroll = () => {
    if (window.scrollY > 60) nav?.classList.add('scrolled');
    else nav?.classList.remove('scrolled');

    const scrollBtn = document.getElementById('scrollTop');
    if (scrollBtn) {
      if (window.scrollY > 400) scrollBtn.classList.add('show');
      else scrollBtn.classList.remove('show');
    }
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // ── Scroll to top ──
  document.getElementById('scrollTop')?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // ── Hamburger / Mobile Nav ──
  const toggler  = document.getElementById('navToggler');
  const menu     = document.getElementById('navMenu');
  const overlay  = document.getElementById('navOverlay');

  const openNav = () => {
    menu?.classList.add('open');
    overlay?.classList.add('show');
    toggler?.classList.add('open');
    toggler?.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  };
  const closeNav = () => {
    menu?.classList.remove('open');
    overlay?.classList.remove('show');
    toggler?.classList.remove('open');
    toggler?.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  };

  toggler?.addEventListener('click', () => {
    menu?.classList.contains('open') ? closeNav() : openNav();
  });
  overlay?.addEventListener('click', closeNav);

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNav(); });

  // ── Typewriter Effect ──
  const el = document.getElementById('typewriterText');
  if (el) {
    const phrases = ['Civil Engineer', 'Structural Designer', 'Site Manager', 'BIM Specialist'];
    let phraseIdx = 0, charIdx = 0, deleting = false;

    const type = () => {
      const current = phrases[phraseIdx];
      if (deleting) {
        el.textContent = current.slice(0, --charIdx);
      } else {
        el.textContent = current.slice(0, ++charIdx);
      }

      let timeout = deleting ? 60 : 100;
      if (!deleting && charIdx === current.length) {
        timeout = 2000; deleting = true;
      } else if (deleting && charIdx === 0) {
        deleting = false; phraseIdx = (phraseIdx + 1) % phrases.length; timeout = 400;
      }
      setTimeout(type, timeout);
    };
    setTimeout(type, 800);
  }

  // ── Project Filter Tabs ──
  const filterBtns = document.querySelectorAll('.filter-btn[data-filter]');
  const projectCards = document.querySelectorAll('.project-card[data-category]');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const filter = btn.dataset.filter;
      projectCards.forEach(card => {
        const show = filter === 'all' || card.dataset.category === filter;
        card.closest('.project-col')?.style.setProperty('display', show ? '' : 'none');
      });
      checkNoResults();
    });
  });

  // ── Search ──
  const searchInput = document.getElementById('projectSearch');
  if (searchInput) {
    searchInput.addEventListener('input', () => {
      const q = searchInput.value.toLowerCase().trim();
      projectCards.forEach(card => {
        const text = (card.dataset.search || '').toLowerCase();
        const col  = card.closest('.project-col');
        if (col) col.style.display = (!q || text.includes(q)) ? '' : 'none';
      });
      checkNoResults();
    });
  }

  function checkNoResults() {
    const grid    = document.getElementById('projectsGrid');
    const noRes   = document.getElementById('noResults');
    if (!grid || !noRes) return;
    const visible = [...grid.querySelectorAll('.project-col')].filter(c => c.style.display !== 'none');
    noRes.style.display = visible.length === 0 ? '' : 'none';
  }

  // ── Project Modal ──
  const projectModal = document.getElementById('projectModal');
  if (projectModal) {
    document.querySelectorAll('.view-details-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const card = btn.closest('.project-card');
        document.getElementById('modalTitle').textContent     = card.dataset.title || '';
        document.getElementById('modalCategory').textContent  = card.dataset.category || '';
        document.getElementById('modalDesc').textContent      = card.dataset.desc || '';
        document.getElementById('modalDuration').textContent  = card.dataset.duration || 'N/A';
        document.getElementById('modalRole').textContent      = card.dataset.role || 'N/A';
        document.getElementById('modalClient').textContent    = card.dataset.client || 'N/A';
        document.getElementById('modalLocation').textContent  = card.dataset.location || 'N/A';
        const toolsWrap = document.getElementById('modalTools');
        if (toolsWrap) toolsWrap.innerHTML = card.dataset.tools?.split(',').map(t => `<span class="tool-badge">${t.trim()}</span>`).join('') || '';
        const img = document.getElementById('modalImg');
        if (img) img.src = card.dataset.img || '';
      });
    });
  }

  // ── Cert Lightbox ──
  document.querySelectorAll('.cert-view-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const src   = btn.dataset.img;
      const title = btn.dataset.title;
      const lb    = document.getElementById('certLightbox');
      if (!lb || !src) return;
      lb.querySelector('#lbImg').src        = src;
      lb.querySelector('#lbTitle').textContent = title;
    });
  });

  // ── Contact Form AJAX ──
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn     = contactForm.querySelector('[type=submit]');
      const msgEl   = document.getElementById('formMessage');
      btn.disabled  = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending…';
      try {
        const resp = await fetch('/contraction/includes/contact_handler.php', {
          method: 'POST', body: new FormData(contactForm),
        });
        const data = await resp.json();
        msgEl.className = 'alert-custom ' + (data.success ? 'alert-success' : 'alert-error');
        msgEl.innerHTML = `<i class="fas fa-${data.success ? 'check-circle' : 'exclamation-circle'}"></i> ${data.message}`;
        msgEl.style.display = 'flex';
        if (data.success) contactForm.reset();
      } catch {
        msgEl.className = 'alert-custom alert-error';
        msgEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Network error. Please try again.';
        msgEl.style.display = 'flex';
      }
      btn.disabled  = false;
      btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Message';
    });
  }

  // ── Cert Filter ──
  const certBtns  = document.querySelectorAll('.cert-filter-btn[data-cat]');
  const certCards  = document.querySelectorAll('.cert-col[data-cat]');
  certBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      certBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const cat = btn.dataset.cat;
      certCards.forEach(card => {
        card.style.display = (cat === 'all' || card.dataset.cat === cat) ? '' : 'none';
      });
    });
  });

  // ── Append scroll-to-top button ──
  if (!document.getElementById('scrollTop')) {
    const st = document.createElement('button');
    st.id = 'scrollTop'; st.setAttribute('aria-label', 'Scroll to top');
    st.innerHTML = '<i class="fas fa-chevron-up"></i>';
    document.body.appendChild(st);
    st.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }
});
