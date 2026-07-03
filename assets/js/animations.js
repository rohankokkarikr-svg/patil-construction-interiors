/* ============================================================
   ANIMATIONS.JS — AOS init + IntersectionObserver skill bars
   ============================================================ */

'use strict';

// ── AOS Init ──
document.addEventListener('DOMContentLoaded', () => {
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      easing: 'ease-out-cubic',
      once: true,
      offset: 100,
      delay: 100,
      anchorPlacement: 'top-bottom'
    });
  }

  
  // ── Hero section staggered animations (desktop only) ──
  const heroElements = document.querySelectorAll('.hero-label, .hero-name, .hero-typewriter, .hero-cta p, .hero-cta');
  // Only animate on desktop (>768px)
  if (window.innerWidth > 768) {
    heroElements.forEach((el, index) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(30px)';
      el.style.transition = `opacity 0.6s ease-out ${index * 0.15}s, transform 0.6s ease-out ${index * 0.15}s`;
      
      setTimeout(() => {
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
      }, 300 + (index * 150));
    });
  }

  // ── Skill bar animations via IntersectionObserver ──
  const skillBars = document.querySelectorAll('.skill-bar[data-pct]');
  if (skillBars.length) {
    const barObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const bar   = entry.target;
          const pct   = bar.dataset.pct || '0';
          const label = bar.closest('.skill-item')?.querySelector('.skill-pct');
          bar.style.width = pct + '%';
          if (label) {
            let start = 0;
            const end = parseInt(pct, 10);
            const dur = 1200;
            const step = dur / end;
            const timer = setInterval(() => {
              start++;
              label.textContent = start + '%';
              if (start >= end) clearInterval(timer);
            }, step);
          }
          barObserver.unobserve(bar);
        }
      });
    }, { threshold: 0.3 });

    skillBars.forEach(bar => barObserver.observe(bar));
  }

  // ── Stat counters with animations (fallback for mobile) ──
  const counters = document.querySelectorAll('.stat-number[data-count]');
  if (counters.length) {
    const cntObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          const el       = entry.target;
          const target   = parseInt(el.dataset.count, 10);
          const suffix   = el.dataset.suffix || '';
          
          if (window.innerWidth <= 768) {
            // Immediate rendering on mobile to prevent missing data
            el.textContent = target + suffix;
            cntObserver.unobserve(el);
            return;
          }
          
          const card     = el.closest('.stat-card');
          const duration = 2000;
          
          // Animate the card first
          if (card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px) scale(0.95)';
            card.style.transition = `opacity 0.6s ease-out ${index * 0.1}s, transform 0.6s ease-out ${index * 0.1}s`;
            
            setTimeout(() => {
              card.style.opacity = '1';
              card.style.transform = 'translateY(0) scale(1)';
            }, index * 100);
          }
          
          // Then animate the counter
          setTimeout(() => {
            let current = 0;
            const timer = setInterval(() => {
              current = Math.min(current + Math.ceil(target / 60), target);
              el.textContent = current + suffix;
              if (current >= target) clearInterval(timer);
            }, duration / 60);
          }, 300 + (index * 100));
          
          cntObserver.unobserve(el);
        }
      });
    }, { threshold: 0.3 });
    counters.forEach(el => cntObserver.observe(el));
  }

  // ── Enhanced project card animations ──
  const projectCards = document.querySelectorAll('.project-card');
  if (projectCards.length) {
    const projectObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          const card = entry.target;
          card.style.opacity = '0';
          card.style.transform = 'translateY(40px) scale(0.95)';
          card.style.transition = `opacity 0.7s ease-out ${index * 0.15}s, transform 0.7s ease-out ${index * 0.15}s`;
          
          setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0) scale(1)';
          }, index * 150);
          
          projectObserver.unobserve(card);
        }
      });
    }, { threshold: 0.2 });
    projectCards.forEach(card => projectObserver.observe(card));
  }
});

