document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('bookReportForm');
  if (!form) return;

  const name = document.getElementById('reportName');
  const email = document.getElementById('reportEmail');
  const message = document.getElementById('reportMessage');

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function setError(field, msg) {
    const errorEl = field.closest('.form-field')?.querySelector('.field-error');
    if (errorEl) errorEl.textContent = msg;
  }

  form.addEventListener('submit', (e) => {
    let valid = true;

    if (!name.value.trim()) {
      setError(name, 'يرجى إدخال الاسم الكامل');
      valid = false;
    } else {
      setError(name, '');
    }

    if (!email.value.trim()) {
      setError(email, 'يرجى إدخال البريد الإلكتروني');
      valid = false;
    } else if (!isValidEmail(email.value.trim())) {
      setError(email, 'صيغة البريد الإلكتروني غير صحيحة');
      valid = false;
    } else {
      setError(email, '');
    }

    if (!message.value.trim()) {
      setError(message, 'يرجى كتابة تفاصيل البلاغ');
      valid = false;
    } else {
      setError(message, '');
    }

    if (!valid) {
      e.preventDefault();
      return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارِ الإرسال ...';
  });

});
