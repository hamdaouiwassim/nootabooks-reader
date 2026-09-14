document.addEventListener('DOMContentLoaded', () => {

  const searchInput = document.getElementById('discoverSearch');
  const clearBtn = document.getElementById('clearFilters');

  // All filtering/sorting (category, language, rating, sort) now happens
  // server-side against the full dataset — search submits its form on
  // Enter rather than re-fetching on every keystroke.
  searchInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      searchInput.form?.submit();
    }
  });

  clearBtn?.addEventListener('click', () => {
    if (clearBtn.dataset.clearUrl) window.location.href = clearBtn.dataset.clearUrl;
  });

  // ---- Mobile filters accordion ----
  // Each filter change submits the form (full page reload), so the
  // sidebar naturally starts closed again once results are shown — no
  // state needs to be persisted across the reload.
  const filtersToggleBtn = document.getElementById('filtersToggleBtn');
  const filtersSidebar = document.getElementById('discoverFilters');

  filtersToggleBtn?.addEventListener('click', () => {
    const willOpen = !filtersSidebar.classList.contains('open');
    filtersSidebar.classList.toggle('open', willOpen);
    filtersToggleBtn.setAttribute('aria-expanded', String(willOpen));
  });

});
