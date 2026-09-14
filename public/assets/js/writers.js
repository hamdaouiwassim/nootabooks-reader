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
      filterTabs.forEach(t => {
        t.classList.remove('active');
        t.setAttribute('aria-pressed', 'false');
      });
      tab.classList.add('active');
      tab.setAttribute('aria-pressed', 'true');
      activeFilter = tab.dataset.filter;
      applyFilters();
    });
  });

});
