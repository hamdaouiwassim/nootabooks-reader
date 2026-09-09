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

  // ---- PDF upload filename display ----
  const pdfUpload = document.getElementById('pdfUpload');
  const pdfInput = document.getElementById('pdfInput');
  const pdfFileName = document.getElementById('pdfFileName');
  const pdfFileSize = document.getElementById('pdfFileSize');

  pdfUpload?.addEventListener('click', () => pdfInput?.click());
  pdfInput?.addEventListener('change', () => {
    const file = pdfInput.files?.[0];
    if (!file) return;
    pdfFileName.textContent = file.name;
    pdfFileSize.textContent = `${(file.size / 1024 / 1024).toFixed(1)} ميجابايت`;
  });

  // ---- Free / paid price toggle ----
  const paidToggle = document.getElementById('paidToggle');
  const priceField = document.getElementById('priceField');
  paidToggle?.addEventListener('change', () => {
    if (priceField) priceField.hidden = !paidToggle.checked;
  });

  // ---- Form submit feedback ----
  const form = document.getElementById('bookForm');
  const successMsg = document.getElementById('bookFormSuccess');

  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalLabel = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارِ الحفظ ...';

    setTimeout(() => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalLabel;
      successMsg?.classList.add('show');
      setTimeout(() => successMsg?.classList.remove('show'), 3500);
    }, 900);
  });

});
