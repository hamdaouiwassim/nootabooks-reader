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

  // ---- Delete confirmation modal (submits the real form on confirm) ----
  const modalOverlay = document.getElementById('deleteModal');
  const modalTitle = document.getElementById('deleteModalTitle');
  const modalConfirmBtn = document.getElementById('deleteModalConfirm');
  const modalCancelBtn = document.getElementById('deleteModalCancel');
  let pendingForm = null;

  function openDeleteModal(itemTitle, form) {
    if (!modalOverlay) return;
    pendingForm = form;
    if (modalTitle) modalTitle.textContent = itemTitle ? `هل تريد حذف "${itemTitle}"؟` : 'هل تريد حذف هذا العنصر؟';
    modalOverlay.classList.add('open');
  }
  function closeDeleteModal() {
    modalOverlay?.classList.remove('open');
    pendingForm = null;
  }

  document.addEventListener('submit', (e) => {
    const form = e.target.closest('[data-confirm-delete]');
    if (!form) return;
    e.preventDefault();
    openDeleteModal(form.dataset.itemTitle, form);
  });

  modalCancelBtn?.addEventListener('click', closeDeleteModal);
  modalOverlay?.addEventListener('click', (e) => { if (e.target === modalOverlay) closeDeleteModal(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDeleteModal(); });

  modalConfirmBtn?.addEventListener('click', () => {
    pendingForm?.submit();
    closeDeleteModal();
  });

  // ---- Categories search (client-side, full list is rendered server-side) ----
  const categorySearchInput = document.getElementById('categorySearchInput');
  const categoriesEmptyState = document.getElementById('categoriesEmptyState');

  function applyCategoryFilter() {
    const categoryRows = document.querySelectorAll('#categoriesGridBody [data-cat-name]');
    if (!categoryRows.length) return;
    const query = (categorySearchInput?.value || '').trim().toLowerCase();
    let visibleCount = 0;

    categoryRows.forEach(card => {
      const name = (card.dataset.catName || '').toLowerCase();
      const visible = !query || name.includes(query);
      card.hidden = !visible;
      if (visible) visibleCount++;
    });

    if (categoriesEmptyState) categoriesEmptyState.hidden = visibleCount !== 0;
  }

  categorySearchInput?.addEventListener('input', applyCategoryFilter);

});
