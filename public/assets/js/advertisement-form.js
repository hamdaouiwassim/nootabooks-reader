document.addEventListener('DOMContentLoaded', () => {

  // ---- Creative file upload + preview (same pattern as book-form.js's cover upload) ----
  const upload = document.getElementById('creativeUpload');
  const input = document.getElementById('creativeInput');
  const preview = document.getElementById('creativePreview');

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

  // ---- Show heading/body fields only for the image_text ad type ----
  const typeSelect = document.getElementById('adType');
  const headingField = document.getElementById('adHeadingField');
  const bodyField = document.getElementById('adBodyField');

  function toggleTextFields() {
    const isImageText = typeSelect?.value === 'image_text';
    if (headingField) headingField.hidden = !isImageText;
    if (bodyField) bodyField.hidden = !isImageText;
  }

  typeSelect?.addEventListener('change', toggleTextFields);
  toggleTextFields();

});
