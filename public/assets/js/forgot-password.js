document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('forgotPasswordForm');
  const email = document.getElementById('forgotPasswordEmail');
  const emailError = document.getElementById('forgotPasswordEmailError');

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  form.addEventListener('submit', (e) => {
    let valid = true;

    if (!email.value.trim()) {
      emailError.textContent = 'يرجى إدخال البريد الإلكتروني';
      valid = false;
    } else if (!isValidEmail(email.value.trim())) {
      emailError.textContent = 'صيغة البريد الإلكتروني غير صحيحة';
      valid = false;
    } else {
      emailError.textContent = '';
    }

    if (!valid) {
      e.preventDefault();
      return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'جارِ الإرسال ...';
  });

});
