document.addEventListener('DOMContentLoaded', () => {

  // ---- Follow button ----
  // Follow state is handled server-side (see WriterFollowController) via a
  // real form submission, so no client-side toggle is needed here anymore.

  // ---- Books sort tabs ----
  const grid = document.getElementById('writerBooksGrid');
  const tabs = document.querySelectorAll('.filter-tab');
  const cards = grid ? Array.from(grid.children) : [];

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      const sort = tab.dataset.sort;

      let sorted = cards;
      if (sort === 'popular') {
        sorted = [...cards].sort((a, b) => b.dataset.downloads - a.dataset.downloads);
      } else if (sort === 'newest') {
        sorted = [...cards].sort((a, b) => b.dataset.year - a.dataset.year);
      }

      sorted.forEach(card => grid.appendChild(card));
    });
  });

  // ---- Download buttons feedback ----
  document.querySelectorAll('.writer-books-grid .btn-outline').forEach(btn => {
    btn.addEventListener('click', () => {
      const original = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> تم التحميل';
      setTimeout(() => { btn.innerHTML = original; }, 1800);
    });
  });

});
