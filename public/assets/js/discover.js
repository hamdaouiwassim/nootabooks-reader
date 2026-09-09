document.addEventListener('DOMContentLoaded', () => {

  const grid = document.getElementById('discoverGrid');
  const cards = Array.from(document.querySelectorAll('#discoverGrid .book-card'));
  const searchInput = document.getElementById('discoverSearch');
  const sortSelect = document.getElementById('sortSelect');
  const resultsCount = document.getElementById('resultsCount');
  const noResults = document.getElementById('noResults');
  const clearBtn = document.getElementById('clearFilters');

  const categoryChecks = document.querySelectorAll('.filter-group input[type="checkbox"][value]');
  const ratingChecks = document.querySelectorAll('.rating-filter input[type="checkbox"]');

  function getCheckedCategories() {
    const categoryValues = ['روايات', 'أدب عربي', 'أدب عالمي', 'تنمية ذاتية', 'تاريخ'];
    return Array.from(categoryChecks)
      .filter(c => c.checked && categoryValues.includes(c.value))
      .map(c => c.value);
  }

  function getMinRating() {
    const checked = Array.from(ratingChecks).filter(c => c.checked);
    if (!checked.length) return 0;
    return Math.min(...checked.map(c => parseFloat(c.closest('.rating-filter').dataset.min)));
  }

  function applyFilters() {
    const query = (searchInput?.value || '').trim();
    const selectedCategories = getCheckedCategories();
    const minRating = getMinRating();
    let visibleCount = 0;

    cards.forEach(card => {
      const matchesSearch = !query ||
        card.dataset.title.includes(query) ||
        card.dataset.author.includes(query);
      const matchesCategory = !selectedCategories.length || selectedCategories.includes(card.dataset.category);
      const matchesRating = parseFloat(card.dataset.rating) >= minRating;
      const visible = matchesSearch && matchesCategory && matchesRating;
      card.hidden = !visible;
      if (visible) visibleCount++;
    });

    resultsCount.textContent = visibleCount;
    noResults.hidden = visibleCount !== 0;
    grid.hidden = visibleCount === 0;
  }

  function applySort() {
    const sort = sortSelect.value;
    let sorted = cards;
    if (sort === 'newest') {
      sorted = [...cards].sort((a, b) => b.dataset.year - a.dataset.year);
    } else if (sort === 'rating') {
      sorted = [...cards].sort((a, b) => b.dataset.rating - a.dataset.rating);
    } else if (sort === 'az') {
      sorted = [...cards].sort((a, b) => a.dataset.title.localeCompare(b.dataset.title, 'ar'));
    }
    sorted.forEach(card => grid.appendChild(card));
  }

  searchInput?.addEventListener('input', applyFilters);
  sortSelect?.addEventListener('change', applySort);
  categoryChecks.forEach(c => c.addEventListener('change', applyFilters));

  clearBtn?.addEventListener('click', () => {
    document.querySelectorAll('.filters-sidebar input[type="checkbox"]').forEach(c => c.checked = false);
    document.querySelector('.filters-sidebar input[value="all"]').checked = true;
    if (searchInput) searchInput.value = '';
    applyFilters();
  });

  // ---- Download buttons feedback ----
  document.querySelectorAll('#discoverGrid .btn-outline').forEach(btn => {
    btn.addEventListener('click', () => {
      const original = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> تم التحميل';
      setTimeout(() => { btn.innerHTML = original; }, 1800);
    });
  });

  // ---- Pagination (demo only) ----
  document.querySelectorAll('.page-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      if (!/^\d+$/.test(btn.textContent)) return;
      document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

});
