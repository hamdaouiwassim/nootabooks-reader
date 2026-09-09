document.addEventListener('DOMContentLoaded', () => {

  // ---- Cover image upload + preview ----
  const coverUpload = document.getElementById('coverUpload');
  const coverInput = document.getElementById('coverInput');
  const coverPreview = document.getElementById('coverPreview');

  coverUpload?.addEventListener('click', () => coverInput?.click());
  coverInput?.addEventListener('change', () => {
    const file = coverInput.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => {
      coverPreview.src = reader.result;
      coverPreview.hidden = false;
      coverUpload.classList.add('has-image');
    };
    reader.readAsDataURL(file);
  });

  // ---- Book file upload: echo the chosen filename ----
  const bookFileInput = document.getElementById('bookFile');
  const bookFileName = document.getElementById('bookFileName');

  bookFileInput?.addEventListener('change', () => {
    const file = bookFileInput.files?.[0];
    bookFileName.textContent = file ? file.name : '';
  });

  // ---- Form submit feedback (real submission — just disable the button) ----
  const form = document.getElementById('bookForm') || coverUpload?.closest('form');
  form?.addEventListener('submit', () => {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (!submitBtn) return;
    submitBtn.disabled = true;
    submitBtn.dataset.originalLabel = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارِ الحفظ ...';
  });

});
