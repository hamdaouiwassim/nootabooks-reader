document.addEventListener('DOMContentLoaded', () => {

  // ---- Cover image upload + preview ----
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

  setupCoverUpload('coverUpload', 'coverInput', 'coverPreview');

  // ---- Searchable writer combobox (filters options as you type) ----
  function setupCombobox(wrapperId, inputId, hiddenId, listId) {
    const wrapper = document.getElementById(wrapperId);
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);
    const list = document.getElementById(listId);
    if (!wrapper || !input || !hidden || !list) return;

    const options = Array.from(list.querySelectorAll('.admin-combobox-option'));

    function filterOptions() {
      const term = input.value.trim().toLowerCase();
      options.forEach((opt) => {
        const name = (opt.dataset.name || '').toLowerCase();
        opt.hidden = term !== '' && !name.includes(term);
      });
    }

    function selectOption(opt) {
      input.value = opt.dataset.name || '';
      hidden.value = opt.dataset.id || '';
      list.hidden = true;
    }

    input.addEventListener('focus', () => {
      filterOptions();
      list.hidden = false;
    });
    input.addEventListener('input', () => {
      filterOptions();
      list.hidden = false;
      hidden.value = ''; // typing invalidates the previous selection until an option is picked
    });
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        list.hidden = true;
        input.blur();
      } else if (e.key === 'Enter') {
        e.preventDefault();
        const firstVisible = options.find((opt) => !opt.hidden);
        if (firstVisible) selectOption(firstVisible);
      }
    });

    options.forEach((opt) => {
      // mousedown (not click) fires before the input's blur handler below
      opt.addEventListener('mousedown', (e) => {
        e.preventDefault();
        selectOption(opt);
      });
    });

    document.addEventListener('click', (e) => {
      if (wrapper.contains(e.target)) return;
      list.hidden = true;
      if (!hidden.value) input.value = '';
    });
  }

  setupCombobox('writerCombobox', 'bookWriterSearch', 'bookWriterId', 'writerComboboxList');

  // ---- Series-based writer/category auto-fill ----
  // Every book in a series shares one writer and one primary category (see
  // Book::booted() for the server-side guarantee) — once an existing
  // series name is picked here, fill those two fields from it so the admin
  // doesn't have to re-select them for every book after the first.
  const seriesNameInput = document.getElementById('bookSeriesName');
  const seriesMap = seriesNameInput ? JSON.parse(seriesNameInput.dataset.seriesMap || '{}') : {};

  function applySeriesAutoFill() {
    const entry = seriesMap[seriesNameInput.value.trim()];
    if (!entry) return;

    const writerSearch = document.getElementById('bookWriterSearch');
    const writerId = document.getElementById('bookWriterId');
    const category = document.getElementById('bookCategory');

    if (writerSearch && writerId) {
      writerSearch.value = entry.writerName || '';
      writerId.value = entry.writerId || '';
    }
    if (category && entry.categoryId) {
      category.value = entry.categoryId;
    }
  }

  seriesNameInput?.addEventListener('change', applySeriesAutoFill);
  seriesNameInput?.addEventListener('blur', applySeriesAutoFill);

  // ---- Book file upload: echo the chosen filename, reject oversized files early ----
  const bookFileInput = document.getElementById('bookFile');
  const bookFileName = document.getElementById('bookFileName');
  const BOOK_FILE_MAX_BYTES = 100 * 1024 * 1024;

  bookFileInput?.addEventListener('change', () => {
    const file = bookFileInput.files?.[0];

    if (file && file.size > BOOK_FILE_MAX_BYTES) {
      bookFileName.textContent = `${file.name} — حجم الملف يتجاوز 100 ميجابايت`;
      bookFileName.classList.add('admin-field-error');
      bookFileInput.value = '';
      return;
    }

    bookFileName.classList.remove('admin-field-error');
    bookFileName.textContent = file ? file.name : '';
  });

  // ---- FAQ repeater ----
  const faqRows = document.getElementById('faqRows');
  const faqTemplate = document.getElementById('faqRowTemplate');
  const addFaqBtn = document.getElementById('addFaqRow');
  let faqNewRowIndex = 9000; // clear of any server-rendered row indices (0, 1, 2, ...)

  addFaqBtn?.addEventListener('click', () => {
    const html = faqTemplate.innerHTML.replaceAll('__INDEX__', String(faqNewRowIndex++));
    faqRows.insertAdjacentHTML('beforeend', html);
  });

  faqRows?.addEventListener('click', (e) => {
    const row = e.target.closest('[data-faq-row]');
    if (!row) return;

    if (e.target.closest('[data-faq-remove]')) {
      row.remove();
    } else if (e.target.closest('[data-faq-move-up]')) {
      const prev = row.previousElementSibling;
      if (prev) faqRows.insertBefore(row, prev);
    } else if (e.target.closest('[data-faq-move-down]')) {
      const next = row.nextElementSibling;
      if (next) faqRows.insertBefore(next, row);
    }
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
