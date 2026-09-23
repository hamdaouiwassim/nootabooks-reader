document.addEventListener('DOMContentLoaded', () => {

  // ---- Creative file upload + preview (same pattern as book-form.js's cover upload) ----
  function setupUpload(uploadId, inputId, previewId) {
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

  setupUpload('creativeUpload', 'creativeInput', 'creativePreview');
  setupUpload('creativeTabletUpload', 'creativeTabletInput', 'creativeTabletPreview');
  setupUpload('creativeMobileUpload', 'creativeMobileInput', 'creativeMobilePreview');

  // ---- Show heading/body fields only for the image_text ad type; hide the
  //      per-device tablet/mobile uploads for animated_banner (one GIF file
  //      covers every device, no per-device variance) ----
  const typeSelect = document.getElementById('adType');
  const headingField = document.getElementById('adHeadingField');
  const bodyField = document.getElementById('adBodyField');
  const tabletField = document.getElementById('creativeTabletField');
  const mobileField = document.getElementById('creativeMobileField');

  function toggleTypeFields() {
    const type = typeSelect?.value;
    const isImageText = type === 'image_text';
    const isAnimated = type === 'animated_banner';
    if (headingField) headingField.hidden = !isImageText;
    if (bodyField) bodyField.hidden = !isImageText;
    if (tabletField) tabletField.hidden = isAnimated;
    if (mobileField) mobileField.hidden = isAnimated;
  }

  typeSelect?.addEventListener('change', toggleTypeFields);
  toggleTypeFields();

});
