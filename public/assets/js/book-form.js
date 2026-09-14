document.addEventListener('DOMContentLoaded', () => {

  // ---- Cover image uploads + previews (large/medium/small, independent) ----
  function setupCoverUpload(uploadId, inputId, previewId) {
    const upload = document.getElementById(uploadId);
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    upload?.addEventListener('click', () => input?.click());
    input?.addEventListener('change', () => {
      const file = input.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = () => {
        preview.src = reader.result;
        preview.hidden = false;
        upload.classList.add('has-image');
      };
      reader.readAsDataURL(file);
    });
  }

  setupCoverUpload('coverUploadLg', 'coverInputLg', 'coverPreviewLg');
  setupCoverUpload('coverUploadMd', 'coverInputMd', 'coverPreviewMd');
  setupCoverUpload('coverUploadSm', 'coverInputSm', 'coverPreviewSm');

  // ---- Book file upload: echo the chosen filename ----
  const bookFileInput = document.getElementById('bookFile');
  const bookFileName = document.getElementById('bookFileName');

  bookFileInput?.addEventListener('change', () => {
    const file = bookFileInput.files?.[0];
    bookFileName.textContent = file ? file.name : '';
  });

  // ---- Form submit feedback (real submission — just disable the button) ----
  const form = document.getElementById('bookForm');
  form?.addEventListener('submit', () => {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (!submitBtn) return;
    submitBtn.disabled = true;
    submitBtn.dataset.originalLabel = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارِ الحفظ ...';
  });

});
