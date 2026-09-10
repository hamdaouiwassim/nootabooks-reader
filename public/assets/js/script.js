document.addEventListener('DOMContentLoaded', () => {

  // ---- Book carousels (trending, recent, ...) — a page can have more than one ----
  function initCarousel(wrap) {
    const track = wrap.querySelector('.book-carousel');
    const prevBtn = wrap.querySelector('.carousel-btn.prev');
    const nextBtn = wrap.querySelector('.carousel-btn.next');

    if (!track || !prevBtn || !nextBtn) return;

    // Read the actual CSS gap instead of hardcoding it, since it's 0 on
    // mobile but 20px on desktop — a fixed number would overshoot on mobile.
    const scrollAmount = () => {
      const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
      return track.querySelector('.book-card').offsetWidth + gap;
    };

    // RTL: "next" (left arrow) moves further into the list, "prev" (right arrow) moves back
    nextBtn.addEventListener('click', () => {
      track.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
    });
    prevBtn.addEventListener('click', () => {
      track.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
    });

    // ---- Autoplay ----
    const AUTOPLAY_INTERVAL = 4000;
    let autoplayTimer = null;

    function isAtEnd() {
      // RTL: scrollLeft goes negative as the track scrolls further into the list
      return Math.abs(track.scrollLeft) + track.clientWidth >= track.scrollWidth - 5;
    }

    function autoplayStep() {
      if (isAtEnd()) {
        track.scrollTo({ left: 0, behavior: 'smooth' });
      } else {
        track.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
      }
    }

    function startAutoplay() {
      stopAutoplay();
      autoplayTimer = setInterval(autoplayStep, AUTOPLAY_INTERVAL);
    }
    function stopAutoplay() {
      if (autoplayTimer) clearInterval(autoplayTimer);
    }

    startAutoplay();
    // Pause on hover/touch so users can browse without fighting the autoplay,
    // and restart the timer after a manual click so it doesn't immediately
    // advance again right after the user just navigated.
    track.addEventListener('mouseenter', stopAutoplay);
    track.addEventListener('mouseleave', startAutoplay);
    track.addEventListener('touchstart', stopAutoplay, { passive: true });
    track.addEventListener('touchend', startAutoplay);
    prevBtn.addEventListener('click', startAutoplay);
    nextBtn.addEventListener('click', startAutoplay);
  }

  document.querySelectorAll('.carousel-wrap').forEach(initCarousel);

  // ---- Newsletter form ----
  const form = document.getElementById('newsletterForm');
  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const input = form.querySelector('input[type="email"]');
      const btn = form.querySelector('button');
      const original = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> تم الاشتراك';
      input.value = '';
      setTimeout(() => { btn.innerHTML = original; }, 2200);
    });
  }

  // ---- Mobile nav toggle ----
  const mobileNavToggle = document.getElementById('mobileNavToggle');
  const mainNav = document.getElementById('mainNav');

  function closeMobileNav() {
    mainNav?.classList.remove('open');
    mobileNavToggle?.setAttribute('aria-expanded', 'false');
    mobileNavToggle?.querySelector('i')?.classList.replace('fa-xmark', 'fa-bars');
    document.body.classList.remove('nav-open');
  }

  mobileNavToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    closeAllDropdowns();
    const willOpen = !mainNav.classList.contains('open');
    mainNav.classList.toggle('open', willOpen);
    mobileNavToggle.setAttribute('aria-expanded', String(willOpen));
    mobileNavToggle.querySelector('i')?.classList.toggle('fa-bars', !willOpen);
    mobileNavToggle.querySelector('i')?.classList.toggle('fa-xmark', willOpen);
    document.body.classList.toggle('nav-open', willOpen);
  });

  mainNav?.addEventListener('click', (e) => e.stopPropagation());
  document.addEventListener('click', () => closeMobileNav());
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMobileNav(); });
  window.addEventListener('resize', () => { if (window.innerWidth > 1024) closeMobileNav(); });

  // ---- Header dropdowns: notifications + user menu ----
  const notifBtn = document.getElementById('notifBtn');
  const notifPanel = document.getElementById('notifPanel');
  const notifBadge = document.getElementById('notifBadge');
  const markAllReadBtn = document.getElementById('markAllReadBtn');

  const userMenuBtn = document.getElementById('userMenuBtn');
  const userPanel = document.getElementById('userPanel');

  function closeAllDropdowns(except) {
    [notifPanel, userPanel].forEach(panel => {
      if (panel && panel !== except) panel.classList.remove('open');
    });
  }

  function toggleDropdown(panel) {
    const willOpen = !panel.classList.contains('open');
    closeAllDropdowns();
    panel.classList.toggle('open', willOpen);
  }

  notifBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleDropdown(notifPanel);
  });

  userMenuBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleDropdown(userPanel);
  });
  userMenuBtn?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleDropdown(userPanel); }
  });

  document.addEventListener('click', () => closeAllDropdowns());
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAllDropdowns(); });

  [notifPanel, userPanel].forEach(panel => {
    panel?.addEventListener('click', (e) => e.stopPropagation());
  });

  markAllReadBtn?.addEventListener('click', () => {
    notifPanel.querySelectorAll('.notif-item.unread').forEach(item => item.classList.remove('unread'));
    if (notifBadge) notifBadge.style.display = 'none';
  });

});
