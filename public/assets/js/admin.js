document.addEventListener('DOMContentLoaded', () => {

  // ---- Mobile sidebar toggle ----
  const sidebar = document.getElementById('adminSidebar');
  const sidebarToggle = document.getElementById('adminSidebarToggle');
  const sidebarClose = document.getElementById('adminSidebarClose');
  const sidebarBackdrop = document.getElementById('adminSidebarBackdrop');

  function openSidebar() {
    sidebar?.classList.add('open');
    sidebarBackdrop?.classList.add('open');
  }
  function closeSidebar() {
    sidebar?.classList.remove('open');
    sidebarBackdrop?.classList.remove('open');
  }
  sidebarToggle?.addEventListener('click', openSidebar);
  sidebarClose?.addEventListener('click', closeSidebar);
  sidebarBackdrop?.addEventListener('click', closeSidebar);

  // ---- Delete confirmation modal ----
  const modalOverlay = document.getElementById('deleteModal');
  const modalTitle = document.getElementById('deleteModalTitle');
  const modalConfirmBtn = document.getElementById('deleteModalConfirm');
  const modalCancelBtn = document.getElementById('deleteModalCancel');
  let pendingRow = null;
  let pendingRedirect = null;

  function openDeleteModal(bookTitle, rowEl, redirectUrl) {
    if (!modalOverlay) return;
    pendingRow = rowEl || null;
    pendingRedirect = redirectUrl || null;
    if (modalTitle) modalTitle.textContent = bookTitle ? `هل تريد حذف "${bookTitle}"؟` : 'هل تريد حذف هذا العنصر؟';
    modalOverlay.classList.add('open');
  }
  function closeDeleteModal() {
    modalOverlay?.classList.remove('open');
    pendingRow = null;
    pendingRedirect = null;
  }

  // Event delegation so dynamically-added rows/cards (e.g. new categories) work too
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-delete-trigger]');
    if (!btn) return;
    const row = btn.closest('tr') || btn.closest('.admin-category-card');
    const title = btn.dataset.bookTitle
      || row?.querySelector('.admin-book-cell strong')?.textContent.trim()
      || row?.querySelector('.admin-category-info strong')?.textContent.trim();
    openDeleteModal(title, row, btn.dataset.deleteRedirect);
  });

  modalCancelBtn?.addEventListener('click', closeDeleteModal);
  modalOverlay?.addEventListener('click', (e) => { if (e.target === modalOverlay) closeDeleteModal(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDeleteModal(); });

  modalConfirmBtn?.addEventListener('click', () => {
    if (pendingRedirect) {
      window.location.href = pendingRedirect;
      return;
    }
    if (pendingRow) {
      pendingRow.style.transition = 'opacity .2s ease';
      pendingRow.style.opacity = '0';
      setTimeout(() => pendingRow?.remove(), 200);
    }
    closeDeleteModal();
  });

  // ---- Books search/filter (client-side demo) ----
  const searchInput = document.getElementById('bookSearchInput');
  const categoryFilter = document.getElementById('bookCategoryFilter');
  const statusFilter = document.getElementById('bookStatusFilter');
  const tableRows = document.querySelectorAll('#booksTableBody tr');
  const emptyRow = document.getElementById('booksEmptyRow');

  function applyBookFilters() {
    if (!tableRows.length) return;
    const query = (searchInput?.value || '').trim().toLowerCase();
    const category = categoryFilter?.value || '';
    const status = statusFilter?.value || '';
    let visibleCount = 0;

    tableRows.forEach(row => {
      if (row === emptyRow) return;
      const title = row.dataset.title || '';
      const author = row.dataset.author || '';
      const rowCategory = row.dataset.category || '';
      const rowStatus = row.dataset.status || '';

      const matchesQuery = !query || title.includes(query) || author.includes(query);
      const matchesCategory = !category || rowCategory === category;
      const matchesStatus = !status || rowStatus === status;
      const visible = matchesQuery && matchesCategory && matchesStatus;

      row.hidden = !visible;
      if (visible) visibleCount++;
    });

    if (emptyRow) emptyRow.hidden = visibleCount !== 0;
  }

  searchInput?.addEventListener('input', applyBookFilters);
  categoryFilter?.addEventListener('change', applyBookFilters);
  statusFilter?.addEventListener('change', applyBookFilters);

  // ---- Writers search/filter (client-side demo) ----
  const writerSearchInput = document.getElementById('writerSearchInput');
  const writerStatusFilter = document.getElementById('writerStatusFilter');
  const writerRows = document.querySelectorAll('#writersTableBody tr');
  const writersEmptyRow = document.getElementById('writersEmptyRow');

  function applyWriterFilters() {
    if (!writerRows.length) return;
    const query = (writerSearchInput?.value || '').trim().toLowerCase();
    const status = writerStatusFilter?.value || '';
    let visibleCount = 0;

    writerRows.forEach(row => {
      if (row === writersEmptyRow) return;
      const name = row.dataset.name || '';
      const rowStatus = row.dataset.status || '';

      const matchesQuery = !query || name.includes(query);
      const matchesStatus = !status || rowStatus === status;
      const visible = matchesQuery && matchesStatus;

      row.hidden = !visible;
      if (visible) visibleCount++;
    });

    if (writersEmptyRow) writersEmptyRow.hidden = visibleCount !== 0;
  }

  writerSearchInput?.addEventListener('input', applyWriterFilters);
  writerStatusFilter?.addEventListener('change', applyWriterFilters);

  // ---- Categories search (client-side demo) ----
  const categorySearchInput = document.getElementById('categorySearchInput');
  const categoriesEmptyState = document.getElementById('categoriesEmptyState');

  function applyCategoryFilter() {
    const categoryRows = document.querySelectorAll('#categoriesGridBody [data-cat-name]');
    if (!categoryRows.length) return;
    const query = (categorySearchInput?.value || '').trim().toLowerCase();
    let visibleCount = 0;

    categoryRows.forEach(card => {
      const name = card.dataset.catName || '';
      const visible = !query || name.includes(query);
      card.hidden = !visible;
      if (visible) visibleCount++;
    });

    if (categoriesEmptyState) categoriesEmptyState.hidden = visibleCount !== 0;
  }

  categorySearchInput?.addEventListener('input', applyCategoryFilter);

});
