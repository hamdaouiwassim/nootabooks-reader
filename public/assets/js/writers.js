document.addEventListener('DOMContentLoaded', () => {

  const grid = document.getElementById('writersGrid');
  const cards = Array.from(document.querySelectorAll('.writer-card'));
  const searchInput = document.getElementById('writerSearch');
  const filterTabs = document.querySelectorAll('.filter-tab');
  const resultsCount = document.getElementById('resultsCount');
  const noResults = document.getElementById('noResults');

  let activeFilter = 'all';

  function applyFilters() {
    const query = (searchInput?.value || '').trim();
    let visibleCount = 0;

    cards.forEach(card => {
      const name = card.querySelector('h3').textContent;
      const tags = card.dataset.tags.split(' ');
      const matchesFilter = activeFilter === 'all' || tags.includes(activeFilter);
      const matchesSearch = !query || name.includes(query);
      const visible = matchesFilter && matchesSearch;
      card.hidden = !visible;
      if (visible) visibleCount++;
    });

    resultsCount.textContent = visibleCount;
    noResults.hidden = visibleCount !== 0;
    grid.hidden = visibleCount === 0;
  }

  searchInput?.addEventListener('input', applyFilters);

  filterTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      filterTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      activeFilter = tab.dataset.filter;
      applyFilters();
    });
  });

  // ---- Follow / unfollow buttons (grid + featured) ----
  // Follow state is now handled server-side (see WriterFollowController); this
  // just stops the click from bubbling up to the card's navigate-on-click handler.
  document.querySelectorAll('.follow-btn').forEach(btn => {
    btn.addEventListener('click', (e) => e.stopPropagation());
  });

  // ---- Card click navigates to writer profile ----
  cards.forEach(card => {
    const href = card.dataset.href;
    if (!href) return;
    card.style.cursor = 'pointer';
    card.addEventListener('click', () => { window.location.href = href; });
  });

});
