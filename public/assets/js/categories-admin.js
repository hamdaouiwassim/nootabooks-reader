document.addEventListener('DOMContentLoaded', () => {

  const grid = document.getElementById('categoriesGridBody');
  const modal = document.getElementById('categoryModal');
  const modalTitle = document.getElementById('categoryModalTitle');
  const form = document.getElementById('categoryForm');
  const nameInput = document.getElementById('categoryName');
  const iconSelect = document.getElementById('categoryIcon');
  const colorSelect = document.getElementById('categoryColor');
  const preview = document.getElementById('categoryPreview');
  const previewIcon = preview?.querySelector('i');
  const addBtn = document.getElementById('addCategoryBtn');
  const cancelBtn = document.getElementById('categoryModalCancel');

  if (!modal || !form) return;

  let editingCard = null;
  const COLOR_CLASSES = ['cat-navy', 'cat-teal', 'cat-green', 'cat-rose', 'cat-purple', 'cat-brown', 'cat-gold'];

  function updatePreview() {
    if (!preview || !previewIcon) return;
    COLOR_CLASSES.forEach(c => preview.classList.remove(c));
    preview.classList.add(colorSelect.value);
    previewIcon.className = `fa-solid ${iconSelect.value}`;
  }

  function openModal(title) {
    modalTitle.textContent = title;
    modal.classList.add('open');
  }
  function closeModal() {
    modal.classList.remove('open');
    editingCard = null;
  }

  addBtn?.addEventListener('click', () => {
    editingCard = null;
    form.reset();
    updatePreview();
    openModal('إضافة تصنيف جديد');
    nameInput?.focus();
  });

  grid?.addEventListener('click', (e) => {
    const editBtn = e.target.closest('[data-cat-edit-trigger]');
    if (!editBtn) return;
    const card = editBtn.closest('.admin-category-card');
    if (!card) return;

    editingCard = card;
    nameInput.value = card.dataset.catName || '';
    const icon = card.querySelector('.cat-icon-circle i');
    const iconClass = [...icon.classList].find(c => c.startsWith('fa-') && c !== 'fa-solid');
    iconSelect.value = iconClass || 'fa-book';
    const colorClass = COLOR_CLASSES.find(c => card.querySelector('.cat-icon-circle').classList.contains(c));
    colorSelect.value = colorClass || 'cat-navy';
    updatePreview();
    openModal('تعديل التصنيف');
  });

  iconSelect?.addEventListener('change', updatePreview);
  colorSelect?.addEventListener('change', updatePreview);
  cancelBtn?.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal.classList.contains('open')) closeModal(); });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const name = nameInput.value.trim();
    if (!name) return;

    if (editingCard) {
      editingCard.dataset.catName = name;
      editingCard.querySelector('.admin-category-info strong').textContent = name;
      const circle = editingCard.querySelector('.cat-icon-circle');
      COLOR_CLASSES.forEach(c => circle.classList.remove(c));
      circle.classList.add(colorSelect.value);
      circle.querySelector('i').className = `fa-solid ${iconSelect.value}`;
    } else {
      const card = document.createElement('div');
      card.className = 'admin-category-card';
      card.dataset.catName = name;
      card.innerHTML = `
        <span class="cat-icon-circle ${colorSelect.value}"><i class="fa-solid ${iconSelect.value}"></i></span>
        <div class="admin-category-info">
          <strong>${name}</strong>
          <span>0 كتاب</span>
        </div>
        <div class="admin-category-actions">
          <button type="button" class="admin-icon-btn" data-cat-edit-trigger title="تعديل" aria-label="edit"><i class="fa-solid fa-pen"></i></button>
          <button type="button" class="admin-icon-btn danger" data-delete-trigger title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
        </div>
      `;
      grid.appendChild(card);
    }

    closeModal();
  });

});
